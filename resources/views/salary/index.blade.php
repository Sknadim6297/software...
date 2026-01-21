@extends('layouts.app')

@section('title', 'My Salary Slips')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0"><i class="fas fa-file-invoice-dollar"></i> My Salary Slips</h4>
                    <a href="{{ route('salary.calculate-current') }}" class="btn btn-light btn-sm">
                        <i class="fas fa-calculator"></i> View Current Month
                    </a>
                </div>
                <div class="card-body">
                    @if($salaries->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover table-striped">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Month</th>
                                        <th>Base Salary</th>
                                        <th>Deductions</th>
                                        <th>Net Salary</th>
                                        <th>Present</th>
                                        <th>Absent</th>
                                        <th>Late</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($salaries as $salary)
                                        <tr>
                                            <td><strong>{{ \Carbon\Carbon::createFromDate($salary->year, $salary->month, 1)->format('F Y') }}</strong></td>
                                            <td>₹{{ number_format($salary->base_salary, 2) }}</td>
                                            <td class="text-danger">₹{{ number_format($salary->total_deductions, 2) }}</td>
                                            <td><strong class="text-success">₹{{ number_format($salary->net_salary, 2) }}</strong></td>
                                            <td><span class="badge badge-success">{{ $salary->present_days }}</span></td>
                                            <td><span class="badge badge-danger">{{ $salary->absent_days }}</span></td>
                                            <td><span class="badge badge-warning">{{ $salary->late_count }}</span></td>
                                            <td>
                                                @if($salary->is_processed)
                                                    <span class="badge badge-success">Processed</span>
                                                @else
                                                    <span class="badge badge-warning">Pending</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('salary.show', $salary) }}" class="btn btn-sm btn-info" title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                @if($salary->payslip_file)
                                                    <a href="{{ route('salary.download', $salary) }}" class="btn btn-sm btn-success" title="Download PDF">
                                                        <i class="fas fa-download"></i>
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{ $salaries->links() }}
                    @else
                        <div class="alert alert-info text-center">
                            <i class="fas fa-inbox"></i> No salary slips available yet.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
