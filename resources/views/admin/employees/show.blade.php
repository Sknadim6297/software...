@extends('admin.layouts.app')

@section('title', 'BDM Profile - ' . $employee->name)

@section('content')
<div class="row page-titles">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.employees.index') }}">Employees</a></li>
        <li class="breadcrumb-item active"><a href="javascript:void(0)">{{ $employee->name }}</a></li>
    </ol>
</div>

<div class="row">
    <div class="col-lg-12">
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
    </div>

    <!-- Profile Overview -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body">
                <div class="text-center">
                    @if($employee->profile_image)
                        <img src="{{ asset('storage/' . $employee->profile_image) }}" class="rounded-circle mb-3" width="120" height="120" style="object-fit: cover;" alt="{{ $employee->name }}">
                    @else
                        <img src="{{ asset('template/images/profile/17.jpg') }}" class="rounded-circle mb-3" width="120" height="120" alt="{{ $employee->name }}">
                    @endif
                    <h4 class="mb-1">{{ $employee->name }}</h4>
                    <p class="text-muted">{{ $employee->employee_code }}</p>
                    <span class="badge 
                        @if($employee->status == 'active') badge-success
                        @elseif($employee->status == 'inactive') badge-warning
                        @else badge-danger
                        @endif
                    ">
                        {{ ucfirst($employee->status) }}
                    </span>
                </div>

                <div class="mt-4">
                    <div class="mb-3">
                        <strong><i class="fa fa-envelope me-2"></i>Email:</strong>
                        <p class="mb-0">{{ $employee->email }}</p>
                    </div>
                    <div class="mb-3">
                        <strong><i class="fa fa-phone me-2"></i>Phone:</strong>
                        <p class="mb-0">{{ $employee->phone }}</p>
                    </div>
                    <div class="mb-3">
                        <strong><i class="fa fa-calendar me-2"></i>Joining Date:</strong>
                        <p class="mb-0">{{ $employee->joining_date->format('d M Y') }}</p>
                    </div>
                    <div class="mb-3">
                        <strong><i class="fa fa-money-bill me-2"></i>Current CTC:</strong>
                        <p class="mb-0">₹{{ number_format($employee->current_ctc, 2) }}</p>
                    </div>
                </div>

                <div class="mt-4">
                    <a href="{{ route('admin.employees.edit', $employee->id) }}" class="btn btn-primary btn-block mb-2">
                        <i class="fa fa-edit me-2"></i>Edit Profile
                    </a>
                    <a href="{{ route('admin.employees.index') }}" class="btn btn-secondary btn-block">
                        <i class="fa fa-arrow-left me-2"></i>Back to List
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Details Tabs -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#personal">Personal Info</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#documents">Documents ({{ $employee->documents->count() }})</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#salary">Salary ({{ $employee->salaries->count() }})</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#leaves">Leaves</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#targets">Targets ({{ $employee->targets->count() }})</a>
                    </li>
                </ul>

                <div class="tab-content mt-3">
                    <!-- Personal Info Tab -->
                    <div class="tab-pane fade show active" id="personal">
                        <table class="table table-borderless">
                            <tr>
                                <th width="30%">Father's Name:</th>
                                <td>{{ $employee->father_name }}</td>
                            </tr>
                            <tr>
                                <th>Date of Birth:</th>
                                <td>{{ \Carbon\Carbon::parse($employee->date_of_birth)->format('d M Y') }} ({{ \Carbon\Carbon::parse($employee->date_of_birth)->age }} years)</td>
                            </tr>
                            <tr>
                                <th>Education:</th>
                                <td>{{ $employee->highest_education }}</td>
                            </tr>
                            <tr>
                                <th>Employee Code:</th>
                                <td>{{ $employee->employee_code }}</td>
                            </tr>
                            <tr>
                                <th>Designation:</th>
                                <td><span class="badge badge-primary">{{ $employee->designation ?? 'BDM' }}</span></td>
                            </tr>
                            <tr>
                                <th>Joining Date:</th>
                                <td>{{ $employee->joining_date->format('d M Y') }}</td>
                            </tr>
                            <tr>
                                <th>Current CTC:</th>
                                <td>₹{{ number_format($employee->current_ctc, 2) }}</td>
                            </tr>
                            <tr>
                                <th>Status:</th>
                                <td>
                                    <span class="badge 
                                        @if($employee->status == 'active') badge-success
                                        @elseif($employee->status == 'inactive') badge-warning
                                        @else badge-danger
                                        @endif
                                    ">
                                        {{ ucfirst($employee->status) }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>Can Login:</th>
                                <td>{{ $employee->can_login ? 'Yes' : 'No' }}</td>
                            </tr>
                            <tr>
                                <th>Warning Count:</th>
                                <td>{{ $employee->warning_count }}</td>
                            </tr>
                        </table>
                    </div>

                    <!-- Documents Tab -->
                    <div class="tab-pane fade" id="documents">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Document Type</th>
                                        <th>Uploaded Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($employee->documents as $document)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ ucwords(str_replace('_', ' ', $document->document_type)) }}</td>
                                            <td>{{ $document->created_at->format('d M Y') }}</td>
                                            <td>
                                                <a href="{{ asset('storage/' . $document->file_path) }}" target="_blank" class="btn btn-sm btn-primary">
                                                    <i class="fa fa-download"></i> Download
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted">No documents uploaded</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Salary Tab -->
                    <div class="tab-pane fade" id="salary">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Month</th>
                                        <th>Gross Salary</th>
                                        <th>Deductions</th>
                                        <th>Net Salary</th>
                                        <th>Slip</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($employee->salaries->take(10) as $salary)
                                        <tr>
                                            <td>{{ $salary->month_year }}</td>
                                            <td>₹{{ number_format($salary->gross_salary, 2) }}</td>
                                            <td>₹{{ number_format($salary->deductions, 2) }}</td>
                                            <td>₹{{ number_format($salary->net_salary, 2) }}</td>
                                            <td>
                                                @if($salary->salary_slip_path)
                                                    <a href="{{ route('admin.salaries.download', $salary->id) }}" class="badge badge-success">
                                                        <i class="fa fa-download"></i>
                                                    </a>
                                                @else
                                                    <span class="badge badge-warning">No Slip</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted">No salary records</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Leaves Tab -->
                    <div class="tab-pane fade" id="leaves">
                        <h5>Leave Balance</h5>
                        @if($employee->leaveBalance)
                            <div class="row mb-4">
                                <div class="col-md-4">
                                    <div class="card bg-primary text-white">
                                        <div class="card-body">
                                            <h4>{{ $employee->leaveBalance->casual_leave }}</h4>
                                            <p class="mb-0">Casual Leave</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card bg-warning text-white">
                                        <div class="card-body">
                                            <h4>{{ $employee->leaveBalance->sick_leave }}</h4>
                                            <p class="mb-0">Sick Leave</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card bg-secondary text-white">
                                        <div class="card-body">
                                            <h4>{{ $employee->leaveBalance->unpaid_leave }}</h4>
                                            <p class="mb-0">Unpaid Leave</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <h5>Recent Leave Applications</h5>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Type</th>
                                        <th>From - To</th>
                                        <th>Days</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($employee->leaveApplications->take(5) as $leave)
                                        <tr>
                                            <td>{{ ucfirst($leave->leave_type) }}</td>
                                            <td>{{ \Carbon\Carbon::parse($leave->from_date)->format('d M Y') }} - {{ \Carbon\Carbon::parse($leave->to_date)->format('d M Y') }}</td>
                                            <td>{{ $leave->days }}</td>
                                            <td>
                                                <span class="badge 
                                                    @if($leave->status == 'approved') badge-success
                                                    @elseif($leave->status == 'pending') badge-warning
                                                    @else badge-danger
                                                    @endif
                                                ">
                                                    {{ ucfirst($leave->status) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted">No leave applications</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Targets Tab -->
                    <div class="tab-pane fade" id="targets">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Period</th>
                                        <th>Type</th>
                                        <th>Revenue Target</th>
                                        <th>Achievement</th>
                                        <th>Progress</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($employee->targets->take(10) as $target)
                                        <tr>
                                            <td>{{ $target->period }}</td>
                                            <td>{{ ucfirst($target->target_type) }}</td>
                                            <td>₹{{ number_format($target->revenue_target, 2) }}</td>
                                            <td>₹{{ number_format($target->revenue_achieved, 2) }}</td>
                                            <td>
                                                <span class="badge 
                                                    @if($target->achievement_percentage >= 100) badge-success
                                                    @elseif($target->achievement_percentage >= 75) badge-info
                                                    @elseif($target->achievement_percentage >= 50) badge-warning
                                                    @else badge-danger
                                                    @endif
                                                ">
                                                    {{ number_format($target->achievement_percentage, 1) }}%
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted">No targets assigned</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
