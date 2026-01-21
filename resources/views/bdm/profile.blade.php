@extends('layouts.app')

@section('title', 'My Profile')
@section('page-title', 'My Profile')

@section('content')
@if($bdm)
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-user"></i> Personal Information
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th width="30%">Name</th>
                        <td>{{ $bdm->name }}</td>
                    </tr>
                    <tr>
                        <th>Father's Name</th>
                        <td>{{ $bdm->father_name }}</td>
                    </tr>
                    <tr>
                        <th>Date of Birth</th>
                        <td>{{ $bdm->date_of_birth->format('F d, Y') }} ({{ $bdm->date_of_birth->age }} years)</td>
                    </tr>
                    <tr>
                        <th>Highest Education</th>
                        <td>{{ $bdm->highest_education }}</td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td>{{ $bdm->email }}</td>
                    </tr>
                    <tr>
                        <th>Phone</th>
                        <td>{{ $bdm->phone ?? 'Not provided' }}</td>
                    </tr>
                    <tr>
                        <th>Employee Code</th>
                        <td><strong>{{ $bdm->employee_code }}</strong></td>
                    </tr>
                    <tr>
                        <th>Joining Date</th>
                        <td>{{ $bdm->joining_date->format('F d, Y') }} ({{ $bdm->joining_date->diffForHumans() }})</td>
                    </tr>
                    <tr>
                        <th>Current CTC</th>
                        <td><strong>₹{{ number_format($bdm->current_ctc, 2) }}</strong> per annum</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>
                            @if($bdm->status === 'active')
                                <span class="badge bg-success">Active</span>
                            @elseif($bdm->status === 'warned')
                                <span class="badge bg-warning">Warned ({{ $bdm->warning_count }}/3)</span>
                            @else
                                <span class="badge bg-danger">Terminated</span>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-image"></i> Profile Image
            </div>
            <div class="card-body text-center">
                @if($bdm->profile_image)
                    <img src="{{ asset('storage/' . $bdm->profile_image) }}" alt="Profile" class="img-fluid rounded mb-3" style="max-width: 200px;">
                @else
                    <div class="bg-secondary rounded d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 200px; height: 200px;">
                        <i class="fas fa-user fa-5x text-white"></i>
                    </div>
                @endif
                
                <form action="{{ route('bdm.profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <input type="file" name="profile_image" class="form-control" accept="image/*">
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fas fa-upload"></i> Update Image
                    </button>
                </form>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header">
                <i class="fas fa-edit"></i> Update Phone
            </div>
            <div class="card-body">
                <form action="{{ route('bdm.profile.update') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Phone Number</label>
                        <input type="text" name="phone" class="form-control" value="{{ $bdm->phone }}" placeholder="Enter phone number">
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fas fa-save"></i> Update Phone
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@else
<div class="alert alert-warning">
    <i class="fas fa-exclamation-triangle"></i> BDM record not found. Please contact administrator.
</div>
@endif
@endsection
