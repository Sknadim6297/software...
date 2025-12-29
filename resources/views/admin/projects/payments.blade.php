@extends('admin.layouts.app')

@section('title', 'Payment Tracking')

@section('content')
<div class="container-fluid">
    <div class="row page-titles">
        <div class="col p-md-0">
            <h4>Payment Tracking</h4>
        </div>
        <div class="col p-md-0">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.projects.index') }}">Projects</a></li>
                <li class="breadcrumb-item active">Payment Tracking</li>
            </ol>
        </div>
    </div>

    {{-- Summary Cards --}}
    <div class="row">
        <div class="col-lg-4 col-sm-6">
            <div class="card gradient-1">
                <div class="card-body">
                    <h3 class="card-title text-white">Total Project Value</h3>
                    <div class="d-inline-block">
                        <h2 class="text-white">₹{{ number_format($totalValuation, 2) }}</h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-sm-6">
            <div class="card gradient-2">
                <div class="card-body">
                    <h3 class="card-title text-white">Total Collected</h3>
                    <div class="d-inline-block">
                        <h2 class="text-white">₹{{ number_format($totalPaid, 2) }}</h2>
                        <p class="text-white mb-0">{{ $totalValuation > 0 ? number_format(($totalPaid / $totalValuation) * 100, 1) : 0 }}% collected</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-sm-6">
            <div class="card gradient-3">
                <div class="card-body">
                    <h3 class="card-title text-white">Total Pending</h3>
                    <div class="d-inline-block">
                        <h2 class="text-white">₹{{ number_format($totalPending, 2) }}</h2>
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
                        <i class="flaticon-381-coin me-2"></i>Payment Status - In Progress Projects
                    </h4>
                </div>
                <div class="card-body">
                    {{-- Filter --}}
                    <form method="GET" action="{{ route('admin.projects.payments') }}" class="mb-4">
                        <div class="row">
                            <div class="col-md-4">
                                <label class="form-label">Filter by BDM</label>
                                <select name="bdm_id" class="form-control" onchange="this.form.submit()">
                                    <option value="">All BDMs</option>
                                    @foreach($bdms as $bdm)
                                        <option value="{{ $bdm->id }}" {{ request('bdm_id') == $bdm->id ? 'selected' : '' }}>{{ $bdm->name }}</option>
                                    @endforeach
                                </select>
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
                                    <th>Valuation</th>
                                    <th>Upfront</th>
                                    <th>1st Install.</th>
                                    <th>2nd Install.</th>
                                    <th>3rd Install.</th>
                                    <th>Total Paid</th>
                                    <th>Balance</th>
                                    <th>Next Pending</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($projects as $index => $project)
                                    <tr>
                                        <td>{{ $projects->firstItem() + $index }}</td>
                                        <td>{{ $project->bdm->name ?? 'N/A' }}</td>
                                        <td>{{ $project->customer_name }}</td>
                                        <td>{{ $project->project_name }}</td>
                                        <td><strong>₹{{ number_format($project->project_valuation, 2) }}</strong></td>
                                        <td>
                                            @if($project->upfront_payment_paid)
                                                <span class="badge badge-success">✓ ₹{{ number_format($project->upfront_payment, 2) }}</span>
                                            @else
                                                <span class="badge badge-warning">⏳ ₹{{ number_format($project->upfront_payment, 2) }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($project->first_installment_paid)
                                                <span class="badge badge-success">✓ ₹{{ number_format($project->first_installment, 2) }}</span>
                                            @else
                                                <span class="badge badge-warning">⏳ ₹{{ number_format($project->first_installment, 2) }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($project->second_installment_paid)
                                                <span class="badge badge-success">✓ ₹{{ number_format($project->second_installment, 2) }}</span>
                                            @else
                                                <span class="badge badge-warning">⏳ ₹{{ number_format($project->second_installment, 2) }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($project->third_installment_paid)
                                                <span class="badge badge-success">✓ ₹{{ number_format($project->third_installment, 2) }}</span>
                                            @else
                                                <span class="badge badge-warning">⏳ ₹{{ number_format($project->third_installment, 2) }}</span>
                                            @endif
                                        </td>
                                        <td><strong class="text-success">₹{{ number_format($project->total_paid, 2) }}</strong></td>
                                        <td><strong class="text-danger">₹{{ number_format($project->remaining_amount, 2) }}</strong></td>
                                        <td>
                                            @if($project->next_pending_installment)
                                                <span class="badge badge-info">{{ $project->next_pending_installment['label'] }}</span>
                                            @else
                                                <span class="badge badge-success">Fully Paid</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="12" class="text-center text-muted py-4">No in-progress projects found</td>
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
