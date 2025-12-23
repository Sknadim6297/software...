@extends('layouts.app')

@section('title', 'Project Details')

@section('page-title', 'Project Details')

@section('content')
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">{{ $project->project_name }}</h4>
                <a href="{{ route('projects.index') }}" class="btn btn-secondary btn-sm">Back to Projects</a>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                
                <div class="row">
                    <div class="col-md-6">
                        <h5>Customer Information</h5>
                        <table class="table table-borderless">
                            <tr>
                                <th width="40%">Customer Name:</th>
                                <td>{{ $project->customer->name }}</td>
                            </tr>
                            <tr>
                                <th>Mobile No.:</th>
                                <td>{{ $project->customer->phone }}</td>
                            </tr>
                            <tr>
                                <th>Email:</th>
                                <td>{{ $project->customer->email }}</td>
                            </tr>
                        </table>
                    </div>
                    
                    <div class="col-md-6">
                        <h5>Project Information</h5>
                        <table class="table table-borderless">
                            <tr>
                                <th width="40%">Project Type:</th>
                                <td><span class="badge badge-info">{{ $project->project_type }}</span></td>
                            </tr>
                            <tr>
                                <th>Start Date:</th>
                                <td>{{ $project->start_date->format('d M, Y') }}</td>
                            </tr>
                            <tr>
                                <th>Project Coordinator:</th>
                                <td>{{ $project->coordinator->name }}</td>
                            </tr>
                            <tr>
                                <th>Status:</th>
                                <td>
                                    @if($project->project_status === 'In Progress')
                                        <span class="badge badge-warning">In Progress</span>
                                    @else
                                        <span class="badge badge-success">Completed</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                
                <hr>
                
                <div class="row">
                    <div class="col-md-12">
                        <h5>Payment Details</h5>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Installment Type</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($project->upfront_payment > 0)
                                <tr>
                                    <td>Upfront Payment</td>
                                    <td>₹{{ number_format($project->upfront_payment, 2) }}</td>
                                    <td>
                                        @if($project->upfront_paid)
                                            <span class="badge badge-success">Paid</span>
                                        @else
                                            <span class="badge badge-warning">Pending</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if(!$project->upfront_paid)
                                            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#paymentModal" data-type="Upfront" data-amount="{{ $project->upfront_payment }}">
                                                Mark as Paid
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                                @endif
                                
                                @if($project->first_installment > 0)
                                <tr>
                                    <td>First Installment</td>
                                    <td>₹{{ number_format($project->first_installment, 2) }}</td>
                                    <td>
                                        @if($project->first_installment_paid)
                                            <span class="badge badge-success">Paid</span>
                                        @else
                                            <span class="badge badge-warning">Pending</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if(!$project->first_installment_paid && $project->upfront_paid)
                                            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#paymentModal" data-type="First" data-amount="{{ $project->first_installment }}">
                                                Mark as Paid
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                                @endif
                                
                                @if($project->second_installment > 0)
                                <tr>
                                    <td>Second Installment</td>
                                    <td>₹{{ number_format($project->second_installment, 2) }}</td>
                                    <td>
                                        @if($project->second_installment_paid)
                                            <span class="badge badge-success">Paid</span>
                                        @else
                                            <span class="badge badge-warning">Pending</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if(!$project->second_installment_paid && $project->first_installment_paid)
                                            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#paymentModal" data-type="Second" data-amount="{{ $project->second_installment }}">
                                                Mark as Paid
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                                @endif
                                
                                @if($project->third_installment > 0)
                                <tr>
                                    <td>Third Installment</td>
                                    <td>₹{{ number_format($project->third_installment, 2) }}</td>
                                    <td>
                                        @if($project->third_installment_paid)
                                            <span class="badge badge-success">Paid</span>
                                        @else
                                            <span class="badge badge-warning">Pending</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if(!$project->third_installment_paid && $project->second_installment_paid)
                                            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#paymentModal" data-type="Third" data-amount="{{ $project->third_installment }}">
                                                Mark as Paid
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                                @endif
                                
                                <tr>
                                    <th>Total</th>
                                    <th>₹{{ number_format($project->project_valuation, 2) }}</th>
                                    <th colspan="2">
                                        Paid: ₹{{ number_format($project->getTotalPaid(), 2) }} 
                                        ({{ number_format(($project->getTotalPaid() / $project->project_valuation) * 100, 0) }}%)
                                    </th>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                @if($project->maintenanceContract)
                <hr>
                <div class="row">
                    <div class="col-md-12">
                        <h5>Maintenance Contract</h5>
                        <table class="table table-borderless">
                            <tr>
                                <th width="30%">Contract Type:</th>
                                <td>
                                    <span class="badge badge-{{ $project->maintenanceContract->contract_type === 'Free' ? 'success' : 'info' }}">
                                        {{ $project->maintenanceContract->contract_type }}
                                    </span>
                                </td>
                            </tr>
                            @if($project->maintenanceContract->contract_type === 'Free')
                            <tr>
                                <th>Free Months:</th>
                                <td>{{ $project->maintenanceContract->free_months }} months</td>
                            </tr>
                            @else
                            <tr>
                                <th>Charges:</th>
                                <td>₹{{ number_format($project->maintenanceContract->charges, 2) }}</td>
                            </tr>
                            <tr>
                                <th>Frequency:</th>
                                <td>{{ $project->maintenanceContract->charge_frequency }}</td>
                            </tr>
                            @endif
                            <tr>
                                <th>Contract Start:</th>
                                <td>{{ $project->maintenanceContract->contract_start_date->format('d M, Y') }}</td>
                            </tr>
                            @if($project->maintenanceContract->contract_end_date)
                            <tr>
                                <th>Contract End:</th>
                                <td>{{ $project->maintenanceContract->contract_end_date->format('d M, Y') }}</td>
                            </tr>
                            @endif
                        </table>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Payment Modal -->
<div class="modal fade" id="paymentModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Mark Installment as Paid</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('projects.mark-installment-paid', $project) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="installment_type" id="installment_type">
                    <div class="mb-3">
                        <label class="form-label">Installment:</label>
                        <p id="installment_display"></p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Transaction ID</label>
                        <input type="text" name="transaction_id" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Confirm Payment</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    var paymentModal = document.getElementById('paymentModal');
    paymentModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        var type = button.getAttribute('data-type');
        var amount = button.getAttribute('data-amount');
        
        document.getElementById('installment_type').value = type;
        document.getElementById('installment_display').textContent = type + ' - ₹' + parseFloat(amount).toLocaleString('en-IN', {minimumFractionDigits: 2});
    });
});
</script>
@endpush
@endsection
