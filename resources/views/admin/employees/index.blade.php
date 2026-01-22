@extends('admin.layouts.app')

@section('title', 'Employees')

@section('content')
<div class="row page-titles">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active"><a href="javascript:void(0)">Employee Management</a></li>
    </ol>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Employee List</h4>
                <a href="{{ route('admin.employees.create') }}" class="btn btn-primary btn-sm">
                    <i class="fa fa-plus me-2"></i>Add New Employee
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-responsive-md">
                        <thead>
                            <tr>
                                <th><strong>#</strong></th>
                                <th><strong>CODE</strong></th>
                                <th><strong>NAME</strong></th>
                                <th><strong>DESIGNATION</strong></th>
                                <th><strong>EMAIL</strong></th>
                                <th><strong>PHONE</strong></th>
                                <th><strong>JOINING DATE</strong></th>
                                <th><strong>CTC</strong></th>
                                <th><strong>STATUS</strong></th>
                                <th><strong>ACTIONS</strong></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bdms as $bdm)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td><strong>{{ $bdm->employee_code }}</strong></td>
                                    <td>{{ $bdm->name }}</td>
                                    <td><span class="badge badge-primary">{{ $bdm->designation ?? 'BDM' }}</span></td>
                                    <td>{{ $bdm->email }}</td>
                                    <td>{{ $bdm->phone }}</td>
                                    <td>{{ $bdm->joining_date->format('d M Y') }}</td>
                                    <td>₹{{ number_format($bdm->current_ctc, 2) }}</td>
                                    <td>
                                        @if($bdm->status == 'active')
                                            <span class="badge light badge-success">
                                                <i class="fa fa-circle text-success me-1"></i>Active
                                            </span>
                                        @elseif($bdm->status == 'inactive')
                                            <span class="badge light badge-warning">
                                                <i class="fa fa-circle text-warning me-1"></i>Inactive
                                            </span>
                                        @else
                                            <span class="badge light badge-danger">
                                                <i class="fa fa-circle text-danger me-1"></i>Terminated
                                            </span>
                                        @endif
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
                                                <a class="dropdown-item" href="{{ route('admin.employees.show', $bdm->id) }}">
                                                    <i class="fa fa-eye me-2"></i>View
                                                </a>
                                                <a class="dropdown-item" href="{{ route('admin.employees.edit', $bdm->id) }}">
                                                    <i class="fa fa-edit me-2"></i>Edit
                                                </a>
                                                @if($bdm->status == 'active')
                                                    <form action="{{ route('admin.employees.deactivate', $bdm->id) }}" method="POST" style="display:inline;">
                                                        @csrf
                                                        <button type="submit" class="dropdown-item text-warning" onclick="return confirm('Are you sure?')">
                                                            <i class="fa fa-ban me-2"></i>Deactivate
                                                        </button>
                                                    </form>
                                                @elseif($bdm->status == 'inactive')
                                                    <form action="{{ route('admin.employees.activate', $bdm->id) }}" method="POST" style="display:inline;">
                                                        @csrf
                                                        <button type="submit" class="dropdown-item text-success" onclick="return confirm('Are you sure?')">
                                                            <i class="fa fa-check me-2"></i>Activate
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center text-muted py-4">
                                        <i class="fa fa-inbox fa-3x mb-3 d-block"></i>
                                        No employees found. <a href="{{ route('admin.employees.create') }}">Add your first employee</a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if($bdms->hasPages())
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div>
                            Showing {{ $bdms->firstItem() }} to {{ $bdms->lastItem() }} of {{ $bdms->total() }} entries
                        </div>
                        <div>
                            {{ $bdms->links() }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
