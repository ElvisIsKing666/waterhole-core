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