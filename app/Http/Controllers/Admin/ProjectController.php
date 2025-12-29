<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\BDM;
use App\Models\Contract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProjectController extends Controller
{
    /**
     * Display listing of all projects across all BDMs.
     */
    public function index(Request $request)
    {
        $query = Project::with(['customer', 'bdm']);

        // Filter by status
        if ($request->has('status')) {
            if ($request->status === 'in-progress') {
                $query->where('status', 'In Progress');
            } elseif ($request->status === 'completed') {
                $query->where('status', 'Completed');
            }
        }

        // Filter by BDM
        if ($request->has('bdm_id') && $request->bdm_id) {
            $query->where('bdm_id', $request->bdm_id);
        }

        // Filter by project type
        if ($request->has('project_type') && $request->project_type) {
            $query->where('project_type', $request->project_type);
        }

        // Search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('project_name', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_mobile', 'like', "%{$search}%");
            });
        }

        $projects = $query->latest()->paginate(20);
        $bdms = BDM::all();

        return view('admin.projects.index', compact('projects', 'bdms'));
    }

    /**
     * Display detailed view of a specific project.
     */
    public function show(Project $project)
    {
        $project->load(['customer', 'bdm', 'invoices', 'maintenanceContract']);
        return view('admin.projects.show', compact('project'));
    }

    /**
     * Display payment tracking across all projects.
     */
    public function payments(Request $request)
    {
        $query = Project::with(['customer', 'bdm'])
            ->where('status', 'In Progress');

        // Filter by BDM
        if ($request->has('bdm_id') && $request->bdm_id) {
            $query->where('bdm_id', $request->bdm_id);
        }

        $projects = $query->latest()->paginate(20);
        $bdms = BDM::all();

        // Calculate totals
        $totalValuation = Project::sum('project_valuation');
        $totalPaid = Project::sum(DB::raw('
            COALESCE(upfront_payment_paid, 0) + 
            COALESCE(first_installment_paid, 0) + 
            COALESCE(second_installment_paid, 0) + 
            COALESCE(third_installment_paid, 0)
        '));
        $totalPending = $totalValuation - $totalPaid;

        return view('admin.projects.payments', compact('projects', 'bdms', 'totalValuation', 'totalPaid', 'totalPending'));
    }

    /**
     * Display maintenance contracts.
     */
    public function maintenance(Request $request)
    {
        $query = Project::with(['customer', 'bdm', 'maintenanceContract'])
            ->where('status', 'Completed')
            ->where('maintenance_enabled', true);

        // Filter by maintenance type
        if ($request->has('type') && $request->type) {
            $query->where('maintenance_type', $request->type);
        }

        // Filter by BDM
        if ($request->has('bdm_id') && $request->bdm_id) {
            $query->where('bdm_id', $request->bdm_id);
        }

        $projects = $query->latest()->paginate(20);
        $bdms = BDM::all();

        // Calculate maintenance revenue (chargeable only)
        $monthlyRevenue = Project::where('maintenance_enabled', true)
            ->where('maintenance_type', 'Chargeable')
            ->where('maintenance_billing_cycle', 'Monthly')
            ->sum('maintenance_charge');

        $quarterlyRevenue = Project::where('maintenance_enabled', true)
            ->where('maintenance_type', 'Chargeable')
            ->where('maintenance_billing_cycle', 'Quarterly')
            ->sum('maintenance_charge');

        $annualRevenue = Project::where('maintenance_enabled', true)
            ->where('maintenance_type', 'Chargeable')
            ->where('maintenance_billing_cycle', 'Annually')
            ->sum('maintenance_charge');

        return view('admin.projects.maintenance', compact('projects', 'bdms', 'monthlyRevenue', 'quarterlyRevenue', 'annualRevenue'));
    }

    /**
     * Display statistics dashboard.
     */
    public function statistics()
    {
        $totalProjects = Project::count();
        $inProgressProjects = Project::where('status', 'In Progress')->count();
        $completedProjects = Project::where('status', 'Completed')->count();

        $projectsByType = Project::select('project_type', DB::raw('count(*) as total'))
            ->groupBy('project_type')
            ->get()
            ->pluck('total', 'project_type');

        $projectsByBdm = Project::select('bdm_id', DB::raw('count(*) as total'))
            ->with('bdm')
            ->groupBy('bdm_id')
            ->get();

        $totalRevenue = Project::sum('project_valuation');
        $collectedRevenue = Project::sum(DB::raw('
            COALESCE(upfront_payment_paid, 0) + 
            COALESCE(first_installment_paid, 0) + 
            COALESCE(second_installment_paid, 0) + 
            COALESCE(third_installment_paid, 0)
        '));

        return view('admin.projects.statistics', compact(
            'totalProjects',
            'inProgressProjects',
            'completedProjects',
            'projectsByType',
            'projectsByBdm',
            'totalRevenue',
            'collectedRevenue'
        ));
    }
}
