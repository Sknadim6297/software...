@extends('layouts.app')

@section('title', 'Notifications')
@section('page-title', 'Notifications')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="fas fa-bell"></i> All Notifications</span>
        <form action="{{ route('bdm.notifications.read-all') }}" method="POST" style="display: inline;">
            @csrf
            <button type="submit" class="btn btn-sm btn-primary">
                <i class="fas fa-check-double"></i> Mark All as Read
            </button>
        </form>
    </div>
    <div class="card-body">
        @if($notifications->count() > 0)
            <div class="list-group">
                @foreach($notifications as $notification)
                    <div class="list-group-item {{ !$notification->is_read ? 'bg-light border-start border-primary border-3' : '' }}">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center mb-2">
                                    @if($notification->type === 'warning')
                                        <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                                    @elseif($notification->type === 'termination')
                                        <i class="fas fa-ban text-danger me-2"></i>
                                    @elseif($notification->type === 'target_failure')
                                        <i class="fas fa-times-circle text-danger me-2"></i>
                                    @elseif($notification->type === 'leave_status')
                                        <i class="fas fa-calendar-check text-info me-2"></i>
                                    @else
                                        <i class="fas fa-info-circle text-primary me-2"></i>
                                    @endif
                                    
                                    <h6 class="mb-0">{{ $notification->title }}</h6>
                                    
                                    @if(!$notification->is_read)
                                        <span class="badge bg-primary ms-2">New</span>
                                    @endif
                                </div>
                                
                                <p class="mb-1">{{ $notification->message }}</p>
                                
                                <small class="text-muted">
                                    <i class="fas fa-clock"></i> {{ $notification->created_at->diffForHumans() }}
                                </small>
                            </div>
                            
                            <div class="ms-3">
                                @if(!$notification->is_read)
                                    <form action="{{ route('bdm.notifications.read', $notification->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-check"></i> Mark Read
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="mt-3">
                {{ $notifications->links() }}
            </div>
        @else
            <div class="alert alert-info mb-0">
                <i class="fas fa-info-circle"></i> No notifications yet.
            </div>
        @endif
    </div>
</div>
@endsection
