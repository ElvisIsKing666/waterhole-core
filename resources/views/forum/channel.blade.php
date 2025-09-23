<x-waterhole::layout :title="$channel->name" :data-channel="$channel->slug">
    <x-slot name="head">
        <link
            rel="alternate"
            type="application/rss+xml"
            href="{{ route('waterhole.rss.channel', compact('channel')) }}"
        />

        @unless ($channel->structure->is_listed)
            <meta name="robots" content="noindex" />
        @endunless
    </x-slot>

    {{-- Custom channel layout with 3/4 width channels underneath headers --}}
    <div class="section container">
        <div class="channel-layout">
            {{-- Channel header section --}}
            <div class="channel-header">
                <x-waterhole::post-feed-channel :channel="$channel" />
            </div>
            
            {{-- Channel content at 3/4 width --}}
            <div class="channel-content">
                <x-waterhole::post-feed :feed="$feed" :channel="$channel" />
            </div>
        </div>
    </div>
</x-waterhole::layout>

<style>
/* Custom channel layout styles */
.channel-layout {
    width: 100%;
    max-width: none;
}

/* Channel header takes full width */
.channel-header {
    width: 100%;
    margin-bottom: var(--space-lg);
}

/* Channel content is 3/4 width and centered */
.channel-content {
    width: 75%;
    max-width: 75%;
    margin: 0 auto;
}

/* Responsive design */
@media (max-width: 768px) {
    .channel-content {
        width: 100%;
        max-width: 100%;
    }
}

/* Style the channel card in the header */
.channel-header .channel-card {
    width: 100%;
    margin-bottom: 0;
}

/* Ensure proper spacing */
.channel-header .channel-card__inner {
    width: 100%;
}

/* Style the post feed content */
.channel-content .post-feed {
    width: 100%;
}

/* Add some visual separation */
.channel-header {
    border-bottom: 1px solid var(--palette-stroke);
    padding-bottom: var(--space-lg);
    margin-bottom: var(--space-xl);
}
</style>
