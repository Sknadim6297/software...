@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">All Contracts</h4>
                    <p class="mb-0 text-muted">View and manage all contracts</p>
                </div>
                <div class="card-body">
                    @if($contracts->isEmpty())
                        <div class="text-center py-5">
                            <i class="flaticon-381-notebook-1 text-muted" style="font-size: 72px;"></i>
                            <h5 class="mt-3">No Contracts Yet</h5>
                            <p class="text-muted">Contracts are automatically generated when proposals are accepted</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover table-striped">
                                <thead>
                                    <tr>
                                        <th>Contract #</th>
                                        <th>Customer</th>
                                        <th>Project</th>
                                        <th>Amount</th>
                                        <th>Timeline</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($contracts as $contract)
                                        <tr>
                                            <td><strong>{{ $contract->contract_number }}</strong></td>
                                            <td>
                                                {{ $contract->customer_name }}<br>
                                                <small class="text-muted">{{ $contract->customer_email }}</small>
                                            </td>
                                            <td>{{ $contract->project_type }}</td>
                                            <td>{{ $contract->currency }} {{ number_format($contract->final_amount, 2) }}</td>
                                            <td>
                                                <small>
                                                    {{ \Carbon\Carbon::parse($contract->start_date)->format('d M Y') }}<br>
                                                    <span class="text-muted">{{ $contract->expected_completion_date }}</span>
                                                </small>
                                            </td>
                                            <td>
                                                <span class="badge badge-{{ $contract->getStatusBadgeColor() }}">
                                                    {{ ucfirst(str_replace('_', ' ', $contract->status)) }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('contracts.show', $contract->id) }}" class="btn btn-sm btn-primary">
                                                    <i class="flaticon-381-view me-1"></i> View
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-center mt-4">
                            {{ $contracts->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
