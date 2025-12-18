@extends('layouts.app')

@section('title', 'Salary & Remuneration')
@section('page-title', 'Salary & Remuneration')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Current CTC</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h3 class="text-primary">₹{{ number_format($bdm->current_ctc, 2) }}</h3>
                            <p class="text-muted">Per Annum</p>
                        </div>
                        <div class="col-md-6 text-end">
                            <p><strong>Monthly CTC:</strong> ₹{{ number_format($bdm->current_ctc / 12, 2) }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Salary History</h4>
                </div>
                <div class="card-body">
                    @if($salaries->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-responsive-md">
                                <thead>
                                    <tr>
                                        <th>Month</th>
                                        <th>Basic Salary</th>
                                        <th>HRA</th>
                                        <th>Other Allowances</th>
                                        <th>Gross Salary</th>
                                        <th>Deductions</th>
                                        <th>Net Salary</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($salaries as $salary)
                                        <tr>
                                            <td><strong>{{ $salary->formatted_month }}</strong></td>
                                            <td>₹{{ number_format($salary->basic_salary, 2) }}</td>
                                            <td>₹{{ number_format($salary->hra, 2) }}</td>
                                            <td>₹{{ number_format($salary->other_allowances, 2) }}</td>
                                            <td>₹{{ number_format($salary->gross_salary, 2) }}</td>
                                            <td class="text-danger">₹{{ number_format($salary->deductions, 2) }}</td>
                                            <td class="text-success"><strong>₹{{ number_format($salary->net_salary, 2) }}</strong></td>
                                            <td>
                                                @if($salary->salary_slip_path)
                                                    <a href="{{ route('bdm.salary.download', $salary->id) }}" class="btn btn-primary btn-sm">
                                                        <i class="fa fa-download"></i> Download Slip
                                                    </a>
                                                @else
                                                    <span class="badge badge-warning">Not Available</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="mt-3">
                            {{ $salaries->links() }}
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="fa fa-info-circle"></i> No salary records found.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
