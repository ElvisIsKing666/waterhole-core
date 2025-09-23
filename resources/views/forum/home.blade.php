<x-waterhole::layout>
    <x-slot name="head">
        <link rel="alternate" type="application/rss+xml" href="{{ route('waterhole.rss.posts') }}" />
    </x-slot>

    {{-- Custom full-width layout with IndexNav as main content --}}
    <div class="section container">
        <div class="index-nav-full-width">
            {{-- Display the IndexNav component as the main content --}}
            <x-waterhole::index-nav />
        </div>
    </div>
</x-waterhole::layout>

<style>
    /* Custom styles for full-width navbar layout */
    .index-nav-full-width {
        width: 100%;
        max-width: none;
    }

    /* Override the collapsible-nav to display as a full-width grid */
    .index-nav-full-width .collapsible-nav {
        display: block !important;
        /* Override the popup behavior */
    }

    .index-nav-full-width .drawer {
        display: block !important;
        /* Show the drawer content directly */
        position: static !important;
        transform: none !important;
        box-shadow: none !important;
        border: none !important;
        background: transparent !important;
        padding: 0 !important;
        margin: 0 !important;
        width: 100% !important;
        height: auto !important;
        max-height: none !important;
        overflow: visible !important;
    }

    /* Style the navigation as a vertical stack layout */
    .index-nav-full-width .nav {
        display: flex;
        flex-direction: column;
        gap: var(--space-sm);
        padding: var(--space-xl) 0;
    }

    /* Style nav headings - full width */
    .index-nav-full-width .nav-heading {
        width: 100%;
        text-align: left;
        font-weight: 600;
        color: var(--palette-text);
        margin: var(--space-lg) 0 var(--space-sm) 0;
        font-size: var(--text-lg);
        text-transform: none;
        letter-spacing: 0;
        border-bottom: 2px solid var(--palette-accent);
        padding: var(--space-sm) 0;
        background: var(--palette-fill-soft);
        padding-left: var(--space-md);
        border-radius: var(--radius-sm);
    }

    /* Style individual nav items - 3/4 width and indented */
    .index-nav-full-width .nav-link {
        display: flex;
        align-items: center;
        gap: var(--space-xs);
        padding: var(--space-md);
        background: var(--palette-surface);
        border: 1px solid var(--palette-stroke);
        border-radius: var(--radius-md);
        text-decoration: none;
        color: var(--palette-text);
        transition: all 0.2s ease;
        min-height: 3rem;
        font-weight: 500;
        width: 75%;
        margin-left: 12.5%;
        /* This creates the 3/4 width with left indentation */
        border-left: 4px solid var(--palette-accent-soft);
    }

    .index-nav-full-width .nav-link:hover {
        background: var(--palette-fill);
        border-color: var(--palette-accent);
        color: var(--palette-accent);
        transform: translateX(4px);
        /* Slide right on hover to show it's under a header */
        box-shadow: 0 4px 12px var(--palette-fill-soft);
    }

    .index-nav-full-width .nav-link.is-active {
        background: var(--palette-accent);
        color: var(--palette-accent-contrast);
        border-color: var(--palette-accent);
        transform: translateX(4px);
    }

    /* Style the icon in nav links */
    .index-nav-full-width .nav-link .icon {
        width: 1.25rem;
        height: 1.25rem;
        flex-shrink: 0;
    }

/* Style the label */
.index-nav-full-width .nav-link .label {
    flex: 1;
    text-align: left;
}

    /* Responsive design */
    @media (max-width: 768px) {
        .index-nav-full-width .nav-link {
            width: 90%;
            margin-left: 5%;
            /* Adjust indentation for mobile */
        }

        .index-nav-full-width .nav-heading {
            padding-left: var(--space-sm);
        }
    }

    /* Footer styling */
    .index-nav-full-width .index-footer {
        width: 100%;
        text-align: center;
        margin-top: var(--space-xl);
        padding-top: var(--space-lg);
        border-top: 1px solid var(--palette-stroke);
        color: var(--palette-muted);
        font-size: var(--text-sm);
    }

    /* Hide the popup button since we're showing the nav directly */
    .index-nav-full-width .btn {
        display: none !important;
    }
</style>