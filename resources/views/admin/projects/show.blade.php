@extends('admin.layouts.app')

@section('title', 'Project Details')

@section('content')
<div class="container-fluid">
    <div class="row page-titles">
        <div class="col p-md-0">
            <h4>Project Details</h4>
        </div>
        <div class="col p-md-0">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.projects.index') }}">Projects</a></li>
                <li class="breadcrumb-item active">{{ $project->project_name }}</li>
            </ol>
        </div>
    </div>

    <div class="row">
        {{-- Project Information --}}
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Project Information</h4>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th width="30%">Project Name</th>
                            <td>{{ $project->project_name }}</td>
                        </tr>
                        <tr>
                            <th>Project Type</th>
                            <td><span class="badge badge-{{ $project->project_type === 'Website' ? 'info' : ($project->project_type === 'Software' ? 'success' : 'warning') }}">{{ $project->project_type }}</span></td>
                        </tr>
                        <tr>
                            <th>Customer Name</th>
                            <td>{{ $project->customer_name }}</td>
                        </tr>
                        <tr>
                            <th>Customer Mobile</th>
                            <td>{{ $project->customer_mobile }}</td>
                        </tr>
                        <tr>
                            <th>Customer Email</th>
                            <td>{{ $project->customer_email }}</td>
                        </tr>
                        <tr>
                            <th>BDM</th>
                            <td>{{ $project->bdm->name ?? 'N/A' }} ({{ $project->bdm->employee_id ?? 'N/A' }})</td>
                        </tr>
                        <tr>
                            <th>Coordinator</th>
                            <td>{{ $project->coordinator_name }}</td>
                        </tr>
                        <tr>
                            <th>Start Date</th>
                            <td>{{ $project->project_start_date ? \Carbon\Carbon::parse($project->project_start_date)->format('d M, Y') : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Project Status</th>
                            <td><span class="badge badge-{{ $project->status === 'In Progress' ? 'warning' : 'success' }}">{{ $project->status }}</span></td>
                        </tr>
                        <tr>
                            <th>Project Valuation</th>
                            <td><strong class="text-primary">₹{{ number_format($project->project_valuation, 2) }}</strong></td>
                        </tr>
                    </table>
                </div>
            </div>

            {{-- Domain & Hosting Details --}}
            @if($project->domain_name || $project->hosting_provider)
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Domain & Hosting Details</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Domain Details</h5>
                            <table class="table table-sm table-bordered">
                                <tr>
                                    <th>Domain Name</th>
                                    <td>{{ $project->domain_name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Purchase Date</th>
                                    <td>{{ $project->domain_purchase_date ? \Carbon\Carbon::parse($project->domain_purchase_date)->format('d M, Y') : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Amount</th>
                                    <td>₹{{ number_format($project->domain_amount ?? 0, 2) }}</td>
                                </tr>
                                <tr>
                                    <th>Renewal Cycle</th>
                                    <td>{{ $project->domain_renewal_cycle ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Renewal Date</th>
                                    <td>{{ $project->domain_renewal_date ? \Carbon\Carbon::parse($project->domain_renewal_date)->format('d M, Y') : 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5>Hosting Details</h5>
                            <table class="table table-sm table-bordered">
                                <tr>
                                    <th>Provider</th>
                                    <td>{{ $project->hosting_provider ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Purchase Date</th>
                                    <td>{{ $project->hosting_purchase_date ? \Carbon\Carbon::parse($project->hosting_purchase_date)->format('d M, Y') : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Amount</th>
                                    <td>₹{{ number_format($project->hosting_amount ?? 0, 2) }}</td>
                                </tr>
                                <tr>
                                    <th>Renewal Cycle</th>
                                    <td>{{ $project->hosting_renewal_cycle ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Renewal Date</th>
                                    <td>{{ $project->hosting_renewal_date ? \Carbon\Carbon::parse($project->hosting_renewal_date)->format('d M, Y') : 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            {{-- Maintenance Contract --}}
            @if($project->maintenance_enabled)
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Maintenance Contract</h4>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th width="30%">Type</th>
                            <td><span class="badge badge-{{ $project->maintenance_type === 'Free' ? 'success' : 'info' }}">{{ $project->maintenance_type }}</span></td>
                        </tr>
                        @if($project->maintenance_type === 'Free')
                        <tr>
                            <th>Duration</th>
                            <td>{{ $project->maintenance_months }} months</td>
                        </tr>
                        @else
                        <tr>
                            <th>Charge</th>
                            <td><strong class="text-success">₹{{ number_format($project->maintenance_charge, 2) }}</strong></td>
                        </tr>
                        <tr>
                            <th>Billing Cycle</th>
                            <td>{{ $project->maintenance_billing_cycle }}</td>
                        </tr>
                        @endif
                        <tr>
                            <th>Start Date</th>
                            <td>{{ $project->maintenance_start_date ? \Carbon\Carbon::parse($project->maintenance_start_date)->format('d M, Y') : 'N/A' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
            @endif
        </div>

        {{-- Payment Progress --}}
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Payment Progress</h4>
                </div>
                <div class="card-body">
                    <div class="progress mb-3" style="height: 30px;">
                        <div class="progress-bar" role="progressbar" style="width: {{ $project->payment_progress }}%" 
                            aria-valuenow="{{ $project->payment_progress }}" aria-valuemin="0" aria-valuemax="100">
                            {{ $project->payment_progress }}%
                        </div>
                    </div>
                    <table class="table table-sm">
                        <tr>
                            <th>Total Valuation</th>
                            <td class="text-end"><strong>₹{{ number_format($project->project_valuation, 2) }}</strong></td>
                        </tr>
                        <tr class="text-success">
                            <th>Total Paid</th>
                            <td class="text-end"><strong>₹{{ number_format($project->total_paid, 2) }}</strong></td>
                        </tr>
                        <tr class="text-danger">
                            <th>Remaining</th>
                            <td class="text-end"><strong>₹{{ number_format($project->remaining_amount, 2) }}</strong></td>
                        </tr>
                    </table>

                    <hr>

                    <h5 class="mt-3">Payment Breakdown</h5>
                    <table class="table table-sm table-bordered">
                        <tr>
                            <th>Upfront Payment</th>
                            <td class="text-end">₹{{ number_format($project->upfront_payment, 2) }}</td>
                            <td width="30px" class="text-center">
                                @if($project->upfront_payment_paid)
                                    <span class="badge badge-success">✓</span>
                                @else
                                    <span class="badge badge-warning">⏳</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>First Installment</th>
                            <td class="text-end">₹{{ number_format($project->first_installment, 2) }}</td>
                            <td class="text-center">
                                @if($project->first_installment_paid)
                                    <span class="badge badge-success">✓</span>
                                @else
                                    <span class="badge badge-warning">⏳</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Second Installment</th>
                            <td class="text-end">₹{{ number_format($project->second_installment, 2) }}</td>
                            <td class="text-center">
                                @if($project->second_installment_paid)
                                    <span class="badge badge-success">✓</span>
                                @else
                                    <span class="badge badge-warning">⏳</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Third Installment</th>
                            <td class="text-end">₹{{ number_format($project->third_installment, 2) }}</td>
                            <td class="text-center">
                                @if($project->third_installment_paid)
                                    <span class="badge badge-success">✓</span>
                                @else
                                    <span class="badge badge-warning">⏳</span>
                                @endif
                            </td>
                        </tr>
                    </table>

                    @if($project->next_pending_installment)
                    <div class="alert alert-info mt-3">
                        <strong>Next Pending:</strong> {{ $project->next_pending_installment['label'] }}<br>
                        <strong>Amount:</strong> ₹{{ number_format($project->next_pending_installment['amount'], 2) }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
