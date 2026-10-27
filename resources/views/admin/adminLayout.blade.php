<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin | Mahalakshmi')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:wght@500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/admin.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    @stack('styles')
</head>

<body class="admin-layout-page">
    <header class="admin-header">
        <nav class="admin-navbar" aria-label="Admin navigation">
            <a class="admin-brand" href="{{ route('admin') }}" aria-label="Mahalakshmi admin dashboard">
                <img src="{{ asset('images/logo_final.png') }}" alt="Mahalakshmi">
            </a>

            <div class="admin-nav-links">
                <a class="admin-nav-link" href="{{ route('admin') }}">
                    <svg class="admin-nav-icon" viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M3 13h8V3H3v10Zm0 8h8v-4H3v4Zm10 0h8V11h-8v10Zm0-18v4h8V3h-8Z" />
                        </svg>
                    <span>Dashboard</span>
                </a>
                <div class="admin-nav-dropdown">
                    <a class="admin-nav-link" href="{{ route('admin') }}">
                        <svg class="admin-nav-icon" viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M3 13h8V3H3v10Zm0 8h8v-4H3v4Zm10 0h8V11h-8v10Zm0-18v4h8V3h-8Z" />
                        </svg>
                        <span>Masters</span>
                        <svg class="admin-nav-chevron" viewBox="0 0 12 8" aria-hidden="true">
                            <path d="m1 1 5 5 5-5" fill="none" stroke="currentColor" stroke-width="1.5" />
                        </svg>
                    </a>
                    <div class="admin-dropdown-menu">
                        <a href="{{ route('admin.products.index') }}">
                            <svg class="admin-menu-icon" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M3 3h8v8H3V3Zm10 0h8v5h-8V3ZM13 10h8v11h-8V10ZM3 13h8v8H3v-8Z" />
                            </svg>
                            <span>Product</span>
                        </a>
                        <a href="{{ route('admin.categories.index') }}">
                            <svg class="admin-menu-icon" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M3 3h8v8H3V3Zm10 0h8v5h-8V3ZM13 10h8v11h-8V10ZM3 13h8v8H3v-8Z" />
                            </svg>
                            <span>Category</span>
                        </a>
                    </div>
                </div>

                <a class="admin-nav-link" href="{{ route('home') }}">
                    <svg class="admin-nav-icon" viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M3 4h2l2.2 11.1a2 2 0 0 0 2 1.6h8.9a2 2 0 0 0 1.9-1.4L22 8H6" />
                        <circle cx="10" cy="20" r="1.3" />
                        <circle cx="18" cy="20" r="1.3" />
                    </svg>
                    <span>Market Place</span>
                </a>
            </div>

            <details class="admin-profile-dropdown">
                <summary class="admin-profile-button" aria-label="Open profile menu">
                    <span class="admin-profile-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                    <span class="admin-profile-details">
                        <strong>{{ auth()->user()->name }}</strong>
                        <small>Administrator</small>
                    </span>
                    <svg class="admin-profile-chevron" viewBox="0 0 12 8" aria-hidden="true">
                        <path d="m1 1 5 5 5-5" fill="none" stroke="currentColor" stroke-width="1.5" />
                    </svg>
                </summary>
                <div class="admin-profile-menu">
                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button type="submit">
                            <svg class="admin-menu-icon" viewBox="0 0 20 20" aria-hidden="true">
                                <path d="M8 3H4v14h4M11 6l4 4-4 4m4-4H7" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" />
                            </svg>
                            Logout
                        </button>
                    </form>
                </div>
            </details>
        </nav>
    </header>

    @php($validationErrors = $errors->all())
    @if (session('status') || $validationErrors !== [])
        <div class="admin-toast-container" aria-live="polite" aria-relevant="additions text">
            @if (session('status'))
                <div class="admin-toast admin-toast--success" role="status">
                    <span class="admin-toast__icon" aria-hidden="true">✓</span>
                    <p class="admin-toast__message">{{ session('status') }}</p>
                    <button class="admin-toast__dismiss" type="button" aria-label="Dismiss notification" data-toast-dismiss>&times;</button>
                </div>
            @endif

            @foreach ($validationErrors as $validationError)
                <div class="admin-toast admin-toast--error" role="alert">
                    <span class="admin-toast__icon" aria-hidden="true">!</span>
                    <p class="admin-toast__message">{{ $validationError }}</p>
                    <button class="admin-toast__dismiss" type="button" aria-label="Dismiss notification" data-toast-dismiss>&times;</button>
                </div>
            @endforeach
        </div>
    @endif

    <main class="admin-layout-main">
        @yield('content')
    </main>

    <footer class="admin-footer">
        <p>&copy; {{ date('Y') }} Mahalakshmi. All rights reserved.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @stack('scripts')
    <script>
        const profileDropdown = document.querySelector('.admin-profile-dropdown');

        document.addEventListener('click', (event) => {
            if (profileDropdown?.open && !profileDropdown.contains(event.target)) {
                profileDropdown.open = false;
            }
        });

        const sweetAlertOptions = {
            confirmButtonColor: '#008f49',
            cancelButtonColor: '#66746b'
        };

        document.querySelectorAll('.admin-toast').forEach((toast) => {
            const dismissToast = () => {
                toast.classList.add('admin-toast--dismissed');
                window.setTimeout(() => toast.remove(), 200);
            };

            toast.querySelector('[data-toast-dismiss]')?.addEventListener('click', dismissToast);
            window.setTimeout(dismissToast, 5000);
        });

        document.addEventListener('submit', async (event) => {
            const form = event.target;

            if (!(form instanceof HTMLFormElement) || !form.matches('[data-swal-confirm]')) {
                return;
            }

            event.preventDefault();

            const confirmationMessage = form.dataset.swalConfirm;
            const isConfirmed = window.Swal
                ? (await Swal.fire({
                    ...sweetAlertOptions,
                    icon: 'warning',
                    title: 'Are you sure?',
                    text: confirmationMessage,
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it',
                    cancelButtonText: 'Cancel',
                    confirmButtonColor: '#a8263f',
                    reverseButtons: true
                })).isConfirmed
                : window.confirm(confirmationMessage);

            if (isConfirmed) {
                HTMLFormElement.prototype.submit.call(form);
            }
        });
    </script>
</body>

</html>
