@extends('layouts.app')

@section('title', 'Travel Guides & Destination Insights - BharatTravel Blog')

@section('content')
<section class="bg-gradient-to-r from-slate-900 to-slate-800 text-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-amber-500/20 text-amber-400 border border-amber-500/30 uppercase tracking-widest mb-4">
            Inspiration & Travel Intel
        </span>
        <h1 class="text-4xl md:text-5xl font-bold font-serif mb-4">The BharatTravel Journal</h1>
        <p class="text-slate-300 max-w-2xl mx-auto text-base md:text-lg">
            Destination breakdowns, seasonal travel hacks, visa guidelines, packing checklists, and cultural chronicles from our on-ground explorers.
        </p>
    </div>
</section>

<section class="py-12 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Filter Bar -->
        <div class="flex flex-col md:flex-row items-center justify-between gap-4 mb-10 pb-6 border-b border-slate-200">
            <div class="flex flex-wrap gap-2 items-center">
                <a href="{{ route('blogs.index') }}" 
                   class="px-4 py-2 rounded-xl text-xs font-bold transition {{ !request('category') ? 'bg-amber-500 text-white shadow-md' : 'bg-white text-slate-700 hover:bg-slate-100 border border-slate-200' }}">
                    All Articles
                </a>
                @foreach($categories as $cat)
                    <a href="{{ route('blogs.index', ['category' => $cat]) }}" 
                       class="px-4 py-2 rounded-xl text-xs font-bold transition {{ request('category') == $cat ? 'bg-amber-500 text-white shadow-md' : 'bg-white text-slate-700 hover:bg-slate-100 border border-slate-200' }}">
                        {{ ucfirst($cat) }}
                    </a>
                @endforeach
            </div>

            <form action="{{ route('blogs.index') }}" method="GET" class="w-full md:w-72">
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}"
                           class="w-full pl-9 pr-4 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-xs"
                           placeholder="Search guides, destinations...">
                    <i data-lucide="search" class="w-4 h-4 text-slate-400 absolute left-3 top-3"></i>
                </div>
            </form>
        </div>

        <!-- Blog Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($blogs as $blog)
                <article class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all border border-slate-200 flex flex-col justify-between group">
                    <div>
                        <div class="relative h-56 overflow-hidden">
                            <img src="{{ $blog->image ?: ($blog->featured_image ?: 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=800&q=80') }}" 
                                 alt="{{ $blog->title }}" 
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            <span class="absolute top-4 left-4 px-3 py-1 bg-black/60 backdrop-blur-md text-white text-[11px] font-bold rounded-full uppercase tracking-wider">
                                {{ $blog->category ?? 'Travel Guide' }}
                            </span>
                        </div>
                        <div class="p-6">
                            <div class="flex items-center gap-3 text-xs text-slate-400 mb-3">
                                <span>{{ $blog->published_at ? \Carbon\Carbon::parse($blog->published_at)->format('M d, Y') : 'Recent' }}</span>
                                <span>•</span>
                                <span>{{ $blog->author_name ?? 'Editorial Desk' }}</span>
                            </div>
                            <h2 class="text-xl font-bold font-serif text-slate-900 group-hover:text-amber-600 transition-colors mb-3 line-clamp-2">
                                <a href="{{ route('blogs.show', $blog->slug) }}">{{ $blog->title }}</a>
                            </h2>
                            <p class="text-slate-600 text-sm line-clamp-3 leading-relaxed mb-4">
                                {{ $blog->summary }}
                            </p>
                        </div>
                    </div>
                    <div class="px-6 pb-6 pt-2 border-t border-slate-100 flex items-center justify-between">
                        <span class="text-xs font-semibold text-slate-500 flex items-center gap-1">
                            <i data-lucide="eye" class="w-3.5 h-3.5"></i> {{ $blog->views_count ?? 120 }} views
                        </span>
                        <a href="{{ route('blogs.show', $blog->slug) }}" class="text-xs font-bold text-amber-600 hover:text-amber-700 inline-flex items-center">
                            Read Guide <i data-lucide="arrow-right" class="w-3.5 h-3.5 ml-1"></i>
                        </a>
                    </div>
                </article>
            @empty
                <div class="col-span-full text-center py-16 bg-white rounded-2xl border border-slate-200">
                    <i data-lucide="book-open" class="w-12 h-12 text-slate-300 mx-auto mb-3"></i>
                    <h3 class="text-lg font-bold text-slate-700">No blog articles found</h3>
                    <p class="text-slate-500 text-sm mt-1">Try another search keyword or category filter.</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-12">
            {{ $blogs->links() }}
        </div>
    </div>
</section>
@endsection

