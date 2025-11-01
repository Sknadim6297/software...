@extends('layouts.app')

@section('title', 'Customers - Konnectix')

@section('page-title', 'Customer Management')

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
                                <th><strong>NAME</strong></th>
                                <th><strong>COMPANY</strong></th>
                                <th><strong>PHONE</strong></th>
                                <th><strong>GST NUMBER</strong></th>
                                <th><strong>STATE</strong></th>
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
                                <td><div class="d-flex align-items-center"><span class="w-space-no">{{ $customer->customer_name }}</span></div></td>
                                <td>{{ $customer->company_name ?? '-' }}</td>
                                <td>{{ $customer->number }}</td>
                                <td>{{ $customer->gst_number ?? '-' }}</td>
                                <td>{{ $customer->state_name ?? '-' }}</td>
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
                                    <div class="d-flex">
                                        <a href="{{ route('customers.show', $customer) }}" class="btn btn-info shadow btn-xs sharp me-1" title="View Customer">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                        <a href="{{ route('customers.edit', $customer) }}" class="btn btn-primary shadow btn-xs sharp me-1" title="Edit Customer">
                                            <i class="fa fa-pencil"></i>
                                        </a>
                                        <form action="{{ route('customers.destroy', $customer) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this customer?');" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger shadow btn-xs sharp" title="Delete Customer">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center">No customers found. <a href="{{ route('customers.create') }}">Add your first customer</a></td>
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
