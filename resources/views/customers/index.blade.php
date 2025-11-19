@extends('layouts.app')

@section('title', 'Customers - Konnectix BDM')

@section('page-title', 'BDM - Customer Management')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">All Customers</h4>
                <a href="{{ route('customers.create') }}" class="btn btn-primary btn-sm">
                    <i class="fa fa-plus"></i> Add New Customer
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-responsive-md">
                        <thead>
                            <tr>
                                <th style="width:50px;">
                                    <div class="form-check custom-checkbox checkbox-success check-lg me-3">
                                        <input type="checkbox" class="form-check-input" id="checkAll">
                                        <label class="form-check-label" for="checkAll"></label>
                                    </div>
                                </th>
                                <th><strong>CUSTOMER NAME</strong></th>
                                <th><strong>COMPANY</strong></th>
                                <th><strong>CONTACT</strong></th>
                                <th><strong>EMAIL</strong></th>
                                <th><strong>PROJECT TYPE</strong></th>
                                <th><strong>PROJECT VALUE</strong></th>
                                <th><strong>LEAD SOURCE</strong></th>
                                <th><strong>DATE ADDED</strong></th>
                                <th><strong>PROJECT START</strong></th>
                                <th><strong>STATUS</strong></th>
                                <th><strong>ACTIONS</strong></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($customers as $customer)
                            <tr>
                                <td>
                                    <div class="form-check custom-checkbox checkbox-success check-lg me-3">
                                        <input type="checkbox" class="form-check-input" id="customCheckBox{{ $customer->id }}">
                                        <label class="form-check-label" for="customCheckBox{{ $customer->id }}"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <span class="w-space-no">{{ $customer->customer_name }}</span>
                                    </div>
                                </td>
                                <td>{{ $customer->company_name ?? '-' }}</td>
                                <td>
                                    <div>
                                        <strong>{{ $customer->number }}</strong>
                                        @if($customer->alternate_number)
                                            <br><small class="text-muted">{{ $customer->alternate_number }}</small>
                                        @endif
                                    </div>
                                </td>
                                <td>{{ $customer->email ?? '-' }}</td>
                                <td>{{ $customer->formatted_project_type ?? '-' }}</td>
                                <td>
                                    @if($customer->project_valuation)
                                        <span class="text-success fw-bold">₹{{ number_format($customer->project_valuation, 2) }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge badge-{{ $customer->lead_source == 'facebook' ? 'primary' : ($customer->lead_source == 'google' ? 'info' : ($customer->lead_source == 'justdial' ? 'warning' : 'secondary')) }}">
                                        {{ $customer->lead_source ? str_replace('_', ' ', ucwords($customer->lead_source, '_')) : '-' }}
                                    </span>
                                </td>
                                <td>
                                    <div>
                                        <strong>{{ $customer->created_at->format('d M Y') }}</strong>
                                        <br><small class="text-muted">{{ $customer->created_at->format('H:i A') }}</small>
                                    </div>
                                </td>
                                <td>
                                    @if($customer->project_start_date)
                                        {{ $customer->project_start_date->format('d M Y') }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($customer->active)
                                        <span class="badge light badge-success">
                                            <i class="fa fa-circle text-success me-1"></i>
                                            Active
                                        </span>
                                    @else
                                        <span class="badge light badge-danger">
                                            <i class="fa fa-circle text-danger me-1"></i>
                                            Inactive
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-primary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" data-bs-auto-close="true">
                                            Actions
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end" style="position: absolute; z-index: 1050;">
                                            <li><a class="dropdown-item" href="{{ route('customers.show', $customer) }}"><i class="fa fa-eye me-2"></i>View</a></li>
                                            <li><a class="dropdown-item" href="{{ route('customers.edit', $customer) }}"><i class="fa fa-pencil me-2"></i>Edit</a></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li><a class="dropdown-item text-primary" href="#"><i class="fa fa-file-text me-2"></i>Proposal</a></li>
                                            <li><a class="dropdown-item text-info" href="#"><i class="fa fa-file-contract me-2"></i>Contract</a></li>
                                            <li><a class="dropdown-item text-success" href="#"><i class="fa fa-file-invoice me-2"></i>Invoices</a></li>
                                            <li><a class="dropdown-item text-warning" href="#"><i class="fa fa-tools me-2"></i>Maintenance</a></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <form action="{{ route('customers.destroy', $customer) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this customer?');" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger"><i class="fa fa-trash me-2"></i>Delete</button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="12" class="text-center">No customers found. <a href="{{ route('customers.create') }}">Add your first customer</a></td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div>
                        Showing {{ $customers->firstItem() ?? 0 }} to {{ $customers->lastItem() ?? 0 }} of {{ $customers->total() }} customers
                    </div>
                    <div>
                        {{ $customers->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
