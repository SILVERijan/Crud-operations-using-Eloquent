<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Silver CRUD</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
            --glass-bg: rgba(255, 255, 255, 0.7);
            --glass-border: rgba(255, 255, 255, 0.3);
        }
        
        body {
            background-color: #f8fafc;
            color: #1e293b;
        }

        .glass-nav {
            background: var(--glass-bg);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--glass-border);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .premium-card {
            background: white;
            border: 1px solid rgba(0,0,0,0.05);
            border-radius: 1.25rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .hover-lift:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .text-gradient {
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .btn-primary {
            background: var(--primary-gradient);
            border: none;
            transition: opacity 0.2s;
        }

        .btn-primary:hover {
            opacity: 0.9;
        }

        .badge-category {
            background: rgba(99, 102, 241, 0.1);
            color: #6366f1;
            font-weight: 600;
            border-radius: 0.5rem;
            padding: 0.4em 0.8em;
        }

        .post-content {
            line-height: 1.8;
            font-size: 1.1rem;
        }

        .post-content h2, .post-content h3 {
            margin-top: 2rem;
            margin-bottom: 1rem;
            font-weight: 700;
        }

        .article-container {
            max-width: 900px;
            margin: 0 auto;
        }
    </style>
    @stack('styles')
    <script src="{{ mix('js/app.js') }}" defer></script>
</head>
<body>
    <nav class="navbar navbar-expand navbar-light glass-nav mb-5 py-3 shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold text-primary" href="{{ route('posts.index') }}" style="letter-spacing: -0.5px;">
                SILVER <span class="text-dark">CRUD</span>
            </a>
            <div class="ms-auto">
                <ul class="navbar-nav gap-3 align-items-center">
                    @guest
                        <li class="nav-item">
                            <a class="nav-link fw-medium" href="{{ route('login') }}">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link btn btn-primary text-white px-4 ms-lg-2" href="{{ route('register') }}">Register</a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link fw-medium {{ request()->routeIs('posts.index') ? 'text-primary' : '' }}" href="{{ route('posts.index') }}">Posts</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link fw-medium {{ request()->routeIs('posts.my') ? 'text-primary' : '' }}" href="{{ route('posts.my') }}">My Posts</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link fw-medium {{ request()->routeIs('posts.liked') ? 'text-primary' : '' }}" href="{{ route('posts.liked') }}">Liked</a>
                        </li>
                        <li class="nav-item">
                        @if(auth()->check() && auth()->user()->isAdmin())
                            <li class="nav-item">
                                <a class="nav-link fw-medium {{ request()->routeIs('admin.*') ? 'text-primary' : '' }}" href="{{ route('admin.dashboard') }}">
                                    <i class="bi bi-shield-lock"></i> Admin Panel
                                </a>
                            </li>
                        @endif
                        {{-- Notifications Dropdown --}}
                        <li class="nav-item dropdown">
                            <a class="nav-link position-relative" href="#" id="notificationDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-bell-fill fs-5"></i>
                                @if(auth()->user()->unreadNotifications->count() > 0)
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem;">
                                        {{ auth()->user()->unreadNotifications->count() }}
                                    </span>
                                @endif
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg mt-2 p-0 overflow-hidden" aria-labelledby="notificationDropdown" style="width: 320px;">
                                <div class="p-3 border-bottom d-flex justify-content-between align-items-center bg-light">
                                    <h6 class="mb-0 fw-bold">Notifications</h6>
                                    @if(auth()->user()->unreadNotifications->count() > 0)
                                        <form action="{{ route('notifications.markAllRead') }}" method="POST" id="mark-all-read-form">
                                            @csrf
                                            <small class="text-primary cursor-pointer" onclick="document.getElementById('mark-all-read-form').submit()">Mark all as read</small>
                                        </form>
                                    @endif
                                </div>
                                <div style="max-height: 350px; overflow-y: auto;">
                                    @forelse(auth()->user()->notifications->take(10) as $notification)
                                        <div class="p-3 border-bottom transition-all dropdown-item position-relative {{ $notification->read_at ? 'opacity-50' : 'bg-light fw-bold' }}" style="white-space: normal;">
                                            <div class="d-flex justify-content-between align-items-start mb-1">
                                                <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                                @unless($notification->read_at)
                                                    <form action="{{ route('notifications.read', $notification->id) }}" method="POST" class="position-relative" style="z-index: 10;">
                                                        @csrf
                                                        <button type="submit" class="btn btn-link p-0 text-primary" title="Mark as read">
                                                            <i class="bi bi-check2-circle"></i>
                                                        </button>
                                                    </form>
                                                @endunless
                                            </div>
                                            <div class="small">{{ $notification->data['message'] ?? 'New notification' }}</div>
                                            @if(isset($notification->data['url']))
                                                <a href="{{ $notification->data['url'] }}" class="stretched-link"></a>
                                            @endif
                                        </div>
                                    @empty
                                        <div class="p-4 text-center text-muted">
                                            <i class="bi bi-bell-slash fs-2 d-block mb-2"></i>
                                            <small>No notifications yet</small>
                                        </div>
                                    @endforelse
                                </div>
                                @if(auth()->user()->notifications->count() > 0)
                                    <div class="p-2 text-center border-top">
                                        <a href="{{ route('notifications.index') }}" class="small text-decoration-none">View all</a>
                                    </div>
                                @endif
                            </ul>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link fw-medium {{ request()->routeIs('categories.*') ? 'text-primary' : '' }}" href="{{ route('categories.index') }}">Categories</a>
                        </li>
                        <li class="nav-item dropdown ms-lg-2">
                            <a class="nav-link dropdown-toggle fw-semibold text-dark bg-light rounded-pill px-3" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-person-circle me-1"></i> {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg mt-2" aria-labelledby="navbarDropdown">
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item py-2 text-danger">
                                            <i class="bi bi-box-arrow-right me-2"></i> Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                <i class="bi bi-exclamation-octagon-fill me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                <div class="d-flex">
                    <i class="bi bi-exclamation-triangle-fill me-2 mt-1"></i>
                    <ul class="mb-0 ps-0" style="list-style: none;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        // Auto-dismiss alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                let alerts = document.querySelectorAll('.alert');
                alerts.forEach(function(alert) {
                    let bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);
        });
    </script>
    @stack('scripts')
</body>
</html>

    