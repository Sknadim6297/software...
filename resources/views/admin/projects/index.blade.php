@extends('admin.layouts.app')

@section('title', 'All Projects')

@section('content')
<div class="container-fluid">
    <div class="row page-titles">
        <div class="col p-md-0">
            <h4>Project Management</h4>
        </div>
        <div class="col p-md-0">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Projects</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">
                        <i class="flaticon-381-settings-1 me-2"></i>All Projects
                    </h4>
                </div>
                <div class="card-body">
                    {{-- Filters --}}
                    <form method="GET" action="{{ route('admin.projects.index') }}" class="mb-4">
                        <div class="row">
                            <div class="col-md-3">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-control">
                                    <option value="">All Status</option>
                                    <option value="in-progress" {{ request('status') === 'in-progress' ? 'selected' : '' }}>In Progress</option>
                                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">BDM</label>
                                <select name="bdm_id" class="form-control">
                                    <option value="">All BDMs</option>
                                    @foreach($bdms as $bdm)
                                        <option value="{{ $bdm->id }}" {{ request('bdm_id') == $bdm->id ? 'selected' : '' }}>{{ $bdm->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Project Type</label>
                                <select name="project_type" class="form-control">
                                    <option value="">All Types</option>
                                    <option value="Website" {{ request('project_type') === 'Website' ? 'selected' : '' }}>Website</option>
                                    <option value="Software" {{ request('project_type') === 'Software' ? 'selected' : '' }}>Software</option>
                                    <option value="Application" {{ request('project_type') === 'Application' ? 'selected' : '' }}>Application</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Search</label>
                                <input type="text" name="search" class="form-control" placeholder="Customer/Project name..." value="{{ request('search') }}">
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="flaticon-381-search-1 me-1"></i>Filter
                                </button>
                                <a href="{{ route('admin.projects.index') }}" class="btn btn-secondary">
                                    <i class="flaticon-381-back me-1"></i>Clear
                                </a>
                            </div>
                        </div>
                    </form>

                    {{-- Projects Table --}}
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>BDM</th>
                                    <th>Customer</th>
                                    <th>Project Details</th>
                                    <th>Type</th>
                                    <th>Valuation</th>
                                    <th>Status</th>
                                    <th>Payment Progress</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($projects as $index => $project)
                                    <tr>
                                        <td>{{ $projects->firstItem() + $index }}</td>
                                        <td>
                                            <strong>{{ $project->bdm->name ?? 'N/A' }}</strong><br>
                                            <small class="text-muted">{{ $project->bdm->employee_id ?? '' }}</small>
                                        </td>
                                        <td>
                                            <strong>{{ $project->customer_name }}</strong><br>
                                            <small class="text-muted">{{ $project->customer_mobile }}</small>
                                        </td>
                                        <td>
                                            <strong>{{ $project->project_name }}</strong><br>
                                            <small class="text-muted">Start: {{ $project->project_start_date ? \Carbon\Carbon::parse($project->project_start_date)->format('d M, Y') : 'N/A' }}</small>
                                        </td>
                                        <td>
                                            <span class="badge badge-{{ $project->project_type === 'Website' ? 'info' : ($project->project_type === 'Software' ? 'success' : 'warning') }}">
                                                {{ $project->project_type }}
                                            </span>
                                        </td>
                                        <td><strong>₹{{ number_format($project->project_valuation, 2) }}</strong></td>
                                        <td>
                                            <span class="badge badge-{{ $project->status === 'In Progress' ? 'warning' : 'success' }}">
                                                {{ $project->status }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="progress" style="height: 20px; min-width: 120px;">
                                                <div class="progress-bar" role="progressbar" style="width: {{ $project->payment_progress }}%" 
                                                    aria-valuenow="{{ $project->payment_progress }}" aria-valuemin="0" aria-valuemax="100">
                                                    {{ $project->payment_progress }}%
                                                </div>
                                            </div>
                                            <small class="text-muted">₹{{ number_format($project->total_paid, 2) }} / ₹{{ number_format($project->project_valuation, 2) }}</small>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.projects.show', $project->id) }}" class="btn btn-sm btn-info" title="View Details">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center text-muted py-4">
                                            <p><i class="flaticon-381-inbox me-2"></i>No projects found</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    @if($projects->hasPages())
                        <nav>
                            {{ $projects->appends(request()->query())->links() }}
                        </nav>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
