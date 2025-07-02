@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-5">
        <h1 class="text-2xl font-bold text-gray-800">Mes notifications</h1>
        <form method="POST" action="{{ route('notifications.markAllRead') }}">
            @csrf
            <button type="submit" class="btn btn-outline-secondary">
                <i class="fas fa-check-circle me-2"></i> Marquer tout comme lu
            </button>
        </form>
    </div>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        @if($notifications->count() > 0)
            <ul class="divide-y divide-gray-100">
                @foreach($notifications as $notification)
                <li class="p-5 hover:bg-gray-50 transition">
                    <div class="d-flex">
                        <div class="flex-shrink-0 me-4">
                            <div class="rounded-circle bg-{{ $notification->data['color'] ?? 'primary' }}-100 p-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                <i class="{{ $notification->data['icon'] ?? 'fas fa-bell' }} text-{{ $notification->data['color'] ?? 'primary' }}-600 fs-5"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between mb-2">
                                <h5 class="font-medium text-gray-800">{{ $notification->data['message'] ?? 'Notification' }}</h5>
                                <small class="text-muted">{{ $notification->created_at->format('d/m/Y H:i') }}</small>
                            </div>
                            <div class="d-flex">
                                @if(!$notification->read_at)
                                <form method="POST" action="{{ route('notifications.markRead', $notification->id) }}" class="me-2">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-secondary">
                                        Marquer comme lu
                                    </button>
                                </form>
                                @endif
                                @if(isset($notification->data['link']))
                                <a href="{{ $notification->data['link'] }}" class="btn btn-sm btn-{{ $notification->data['color'] ?? 'primary' }}">
                                    Voir
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </li>
                @endforeach
            </ul>
            <div class="p-4">
                {{ $notifications->links() }}
            </div>
        @else
            <div class="text-center py-10">
                <i class="far fa-bell-slash fs-1 text-muted mb-3"></i>
                <p class="text-muted fs-5">Aucune notification</p>
            </div>
        @endif
    </div>
</div>
@endsection