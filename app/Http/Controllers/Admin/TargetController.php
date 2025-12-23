<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BDM;
use App\Models\BDMTarget;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TargetController extends Controller
{
    public function index(Request $request)
    {
        $query = BDMTarget::with('bdm');

        // Filter by period
        if ($request->has('target_type') && $request->target_type != 'all') {
            $query->where('target_type', $request->target_type);
        }

        // Filter by BDM
        if ($request->has('bdm_id') && $request->bdm_id != 'all') {
            $query->where('bdm_id', $request->bdm_id);
        }

        $targets = $query->latest('period')->paginate(20);
        $bdms = BDM::where('status', 'active')->get();
        
        return view('admin.targets.index', compact('targets', 'bdms'));
    }

    public function create()
    {
        $bdms = BDM::where('status', 'active')->get();
        return view('admin.targets.create', compact('bdms'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'bdm_id' => 'required|exists:bdms,id',
            'target_type' => 'required|in:monthly,quarterly,annual',
            'period' => 'required|string',
            'revenue_target' => 'required|numeric|min:0',
            'project_target' => 'required|integer|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        BDMTarget::create([
            'bdm_id' => $request->bdm_id,
            'target_type' => $request->target_type,
            'period' => $request->period,
            'revenue_target' => $request->revenue_target,
            'project_target' => $request->project_target,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'projects_achieved' => 0,
            'revenue_achieved' => 0,
            'achievement_percentage' => 0,
        ]);

        return redirect()->route('admin.targets.index')
            ->with('success', 'Target created successfully!');
    }

    public function show(BDMTarget $target)
    {
        $target->load('bdm');
        return view('admin.targets.show', compact('target'));
    }

    public function edit(BDMTarget $target)
    {
        $bdms = BDM::where('status', 'active')->get();
        return view('admin.targets.edit', compact('target', 'bdms'));
    }

    public function update(Request $request, BDMTarget $target)
    {
        $request->validate([
            'target_type' => 'required|in:monthly,quarterly,annual',
            'period' => 'required|string',
            'revenue_target' => 'required|numeric|min:0',
            'project_target' => 'required|integer|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'revenue_achieved' => 'nullable|numeric|min:0',
            'projects_achieved' => 'nullable|integer|min:0',
        ]);

        $target->update([
            'target_type' => $request->target_type,
            'period' => $request->period,
            'revenue_target' => $request->revenue_target,
            'project_target' => $request->project_target,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'revenue_achieved' => $request->revenue_achieved ?? $target->revenue_achieved,
            'projects_achieved' => $request->projects_achieved ?? $target->projects_achieved,
        ]);

        return redirect()->route('admin.targets.show', $target->id)
            ->with('success', 'Target updated successfully!');
    }

    public function destroy(BDMTarget $target)
    {
        $target->delete();

        return redirect()->route('admin.targets.index')
            ->with('success', 'Target deleted successfully!');
    }

    public function updateAchievement(Request $request, BDMTarget $target)
    {
        $request->validate([
            'revenue_achieved' => 'required|numeric|min:0',
            'projects_achieved' => 'required|integer|min:0',
        ]);

        $target->revenue_achieved = $request->revenue_achieved;
        $target->projects_achieved = $request->projects_achieved;
        $target->calculateAchievement();

        return back()->with('success', 'Achievement updated successfully!');
    }

    public function bulkCreate()
    {
        $bdms = BDM::where('status', 'active')->get();
        return view('admin.targets.bulk-create', compact('bdms'));
    }

    public function bulkStore(Request $request)
    {
        $request->validate([
            'target_type' => 'required|in:monthly,quarterly,annual',
            'period' => 'required|string',
            'revenue_target' => 'required|numeric|min:0',
            'project_target' => 'required|integer|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'bdm_ids' => 'required|array',
            'bdm_ids.*' => 'exists:bdms,id',
        ]);

        foreach ($request->bdm_ids as $bdmId) {
            BDMTarget::create([
                'bdm_id' => $bdmId,
                'target_type' => $request->target_type,
                'period' => $request->period,
                'revenue_target' => $request->revenue_target,
                'project_target' => $request->project_target,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'projects_achieved' => 0,
                'revenue_achieved' => 0,
                'achievement_percentage' => 0,
            ]);
        }

        return redirect()->route('admin.targets.index')
            ->with('success', 'Targets created successfully for ' . count($request->bdm_ids) . ' BDMs!');
    }
}
