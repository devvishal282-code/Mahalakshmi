@extends('admin.adminLayout')

@section('title', 'Admin | Mahalakshmi')

@section('content')
    <section class="admin-dashboard" aria-labelledby="admin-dashboard-title">
        <div class="admin-dashboard-heading">
            <div>
                <p class="admin-dashboard-eyebrow">ADMIN WORKSPACE</p>
                <h1 id="admin-dashboard-title">Welcome, {{ auth()->user()->name }}</h1>
                <p class="admin-dashboard-description">Your Mahalakshmi store overview and quick links.</p>
            </div>
            <span class="admin-dashboard-status">
                <span aria-hidden="true"></span>
                Admin access active
            </span>
        </div>

        <div class="admin-dashboard-shortcuts" aria-label="Store shortcuts">
            <a class="admin-dashboard-card" href="{{ route('home') }}">
                <span class="admin-dashboard-card-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24">
                        <path d="M3 10.5 12 3l9 7.5v9a1.5 1.5 0 0 1-1.5 1.5h-15A1.5 1.5 0 0 1 3 19.5v-9Z" />
                        <path d="M9 21v-7h6v7" />
                    </svg>
                </span>
                <span class="admin-dashboard-card-copy">
                    <strong>Storefront preview</strong>
                    <small>Visit the public Mahalakshmi store.</small>
                </span>
                <span class="admin-dashboard-card-arrow" aria-hidden="true">→</span>
            </a>

            <a class="admin-dashboard-card" href="{{ route('collections.index') }}">
                <span class="admin-dashboard-card-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24">
                        <rect x="3.5" y="3.5" width="7" height="7" rx="1.5" />
                        <rect x="13.5" y="3.5" width="7" height="7" rx="1.5" />
                        <rect x="3.5" y="13.5" width="7" height="7" rx="1.5" />
                        <rect x="13.5" y="13.5" width="7" height="7" rx="1.5" />
                    </svg>
                </span>
                <span class="admin-dashboard-card-copy">
                    <strong>Collections</strong>
                    <small>Browse the collection pages currently on your store.</small>
                </span>
                <span class="admin-dashboard-card-arrow" aria-hidden="true">→</span>
            </a>
        </div>

        <section class="admin-dashboard-empty" aria-labelledby="admin-dashboard-empty-title">
            <span class="admin-dashboard-empty-icon" aria-hidden="true">
                <svg viewBox="0 0 48 48">
                    <rect x="8" y="10" width="32" height="28" rx="4" />
                    <path d="M8 19h32M18 10v9m12-9v9M17 27h5m5 0h5m-15 6h5" />
                </svg>
            </span>
            <div>
                <h2 id="admin-dashboard-empty-title">Your store activity will appear here</h2>
                <p>There are no product or order summaries to show yet. This dashboard is ready for store data and activity when those features are connected.</p>
            </div>
        </section>
    </section>
@endsection
