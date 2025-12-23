@extends('admin.layouts.app')

@section('title', 'Leave Balances')

@section('content')
<div class="row page-titles">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.leaves.index') }}">Leaves</a></li>
        <li class="breadcrumb-item active"><a href="javascript:void(0)">Leave Balances</a></li>
    </ol>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Employee Leave Balances</h4>
                <a href="{{ route('admin.leaves.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fa fa-arrow-left me-2"></i>Back to Leave Applications
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
                                <th><strong>CASUAL LEAVE</strong></th>
                                <th><strong>SICK LEAVE</strong></th>
                                <th><strong>UNPAID LEAVE</strong></th>
                                <th><strong>ACTIONS</strong></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($balances as $balance)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $balance->bdm->name ?? 'N/A' }}</td>
                                    <td><strong>{{ $balance->bdm->employee_code ?? 'N/A' }}</strong></td>
                                    <td>
                                        <span class="badge badge-primary badge-lg">{{ $balance->casual_leave ?? 0 }}</span>
                                    </td>
                                    <td>
                                        <span class="badge badge-warning badge-lg">{{ $balance->sick_leave ?? 0 }}</span>
                                    </td>
                                    <td>
                                        <span class="badge badge-secondary badge-lg">{{ $balance->unpaid_leave ?? 0 }}</span>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#updateBalanceModal{{ $balance->id }}">
                                            <i class="fa fa-edit me-1"></i>Update
                                        </button>
                                    </td>
                                </tr>

                                <!-- Update Balance Modal -->
                                <div class="modal fade" id="updateBalanceModal{{ $balance->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="{{ route('admin.leaves.update-balance', $balance->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Update Leave Balance - {{ $balance->bdm->name }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-group mb-3">
                                                        <label>Casual Leave <span class="text-danger">*</span></label>
                                                        <input type="number" name="casual_leave" class="form-control" value="{{ $balance->casual_leave }}" min="0" required>
                                                    </div>
                                                    <div class="form-group mb-3">
                                                        <label>Sick Leave <span class="text-danger">*</span></label>
                                                        <input type="number" name="sick_leave" class="form-control" value="{{ $balance->sick_leave }}" min="0" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Remarks (Optional)</label>
                                                        <textarea name="remarks" class="form-control" rows="2" placeholder="Reason for update..."></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-primary">Update Balance</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">
                                        <i class="fa fa-inbox fa-3x mb-3 d-block"></i>
                                        No leave balance records found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
