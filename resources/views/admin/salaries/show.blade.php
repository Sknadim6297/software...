@extends('admin.layouts.app')

@section('title', 'Salary Details')

@section('content')
<div class="row page-titles">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.salaries.index') }}">Salaries</a></li>
        <li class="breadcrumb-item active"><a href="javascript:void(0)">Salary Details</a></li>
    </ol>
</div>

<div class="row">
    <div class="col-12">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fa fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
    </div>

    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Salary Details - {{ $salary->month_year }}</h4>
                <div>
                    <a href="{{ route('admin.salaries.edit', $salary->id) }}" class="btn btn-primary btn-sm me-2">
                        <i class="fa fa-edit me-1"></i>Edit
                    </a>
                    <a href="{{ route('admin.salaries.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fa fa-arrow-left me-1"></i>Back
                    </a>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th width="30%">Employee Name:</th>
                        <td>{{ $salary->bdm->name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Employee Code:</th>
                        <td>{{ $salary->bdm->employee_code ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Month/Year:</th>
                        <td>{{ \Carbon\Carbon::parse($salary->month_year)->format('F Y') }}</td>
                    </tr>
                    <tr>
                        <th>Basic Salary:</th>
                        <td>₹{{ number_format($salary->basic_salary, 2) }}</td>
                    </tr>
                    <tr>
                        <th>HRA:</th>
                        <td>₹{{ number_format($salary->hra, 2) }}</td>
                    </tr>
                    <tr>
                        <th>Other Allowances:</th>
                        <td>₹{{ number_format($salary->other_allowances, 2) }}</td>
                    </tr>
                    <tr>
                        <th>Gross Salary:</th>
                        <td><strong>₹{{ number_format($salary->gross_salary, 2) }}</strong></td>
                    </tr>
                    <tr>
                        <th>Deductions:</th>
                        <td class="text-danger">₹{{ number_format($salary->deductions, 2) }}</td>
                    </tr>
                    <tr>
                        <th>Net Salary:</th>
                        <td><strong class="text-success">₹{{ number_format($salary->net_salary, 2) }}</strong></td>
                    </tr>
                    <tr>
                        <th>Salary Slip:</th>
                        <td>
                            @if($salary->salary_slip_path)
                                <a href="{{ route('admin.salaries.download', $salary->id) }}" class="btn btn-sm btn-success">
                                    <i class="fa fa-download me-1"></i>Download Slip
                                </a>
                            @else
                                <span class="badge badge-warning">No Slip Uploaded</span>
                            @endif
                        </td>
                    </tr>
                    @if($salary->remarks)
                    <tr>
                        <th>Remarks:</th>
                        <td>{{ $salary->remarks }}</td>
                    </tr>
                    @endif
                    <tr>
                        <th>Created At:</th>
                        <td>{{ $salary->created_at->format('d M Y, h:i A') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Salary Breakdown</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <h6 class="text-muted">Earnings</h6>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Basic Salary</span>
                        <strong>₹{{ number_format($salary->basic_salary, 2) }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>HRA</span>
                        <strong>₹{{ number_format($salary->hra, 2) }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Allowances</span>
                        <strong>₹{{ number_format($salary->other_allowances, 2) }}</strong>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between">
                        <strong>Gross Salary</strong>
                        <strong class="text-success">₹{{ number_format($salary->gross_salary, 2) }}</strong>
                    </div>
                </div>

                <div class="mb-3">
                    <h6 class="text-muted">Deductions</h6>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Total Deductions</span>
                        <strong class="text-danger">₹{{ number_format($salary->deductions, 2) }}</strong>
                    </div>
                </div>

                <div class="alert alert-success">
                    <div class="d-flex justify-content-between">
                        <strong>Net Payable</strong>
                        <strong>₹{{ number_format($salary->net_salary, 2) }}</strong>
                    </div>
                </div>

                @if(!$salary->salary_slip_path)
                <div class="mt-3">
                    <button type="button" class="btn btn-primary btn-block" data-bs-toggle="modal" data-bs-target="#uploadSlipModal">
                        <i class="fa fa-upload me-2"></i>Upload Salary Slip
                    </button>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Upload Slip Modal -->
<div class="modal fade" id="uploadSlipModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.salaries.upload-slip', $salary->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Upload Salary Slip</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Select PDF File <span class="text-danger">*</span></label>
                        <input type="file" name="salary_slip" class="form-control" accept=".pdf" required>
                        <small class="text-muted">Only PDF files allowed. Max size: 2MB</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
