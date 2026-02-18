@extends('layout')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold"><i class="bi bi-bell me-2"></i> Notifications</h2>
            @if(auth()->user()->unreadNotifications->count() > 0)
                <form action="{{ route('notifications.markAllRead') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-outline-primary btn-sm rounded-pill">
                        <i class="bi bi-check-all me-1"></i> Mark all as read
                    </button>
                </form>
            @endif
        </div>

        <div class="premium-card shadow-sm border-0 overflow-hidden">
            <div class="list-group list-group-flush">
                @forelse($notifications as $notification)
                    <div class="list-group-item p-4 transition-all {{ $notification->read_at ? 'opacity-75' : 'bg-light border-start border-4 border-primary' }}">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="mb-1 {{ $notification->read_at ? 'text-muted' : 'fw-bold' }}">
                                    {{ $notification->data['title'] ?? 'Notification' }}
                                </h6>
                                <p class="mb-2 text-secondary small">{{ $notification->data['message'] }}</p>
                                <small class="text-muted d-block mb-3">
                                    <i class="bi bi-clock me-1"></i> {{ $notification->created_at->diffForHumans() }}
                                </small>
                                
                                <div class="d-flex gap-2">
                                    @if(isset($notification->data['url']))
                                        <a href="{{ $notification->data['url'] }}" class="btn btn-sm btn-primary rounded-pill px-3">
                                            View Details
                                        </a>
                                    @endif
                                    
                                    @unless($notification->read_at)
                                        <form action="{{ route('notifications.read', $notification->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                                                Mark as read
                                            </button>
                                        </form>
                                    @endunless
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-5 text-center text-muted">
                        <i class="bi bi-bell-slash fs-1 d-block mb-3 opacity-25"></i>
                        <p class="mb-0">You have no notifications yet.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <div class="mt-4 d-flex justify-content-center">
            {{ $notifications->links() }}
        </div>
    </div>
</div>
@endsection
