@extends('admin.layouts.app')

@section('title', 'Performance Targets')

@section('content')
<div class="row page-titles">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active"><a href="javascript:void(0)">Target Management</a></li>
    </ol>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Performance Targets</h4>
                <div>
                    <a href="{{ route('admin.targets.create') }}" class="btn btn-primary btn-sm">
                        <i class="fa fa-plus me-2"></i>Add New Target
                    </a>
                </div>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fa fa-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fa fa-exclamation-triangle me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- Filters -->
                <div class="row mb-3">
                    <div class="col-md-12">
                        <form method="GET" action="{{ route('admin.targets.index') }}" class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Target Type</label>
                                <select name="target_type" class="form-control">
                                    <option value="all" {{ request('target_type') == 'all' ? 'selected' : '' }}>All Types</option>
                                    <option value="monthly" {{ request('target_type') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                                    <option value="quarterly" {{ request('target_type') == 'quarterly' ? 'selected' : '' }}>Quarterly</option>
                                    <option value="annual" {{ request('target_type') == 'annual' ? 'selected' : '' }}>Annual</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">BDM</label>
                                <select name="bdm_id" class="form-control">
                                    <option value="all" {{ request('bdm_id') == 'all' ? 'selected' : '' }}>All BDMs</option>
                                    @foreach($bdms as $bdm)
                                        <option value="{{ $bdm->id }}" {{ request('bdm_id') == $bdm->id ? 'selected' : '' }}>
                                            {{ $bdm->name }} ({{ $bdm->employee_code }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">&nbsp;</label>
                                <button type="submit" class="btn btn-primary d-block">
                                    <i class="fa fa-filter me-2"></i>Apply Filters
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-responsive-md">
                        <thead>
                            <tr>
                                <th><strong>#</strong></th>
                                <th><strong>EMPLOYEE</strong></th>
                                <th><strong>TYPE</strong></th>
                                <th><strong>PERIOD</strong></th>
                                <th><strong>REVENUE TARGET</strong></th>
                                <th><strong>PROJECTS TARGET</strong></th>
                                <th><strong>ACHIEVEMENT</strong></th>
                                <th><strong>PROGRESS</strong></th>
                                <th><strong>ACTIONS</strong></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($targets as $target)
                                <tr>
                                    <td>{{ $loop->iteration + ($targets->currentPage() - 1) * $targets->perPage() }}</td>
                                    <td>
                                        <strong>{{ $target->bdm->name ?? 'N/A' }}</strong><br>
                                        <small class="text-muted">{{ $target->bdm->employee_code ?? 'N/A' }}</small>
                                    </td>
                                    <td>
                                        @if($target->target_type == 'monthly')
                                            <span class="badge badge-primary">Monthly</span>
                                        @elseif($target->target_type == 'quarterly')
                                            <span class="badge badge-info">Quarterly</span>
                                        @else
                                            <span class="badge badge-success">Annual</span>
                                        @endif
                                    </td>
                                    <td>{{ $target->period }}</td>
                                    <td>
                                        ₹{{ number_format($target->revenue_achieved, 0) }} / ₹{{ number_format($target->revenue_target, 0) }}
                                    </td>
                                    <td>
                                        {{ $target->projects_achieved }} / {{ $target->project_target }}
                                    </td>
                                    <td>
                                        @php
                                            $percentage = $target->achievement_percentage ?? 0;
                                        @endphp
                                        <span class="badge badge-lg 
                                            @if($percentage >= 100) badge-success
                                            @elseif($percentage >= 75) badge-info
                                            @elseif($percentage >= 50) badge-warning
                                            @else badge-danger
                                            @endif
                                        ">
                                            {{ number_format($percentage, 1) }}%
                                        </span>
                                    </td>
                                    <td>
                                        <div class="progress" style="height: 20px;">
                                            <div class="progress-bar 
                                                @if($percentage >= 100) bg-success
                                                @elseif($percentage >= 75) bg-info
                                                @elseif($percentage >= 50) bg-warning
                                                @else bg-danger
                                                @endif
                                            " role="progressbar" style="width: {{ min($percentage, 100) }}%">
                                                {{ number_format($percentage, 0) }}%
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="dropdown">
                                            <button type="button" class="btn btn-success light sharp" data-bs-toggle="dropdown" aria-expanded="false">
                                                <svg width="20px" height="20px" viewBox="0 0 24 24" version="1.1">
                                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                        <rect x="0" y="0" width="24" height="24"/>
                                                        <circle fill="#000000" cx="5" cy="12" r="2"/>
                                                        <circle fill="#000000" cx="12" cy="12" r="2"/>
                                                        <circle fill="#000000" cx="19" cy="12" r="2"/>
                                                    </g>
                                                </svg>
                                            </button>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item" href="{{ route('admin.targets.show', $target->id) }}">
                                                    <i class="fa fa-eye me-2"></i>View Details
                                                </a>
                                                <a class="dropdown-item" href="{{ route('admin.targets.edit', $target->id) }}">
                                                    <i class="fa fa-edit me-2"></i>Edit
                                                </a>
                                                <button type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#updateAchievementModal{{ $target->id }}">
                                                    <i class="fa fa-chart-line me-2"></i>Update Achievement
                                                </button>
                                                <form action="{{ route('admin.targets.destroy', $target->id) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Are you sure you want to delete this target?')">
                                                        <i class="fa fa-trash me-2"></i>Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Update Achievement Modal -->
                                <div class="modal fade" id="updateAchievementModal{{ $target->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="{{ route('admin.targets.update-achievement', $target->id) }}" method="POST">
                                                @csrf
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Update Achievement - {{ $target->bdm->name }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-group mb-3">
                                                        <label>Revenue Achieved <span class="text-danger">*</span></label>
                                                        <input type="number" name="revenue_achieved" class="form-control" value="{{ $target->revenue_achieved }}" min="0" step="0.01" required>
                                                        <small class="text-muted">Target: ₹{{ number_format($target->revenue_target, 2) }}</small>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Projects Achieved <span class="text-danger">*</span></label>
                                                        <input type="number" name="projects_achieved" class="form-control" value="{{ $target->projects_achieved }}" min="0" required>
                                                        <small class="text-muted">Target: {{ $target->project_target }} projects</small>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-primary">Update Achievement</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center text-muted py-4">
                                        <i class="fa fa-inbox fa-3x mb-3 d-block"></i>
                                        No targets found. <a href="{{ route('admin.targets.create') }}">Create your first target</a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if($targets->hasPages())
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div>
                            Showing {{ $targets->firstItem() }} to {{ $targets->lastItem() }} of {{ $targets->total() }} entries
                        </div>
                        <div>
                            {{ $targets->links() }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
