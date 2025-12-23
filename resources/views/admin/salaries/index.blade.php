@extends('admin.layouts.app')

@section('title', 'Salary Records')

@section('content')
<div class="row page-titles">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active"><a href="javascript:void(0)">Salary Management</a></li>
    </ol>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Salary Records</h4>
                <a href="{{ route('admin.salaries.create') }}" class="btn btn-primary btn-sm">
                    <i class="fa fa-plus me-2"></i>Add New Salary Record
                </a>
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

                <div class="table-responsive">
                    <table class="table table-responsive-md">
                        <thead>
                            <tr>
                                <th><strong>#</strong></th>
                                <th><strong>EMPLOYEE</strong></th>
                                <th><strong>CODE</strong></th>
                                <th><strong>MONTH/YEAR</strong></th>
                                <th><strong>BASIC SALARY</strong></th>
                                <th><strong>GROSS SALARY</strong></th>
                                <th><strong>DEDUCTIONS</strong></th>
                                <th><strong>NET SALARY</strong></th>
                                <th><strong>SLIP</strong></th>
                                <th><strong>ACTIONS</strong></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($salaries as $salary)
                                <tr>
                                    <td>{{ $loop->iteration + ($salaries->currentPage() - 1) * $salaries->perPage() }}</td>
                                    <td>{{ $salary->bdm->name ?? 'N/A' }}</td>
                                    <td><strong>{{ $salary->bdm->employee_code ?? 'N/A' }}</strong></td>
                                    <td>{{ $salary->month_year }}</td>
                                    <td>₹{{ number_format($salary->basic_salary, 2) }}</td>
                                    <td>₹{{ number_format($salary->gross_salary, 2) }}</td>
                                    <td>₹{{ number_format($salary->deductions, 2) }}</td>
                                    <td><strong>₹{{ number_format($salary->net_salary, 2) }}</strong></td>
                                    <td>
                                        @if($salary->salary_slip_path)
                                            <a href="{{ route('admin.salaries.download', $salary->id) }}" class="badge badge-success">
                                                <i class="fa fa-download me-1"></i>Download
                                            </a>
                                        @else
                                            <span class="badge badge-warning">No Slip</span>
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
                                                <a class="dropdown-item" href="{{ route('admin.salaries.show', $salary->id) }}">
                                                    <i class="fa fa-eye me-2"></i>View
                                                </a>
                                                <a class="dropdown-item" href="{{ route('admin.salaries.edit', $salary->id) }}">
                                                    <i class="fa fa-edit me-2"></i>Edit
                                                </a>
                                                <form action="{{ route('admin.salaries.destroy', $salary->id) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Are you sure you want to delete this salary record?')">
                                                        <i class="fa fa-trash me-2"></i>Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center text-muted py-4">
                                        <i class="fa fa-inbox fa-3x mb-3 d-block"></i>
                                        No salary records found. <a href="{{ route('admin.salaries.create') }}">Add your first salary record</a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if($salaries->hasPages())
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div>
                            Showing {{ $salaries->firstItem() }} to {{ $salaries->lastItem() }} of {{ $salaries->total() }} entries
                        </div>
                        <div>
                            {{ $salaries->links() }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
