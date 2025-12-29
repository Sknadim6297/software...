@extends('admin.layouts.app')

@section('title', 'Maintenance Contracts')

@section('content')
<div class="container-fluid">
    <div class="row page-titles">
        <div class="col p-md-0">
            <h4>Maintenance Contracts</h4>
        </div>
        <div class="col p-md-0">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.projects.index') }}">Projects</a></li>
                <li class="breadcrumb-item active">Maintenance Contracts</li>
            </ol>
        </div>
    </div>

    {{-- Revenue Summary --}}
    <div class="row">
        <div class="col-lg-4 col-sm-6">
            <div class="card gradient-1">
                <div class="card-body">
                    <h3 class="card-title text-white">Monthly Revenue</h3>
                    <div class="d-inline-block">
                        <h2 class="text-white">₹{{ number_format($monthlyRevenue, 2) }}</h2>
                        <p class="text-white mb-0">Per Month</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-sm-6">
            <div class="card gradient-2">
                <div class="card-body">
                    <h3 class="card-title text-white">Quarterly Revenue</h3>
                    <div class="d-inline-block">
                        <h2 class="text-white">₹{{ number_format($quarterlyRevenue, 2) }}</h2>
                        <p class="text-white mb-0">Per Quarter</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-sm-6">
            <div class="card gradient-3">
                <div class="card-body">
                    <h3 class="card-title text-white">Annual Revenue</h3>
                    <div class="d-inline-block">
                        <h2 class="text-white">₹{{ number_format($annualRevenue, 2) }}</h2>
                        <p class="text-white mb-0">Per Year</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">
                        <i class="flaticon-381-heart me-2"></i>Active Maintenance Contracts
                    </h4>
                </div>
                <div class="card-body">
                    {{-- Filters --}}
                    <form method="GET" action="{{ route('admin.projects.maintenance') }}" class="mb-4">
                        <div class="row">
                            <div class="col-md-4">
                                <label class="form-label">Type</label>
                                <select name="type" class="form-control">
                                    <option value="">All Types</option>
                                    <option value="Free" {{ request('type') === 'Free' ? 'selected' : '' }}>Free</option>
                                    <option value="Chargeable" {{ request('type') === 'Chargeable' ? 'selected' : '' }}>Chargeable</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">BDM</label>
                                <select name="bdm_id" class="form-control">
                                    <option value="">All BDMs</option>
                                    @foreach($bdms as $bdm)
                                        <option value="{{ $bdm->id }}" {{ request('bdm_id') == $bdm->id ? 'selected' : '' }}>{{ $bdm->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary me-2">
                                    <i class="flaticon-381-search-1 me-1"></i>Filter
                                </button>
                                <a href="{{ route('admin.projects.maintenance') }}" class="btn btn-secondary">
                                    <i class="flaticon-381-back me-1"></i>Clear
                                </a>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>BDM</th>
                                    <th>Customer</th>
                                    <th>Project</th>
                                    <th>Type</th>
                                    <th>Duration/Billing</th>
                                    <th>Charge</th>
                                    <th>Start Date</th>
                                    <th>Domain Renewal</th>
                                    <th>Hosting Renewal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($projects as $index => $project)
                                    <tr>
                                        <td>{{ $projects->firstItem() + $index }}</td>
                                        <td>{{ $project->bdm->name ?? 'N/A' }}</td>
                                        <td>{{ $project->customer_name }}</td>
                                        <td>{{ $project->project_name }}</td>
                                        <td>
                                            <span class="badge badge-{{ $project->maintenance_type === 'Free' ? 'success' : 'info' }}">
                                                {{ $project->maintenance_type }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($project->maintenance_type === 'Free')
                                                {{ $project->maintenance_months }} months
                                            @else
                                                {{ $project->maintenance_billing_cycle }}
                                            @endif
                                        </td>
                                        <td>
                                            @if($project->maintenance_type === 'Chargeable')
                                                <strong>₹{{ number_format($project->maintenance_charge, 2) }}</strong>
                                            @else
                                                <span class="text-muted">Free</span>
                                            @endif
                                        </td>
                                        <td>{{ $project->maintenance_start_date ? \Carbon\Carbon::parse($project->maintenance_start_date)->format('d M, Y') : 'N/A' }}</td>
                                        <td>
                                            @if($project->domain_renewal_date)
                                                {{ \Carbon\Carbon::parse($project->domain_renewal_date)->format('d M, Y') }}
                                                <br><small class="text-muted">{{ $project->domain_renewal_cycle }}</small>
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($project->hosting_renewal_date)
                                                {{ \Carbon\Carbon::parse($project->hosting_renewal_date)->format('d M, Y') }}
                                                <br><small class="text-muted">{{ $project->hosting_renewal_cycle }}</small>
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center text-muted py-4">No maintenance contracts found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

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
