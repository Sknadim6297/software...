@extends('layouts.app')

@section('title', 'All Projects - Website, Software & Application Management')
@section('page-title', 'All Projects')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title">
                    <i class="flaticon-381-list me-2"></i>Website, Software & Application Projects
                </h4>
                <a href="{{ route('projects.create') }}" class="btn btn-primary">
                    <i class="flaticon-381-add me-1"></i>Create New Project
                </a>
            </div>
            <div class="card-body">
                @isset($demoFallback)
                    @if($demoFallback)
                        <div class="alert alert-info d-flex align-items-center" role="alert">
                            <i class="flaticon-381-info me-2"></i>
                            <span>Showing sample projects so you can explore the module. Create your own project to see your assignments here.</span>
                        </div>
                    @endif
                @endisset
                {{-- Filters --}}
                <div class="row mb-4">
                    <div class="col-md-3">
                        <label class="form-label">Filter by Status</label>
                        <select class="form-control" onchange="location.href='{{ route('projects.index') }}?status=' + this.value">
                            <option value="">All Projects</option>
                            <option value="in-progress" {{ request('status') === 'in-progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                        </select>
                    </div>
                </div>

                {{-- Alert Messages --}}
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        <i class="flaticon-381-success me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show">
                        <i class="flaticon-381-error me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                {{-- Projects Table --}}
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>S. No</th>
                                <th>Customer Name</th>
                                <th>Project Name</th>
                                <th>Type</th>
                                <th>Project Value</th>
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
                                        <strong>{{ $project->customer_name }}</strong><br>
                                        <small class="text-muted">{{ $project->customer_mobile }}</small>
                                    </td>
                                    <td>{{ $project->project_name }}</td>
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
                                        <div class="progress" style="height: 20px;">
                                            <div class="progress-bar" role="progressbar" style="width: {{ $project->payment_progress }}%" 
                                                aria-valuenow="{{ $project->payment_progress }}" aria-valuemin="0" aria-valuemax="100">
                                                {{ $project->payment_progress }}%
                                            </div>
                                        </div>
                                        <small class="text-muted">₹{{ number_format($project->total_paid, 2) }} / ₹{{ number_format($project->project_valuation, 2) }}</small>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('projects.show', $project->id) }}" class="btn btn-sm btn-info" title="View Details">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                            <a href="{{ route('projects.edit', $project->id) }}" class="btn btn-sm btn-primary" title="Edit Project">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            @if($project->status === 'In Progress' && $project->next_pending_installment)
                                                <a href="{{ route('projects.take-payment', $project->id) }}" class="btn btn-sm btn-success" title="Take Payment">
                                                    <i class="fa fa-money"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-4">
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
                        {{ $projects->links() }}
                    </nav>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
