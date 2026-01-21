@extends('admin.layouts.app')

@section('title', 'Manage Holidays')

@section('content')
<div class="container-fluid">
    <div class="row page-titles">
        <div class="col p-md-0">
            <h4>Manage Holidays</h4>
        </div>
        <div class="col p-md-0">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.attendance.dashboard') }}">Attendance</a></li>
                <li class="breadcrumb-item active">Holidays</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Add New Holiday</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.attendance.add-holiday') }}" method="POST" class="form-inline">
                        @csrf
                        <div class="form-group mr-2 mb-2">
                            <input type="date" name="holiday_date" class="form-control" placeholder="Date" required>
                        </div>
                        <div class="form-group mr-2 mb-2">
                            <input type="text" name="name" class="form-control" placeholder="Holiday Name" required>
                        </div>
                        <div class="form-group mr-2 mb-2">
                            <input type="text" name="description" class="form-control" placeholder="Description (Optional)">
                        </div>
                        <button type="submit" class="btn btn-primary mb-2">
                            <i class="fa fa-plus"></i> Add Holiday
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Holiday List ({{ $holidays->count() }})</h4>
                </div>
                <div class="card-body">
                    @if($holidays->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Day</th>
                                        <th>Holiday Name</th>
                                        <th>Description</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($holidays as $holiday)
                                        <tr>
                                            <td><strong>{{ $holiday->holiday_date->format('d M, Y') }}</strong></td>
                                            <td>{{ $holiday->holiday_date->format('l') }}</td>
                                            <td>{{ $holiday->name }}</td>
                                            <td>{{ $holiday->description ?? '-' }}</td>
                                            <td>
                                                <form action="{{ route('admin.attendance.delete-holiday', $holiday) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this holiday?')">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info text-center">
                            <i class="fa fa-info-circle"></i> No holidays added yet. Add your first holiday above.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
