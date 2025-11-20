@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header border-0 pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="card-title">All Proposals</h4>
                            <p class="mb-0 text-muted">Manage and track all your proposals</p>
                        </div>
                        <a href="{{ route('proposals.create') }}" class="btn btn-primary">
                            <i class="flaticon-381-add me-2"></i> Create New Proposal
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            <i class="flaticon-381-check me-2"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($proposals->isEmpty())
                        <div class="text-center py-5">
                            <i class="flaticon-381-file-1 text-muted" style="font-size: 72px;"></i>
                            <h5 class="mt-3">No Proposals Yet</h5>
                            <p class="text-muted">Create your first proposal to get started</p>
                            <a href="{{ route('proposals.create') }}" class="btn btn-primary">
                                <i class="flaticon-381-add me-2"></i> Create First Proposal
                            </a>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Customer</th>
                                        <th>Project Type</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Sent At</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($proposals as $proposal)
                                        <tr>
                                            <td>#{{ $proposal->id }}</td>
                                            <td>
                                                <strong>{{ $proposal->customer_name }}</strong><br>
                                                <small class="text-muted">{{ $proposal->customer_email }}</small>
                                            </td>
                                            <td>{{ $proposal->project_type }}</td>
                                            <td>{{ $proposal->currency }} {{ number_format($proposal->proposed_amount, 2) }}</td>
                                            <td>
                                                <span class="badge badge-{{ $proposal->getStatusBadgeColor() }}">
                                                    {{ ucfirst(str_replace('_', ' ', $proposal->status)) }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($proposal->sent_at)
                                                    {{ $proposal->sent_at->format('d M Y') }}<br>
                                                    <small class="text-muted">{{ $proposal->sent_at->diffForHumans() }}</small>
                                                @else
                                                    <span class="text-muted">Not sent</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                        Actions
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item" href="{{ route('proposals.show', $proposal->id) }}">
                                                            <i class="flaticon-381-view me-2"></i> View
                                                        </a>
                                                        @if($proposal->status === 'draft')
                                                            <a class="dropdown-item" href="{{ route('proposals.edit', $proposal->id) }}">
                                                                <i class="flaticon-381-edit me-2"></i> Edit
                                                            </a>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-center mt-4">
                            {{ $proposals->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
