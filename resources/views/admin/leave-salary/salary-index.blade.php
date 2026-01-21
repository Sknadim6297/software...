@extends('admin.layouts.app')

@section('title', 'BDM Salary Management')
@section('page-title', 'BDM Salary Management')

@section('content')
<div class="container-fluid">
    <!-- Month Selection & Actions -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Month Selection & Actions</h4>
                </div>
                <div class="card-body">
                    <form method="GET" class="row g-3 mb-3">
                        <div class="col-md-3">
                            <label class="form-label">Month</label>
                            <select name="month" class="form-control" onchange="this.form.submit()">
                                @for($m = 1; $m <= 12; $m++)
                                    <option value="{{ $m }}" {{ request('month', now()->month) == $m ? 'selected' : '' }}>
                                        {{ \Carbon\Carbon::createFromDate(null, $m, 1)->format('F') }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Year</label>
                            <select name="year" class="form-control" onchange="this.form.submit()">
                                @for($y = now()->year; $y >= now()->year - 3; $y--)
                                    <option value="{{ $y }}" {{ request('year', now()->year) == $y ? 'selected' : '' }}>{{ $y }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-6 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-filter"></i> Filter
                            </button>
                        </div>
                    </form>

                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#generateSalaryModal">
                            <i class="fa fa-cog"></i> Generate Salaries
                        </button>
                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#processSalaryModal">
                            <i class="fa fa-check"></i> Process Month
                        </button>
                        <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#exportModal">
                            <i class="fa fa-download"></i> Export
                        </button>
                        <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#emailModal">
                            <i class="fa fa-envelope"></i> Email Payslips
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Salary Summary Stats -->
    <div class="row">
        @php
            $monthYear = sprintf('%04d-%02d', request('year', now()->year), request('month', now()->month));
            $bdmSalaries = \App\Models\BDMSalary::where('month_year', $monthYear)->get();
            $totalSalaries = $bdmSalaries->count();
            $totalAmount = $bdmSalaries->sum('net_salary');
            $totalDeductions = $bdmSalaries->sum('deductions');
        @endphp
        <div class="col-xl-4 col-sm-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="icon-box bg-primary me-3">
                            <i class="flaticon-381-user text-white"></i>
                        </div>
                        <div>
                            <h2 class="mb-0">{{ $totalSalaries }}</h2>
                            <span class="text-muted">Total BDMs</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-sm-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="icon-box bg-success me-3">
                            <i class="flaticon-381-price-tag text-white"></i>
                        </div>
                        <div>
                            <h2 class="mb-0">₹{{ number_format($totalAmount, 2) }}</h2>
                            <span class="text-muted">Total Net Salary</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-sm-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="icon-box bg-danger me-3">
                            <i class="flaticon-381-notepad text-white"></i>
                        </div>
                        <div>
                            <h2 class="mb-0">₹{{ number_format($totalDeductions, 2) }}</h2>
                            <span class="text-muted">Total Deductions</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Salary List -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Salary Records for {{ \Carbon\Carbon::createFromDate(request('year', now()->year), request('month', now()->month), 1)->format('F Y') }}</h4>
                </div>
                <div class="card-body">
                    @if($bdmSalaries->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="thead-light">
                                    <tr>
                                        <th>#</th>
                                        <th>BDM Name</th>
                                        <th>Basic Salary</th>
                                        <th>Present Days</th>
                                        <th>Leaves (CL/SL/UL)</th>
                                        <th>Deductions</th>
                                        <th>Net Salary</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($bdmSalaries as $index => $salary)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td><strong>{{ $salary->bdm->name }}</strong></td>
                                            <td>₹{{ number_format($salary->basic_salary, 2) }}</td>
                                            <td>
                                                <span class="badge badge-success">{{ $salary->total_present_days }}</span>
                                            </td>
                                            <td>
                                                <span class="badge badge-primary">CL: {{ $salary->casual_leave_taken }}</span>
                                                <span class="badge badge-info">SL: {{ $salary->sick_leave_taken }}</span>
                                                <span class="badge badge-warning">UL: {{ $salary->unpaid_leave_taken }}</span>
                                            </td>
                                            <td class="text-danger">₹{{ number_format($salary->deductions + $salary->leave_deduction, 2) }}</td>
                                            <td><strong class="text-success">₹{{ number_format($salary->net_salary, 2) }}</strong></td>
                                            <td>
                                                @if($salary->is_regenerated)
                                                    <span class="badge badge-warning">Regenerated</span>
                                                @else
                                                    <span class="badge badge-success">Generated</span>
                                                @endif
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#viewModal{{ $salary->id }}">
                                                    <i class="fa fa-eye"></i> View
                                                </button>
                                                <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#editModal{{ $salary->id }}">
                                                    <i class="fa fa-edit"></i> Edit
                                                </button>
                                            </td>
                                        </tr>

                                        <!-- View Modal -->
                                        <div class="modal fade" id="viewModal{{ $salary->id }}" tabindex="-1">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-primary text-white">
                                                        <h5 class="modal-title text-white">Salary Details - {{ $salary->bdm->name }}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <h6>Earnings</h6>
                                                                <table class="table table-sm">
                                                                    <tr>
                                                                        <td>Basic Salary:</td>
                                                                        <td class="text-end"><strong>₹{{ number_format($salary->basic_salary, 2) }}</strong></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>HRA:</td>
                                                                        <td class="text-end">₹{{ number_format($salary->hra, 2) }}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Other Allowances:</td>
                                                                        <td class="text-end">₹{{ number_format($salary->other_allowances, 2) }}</td>
                                                                    </tr>
                                                                    <tr class="table-success">
                                                                        <td><strong>Gross Salary:</strong></td>
                                                                        <td class="text-end"><strong>₹{{ number_format($salary->gross_salary, 2) }}</strong></td>
                                                                    </tr>
                                                                </table>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <h6>Deductions</h6>
                                                                <table class="table table-sm">
                                                                    <tr>
                                                                        <td>Leave Deduction:</td>
                                                                        <td class="text-end text-danger">₹{{ number_format($salary->leave_deduction, 2) }}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Other Deductions:</td>
                                                                        <td class="text-end text-danger">₹{{ number_format($salary->deductions, 2) }}</td>
                                                                    </tr>
                                                                    <tr class="table-info">
                                                                        <td><strong>Net Salary:</strong></td>
                                                                        <td class="text-end"><strong class="text-success">₹{{ number_format($salary->net_salary, 2) }}</strong></td>
                                                                    </tr>
                                                                </table>
                                                            </div>
                                                        </div>
                                                        <hr>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <h6>Attendance & Leave Details</h6>
                                                                <table class="table table-sm">
                                                                    <tr>
                                                                        <td>Present Days:</td>
                                                                        <td><span class="badge badge-success">{{ $salary->total_present_days }}</span></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Casual Leave:</td>
                                                                        <td><span class="badge badge-primary">{{ $salary->casual_leave_taken }}</span></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Sick Leave:</td>
                                                                        <td><span class="badge badge-info">{{ $salary->sick_leave_taken }}</span></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Unpaid Leave:</td>
                                                                        <td><span class="badge badge-warning">{{ $salary->unpaid_leave_taken }}</span></td>
                                                                    </tr>
                                                                </table>
                                                            </div>
                                                        </div>
                                                        @if($salary->remarks)
                                                        <hr>
                                                        <p><strong>Remarks:</strong> {{ $salary->remarks }}</p>
                                                        @endif
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Edit Modal -->
                                        <div class="modal fade" id="editModal{{ $salary->id }}" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form action="{{ route('admin.leave-salary.salary.regenerate', $salary->id) }}" method="POST">
                                                        @csrf
                                                        <div class="modal-header bg-info text-white">
                                                            <h5 class="modal-title text-white">Edit & Regenerate Salary</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label class="form-label">Total Present Days <span class="text-danger">*</span></label>
                                                                <input type="number" name="total_present_days" class="form-control" value="{{ $salary->total_present_days }}" min="0" max="31" required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label">Casual Leave Taken</label>
                                                                <input type="number" name="casual_leave_taken" class="form-control" value="{{ $salary->casual_leave_taken }}" min="0">
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label">Sick Leave Taken</label>
                                                                <input type="number" name="sick_leave_taken" class="form-control" value="{{ $salary->sick_leave_taken }}" min="0">
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label">Unpaid Leave Taken</label>
                                                                <input type="number" name="unpaid_leave_taken" class="form-control" value="{{ $salary->unpaid_leave_taken }}" min="0">
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label">Remarks</label>
                                                                <textarea name="remarks" class="form-control" rows="3">{{ $salary->remarks }}</textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                            <button type="submit" class="btn btn-info">
                                                                <i class="fa fa-save"></i> Regenerate Salary
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="fa fa-info-circle"></i> No salary records found for this month. Click "Generate Salaries" to create salary records.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Generate Salary Modal -->
<div class="modal fade" id="generateSalaryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title text-white">Generate Monthly Salaries</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.salary.generate-monthly') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <p>Generate salary slips for all BDMs for the selected month?</p>
                    <input type="hidden" name="month" value="{{ request('month', now()->month) }}">
                    <input type="hidden" name="year" value="{{ request('year', now()->year) }}">
                    <div class="alert alert-info">
                        <strong>Month:</strong> {{ \Carbon\Carbon::createFromDate(request('year', now()->year), request('month', now()->month), 1)->format('F Y') }}
                    </div>
                    <p class="text-muted"><small>This will calculate salaries based on attendance records and approved leaves.</small></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-cog"></i> Generate Salaries
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Process Salary Modal -->
<div class="modal fade" id="processSalaryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title text-white">Process Month Salaries</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.salary.process-month') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <p>Mark all salaries for this month as processed and finalized?</p>
                    <input type="hidden" name="month" value="{{ request('month', now()->month) }}">
                    <input type="hidden" name="year" value="{{ request('year', now()->year) }}">
                    <div class="alert alert-warning">
                        <i class="fa fa-exclamation-triangle"></i> <strong>Warning:</strong> This action cannot be undone easily.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fa fa-check"></i> Process Month
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Export Modal -->
<div class="modal fade" id="exportModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title text-white">Export Salary Sheet</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.salary.export') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="month" value="{{ request('month', now()->month) }}">
                    <input type="hidden" name="year" value="{{ request('year', now()->year) }}">
                    <div class="mb-3">
                        <label class="form-label">Export Format <span class="text-danger">*</span></label>
                        <select name="format" class="form-control" required>
                            <option value="">-- Select Format --</option>
                            <option value="excel">Excel (.xlsx)</option>
                            <option value="pdf">PDF Document</option>
                            <option value="csv">CSV</option>
                        </select>
                    </div>
                    <p class="text-muted"><small>Export salary sheet for {{ \Carbon\Carbon::createFromDate(request('year', now()->year), request('month', now()->month), 1)->format('F Y') }}</small></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-info">
                        <i class="fa fa-download"></i> Export
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Email Modal -->
<div class="modal fade" id="emailModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title">Email Payslips to BDMs</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.salary.email-payslips') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <p>Send salary slips to all BDMs via email for this month?</p>
                    <input type="hidden" name="month" value="{{ request('month', now()->month) }}">
                    <input type="hidden" name="year" value="{{ request('year', now()->year) }}">
                    <div class="alert alert-info">
                        <i class="fa fa-envelope"></i> Payslips will be sent to registered email addresses.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fa fa-envelope"></i> Send Emails
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
