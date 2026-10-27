<main class="store-info-page">
    <header class="store-info-heading">
        <p class="eyebrow">MAHALAKSHMI • THE ART OF LIVING BEAUTIFULLY</p>
        <h1>{{ $title }}</h1>
        <p>{{ $intro }}</p>
    </header>

    <div class="store-info-sections">
        @foreach ($sections as $section)
            <section class="store-info-section">
                <h2>{{ $section['heading'] }}</h2>
                <p>{{ $section['body'] }}</p>
            </section>
        @endforeach
    </div>

    @if (! empty($links))
        <nav class="store-info-actions {{ $contactActions ? 'contact-actions' : '' }}" aria-label="Contact options">
            @foreach ($links as $link)
                <a
                    class="{{ $contactActions ? 'contact-action contact-action--' . $link['type'] : '' }}"
                    href="{{ $link['href'] }}"
                >
                    @if (isset($link['icon']))
                        <span class="contact-action-icon" aria-hidden="true"><i class="{{ $link['icon'] }}"></i></span>
                    @endif
                    <span>{{ $link['label'] }}</span>
                </a>
            @endforeach
        </nav>
    @endif

    @if ($showNewsNote)
        <p class="store-info-note">For product details or availability, <a href="{{ route('pages.contact-us') }}">contact our team</a>.</p>
    @endif
</main>
