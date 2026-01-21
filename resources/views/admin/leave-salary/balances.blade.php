@extends('admin.layouts.app')

@section('title', 'Leave Balances')
@section('page-title', 'BDM Leave Balances')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">BDM Leave Balance Sheet</h4>
                </div>
                <div class="card-body">
                    @if($bdms->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>#</th>
                                        <th>BDM Name</th>
                                        <th>CL Allocated</th>
                                        <th>CL Balance</th>
                                        <th>CL Taken</th>
                                        <th>SL Allocated</th>
                                        <th>SL Balance</th>
                                        <th>SL Taken</th>
                                        <th>Total Taken</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($bdms as $index => $bdm)
                                    @php
                                        $balance = $bdm->leaveBalance ?? new \App\Models\BDMLeaveBalance();
                                        $clTaken = ($balance->casual_leave_allocated ?? 0) - ($balance->casual_leave_balance ?? 0);
                                        $slTaken = ($balance->sick_leave_allocated ?? 0) - ($balance->sick_leave_balance ?? 0);
                                    @endphp
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td><strong>{{ $bdm->name }}</strong></td>
                                        <td>{{ $balance->casual_leave_allocated ?? 0 }}</td>
                                        <td>
                                            <span class="badge badge-{{ $balance->casual_leave_balance > 0 ? 'success' : 'danger' }}">
                                                {{ $balance->casual_leave_balance ?? 0 }}
                                            </span>
                                        </td>
                                        <td>{{ $clTaken }}</td>
                                        <td>{{ $balance->sick_leave_allocated ?? 0 }}</td>
                                        <td>
                                            <span class="badge badge-{{ $balance->sick_leave_balance > 0 ? 'success' : 'danger' }}">
                                                {{ $balance->sick_leave_balance ?? 0 }}
                                            </span>
                                        </td>
                                        <td>{{ $slTaken }}</td>
                                        <td><strong>{{ $clTaken + $slTaken }}</strong></td>
                                        <td>
                                            <button 
                                                class="btn btn-primary btn-sm" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#allocateModal{{ $bdm->id }}"
                                            >
                                                <i class="fa fa-edit"></i> Set Allocation
                                            </button>
                                        </td>
                                    </tr>

                                    <!-- Allocate Leave Modal -->
                                    <div class="modal fade" id="allocateModal{{ $bdm->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form action="{{ route('admin.leave-salary.leaves.allocate', $bdm->id) }}" method="POST">
                                                    @csrf
                                                    <div class="modal-header bg-primary text-white">
                                                        <h5 class="modal-title">Set Leave Allocation</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p><strong>BDM:</strong> {{ $bdm->name }}</p>
                                                        <hr>
                                                        <div class="mb-3">
                                                            <label class="form-label">Casual Leaves (CL) <span class="text-danger">*</span></label>
                                                            <input 
                                                                type="number" 
                                                                name="casual_leaves" 
                                                                class="form-control" 
                                                                value="{{ $balance->casual_leave_allocated ?? 12 }}"
                                                                min="0" 
                                                                max="30"
                                                                required
                                                            >
                                                            <small class="text-muted">Total casual leaves to allocate for the year</small>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Sick Leaves (SL) <span class="text-danger">*</span></label>
                                                            <input 
                                                                type="number" 
                                                                name="sick_leaves" 
                                                                class="form-control" 
                                                                value="{{ $balance->sick_leave_allocated ?? 12 }}"
                                                                min="0" 
                                                                max="30"
                                                                required
                                                            >
                                                            <small class="text-muted">Total sick leaves to allocate for the year</small>
                                                        </div>
                                                        <div class="alert alert-warning">
                                                            <i class="fa fa-exclamation-triangle"></i> 
                                                            <strong>Note:</strong> This will reset the leave balance to the allocated values.
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-primary">
                                                            <i class="fa fa-save"></i> Update Allocation
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
                            <i class="fa fa-info-circle"></i> No active BDMs found.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-xl-12">
            <a href="{{ route('admin.leave-salary.leaves.index') }}" class="btn btn-secondary">
                <i class="fa fa-arrow-left"></i> Back to Leave Applications
            </a>
        </div>
    </div>
</div>
@endsection
