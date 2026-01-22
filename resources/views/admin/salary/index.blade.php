@extends('admin.layouts.app')

@section('title', 'Salary Management')

@section('content')
<div class="container-fluid">
    <div class="row page-titles">
        <div class="col p-md-0">
            <h4>Salary Management</h4>
        </div>
        <div class="col p-md-0">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Salary</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Month Selection & Actions</h4>
                </div>
                <div class="card-body">
                    <form method="GET" class="form-inline mb-3">
                        <div class="form-group mr-2">
                            <label class="mr-2">Month:</label>
                            <select name="month" class="form-control" onchange="this.form.submit()">
                                @for($m = 1; $m <= 12; $m++)
                                    <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                                        {{ \Carbon\Carbon::createFromDate(null, $m, 1)->format('F') }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                        <div class="form-group mr-2">
                            <label class="mr-2">Year:</label>
                            <select name="year" class="form-control" onchange="this.form.submit()">
                                @for($y = now()->year; $y >= now()->year - 2; $y--)
                                    <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                                @endfor
                            </select>
                        </div>
                    </form>

                    <div class="btn-group">
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

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Employee</th>
                                    <th>Base Salary</th>
                                    <th>Present/Absent/Half</th>
                                    <th>Late</th>
                                    <th>Deductions</th>
                                    <th>Net Salary</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($salaries as $salary)
                                    <tr>
                                        <td>{{ $salary->bdm->user->name ?? 'N/A' }}</td>
                                        <td>₹{{ number_format($salary->basic_salary, 2) }}</td>
                                        <td>
                                            <span class="badge badge-success">{{ $salary->total_present_days ?? 0 }}</span> /
                                            <span class="badge badge-danger">{{ $salary->total_leaves ?? 0 }}</span> /
                                            <span class="badge badge-warning">0</span>
                                        </td>
                                        <td><span class="badge badge-info">0</span></td>
                                        <td class="text-danger">₹{{ number_format($salary->deductions, 2) }}</td>
                                        <td><strong class="text-success">₹{{ number_format($salary->net_salary, 2) }}</strong></td>
                                        <td>
                                            @if($salary->is_processed)
                                                <span class="badge badge-success">Processed</span>
                                            @else
                                                <span class="badge badge-warning">Pending</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.salary.show', $salary) }}" class="btn btn-sm btn-primary">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                            @if($salary->bdm && $salary->bdm->user)
                                                <a href="{{ route('admin.salary.settings', $salary->bdm->user) }}" class="btn btn-sm btn-info">
                                                    <i class="fa fa-cog"></i>
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-muted">No salary records for this month</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{ $salaries->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Generate Salary Modal -->
<div class="modal fade" id="generateSalaryModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Generate Salaries</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.salary.generate-monthly') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <p>Generate salaries for all employees for the selected month?</p>
                    <input type="hidden" name="month" value="{{ $month }}">
                    <input type="hidden" name="year" value="{{ $year }}">
                    <div class="alert alert-info">
                        <strong>Month:</strong> {{ \Carbon\Carbon::createFromDate($year, $month, 1)->format('F Y') }}
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Generate</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Process Salary Modal -->
<div class="modal fade" id="processSalaryModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Process Salaries</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.salary.process-month') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <p>Mark all salaries as processed for this month?</p>
                    <input type="hidden" name="month" value="{{ $month }}">
                    <input type="hidden" name="year" value="{{ $year }}">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Process</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Export Modal -->
<div class="modal fade" id="exportModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Export Salary Sheet</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.salary.export') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="month" value="{{ $month }}">
                    <input type="hidden" name="year" value="{{ $year }}">
                    <div class="form-group">
                        <label>Format:</label>
                        <select name="format" class="form-control" required>
                            <option value="excel">Excel (.xlsx)</option>
                            <option value="pdf">PDF</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-info">Export</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Email Modal -->
<div class="modal fade" id="emailModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Email Payslips</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.salary.email-payslips') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <p>Send payslips to all employees via email?</p>
                    <input type="hidden" name="month" value="{{ $month }}">
                    <input type="hidden" name="year" value="{{ $year }}">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-warning">Send Emails</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
