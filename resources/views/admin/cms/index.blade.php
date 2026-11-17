@extends('layouts.admin')

@section('title', 'Website Content Management (CMS) - BharatTravel CRM')

@section('content')
<div class="space-y-6" x-data="{ activeTab: 'banners' }">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Website Content Management (CMS)</h1>
        <p class="text-sm text-gray-500">Manage hero promotional banners, customer reviews, travel blogs, and FAQs</p>
    </div>

    <!-- Navigation Tabs -->
    <div class="border-b border-gray-200">
        <nav class="flex space-x-8" aria-label="Tabs">
            <button @click="activeTab = 'banners'" :class="activeTab === 'banners' ? 'border-orange-500 text-orange-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="whitespace-nowrap py-3 px-1 border-b-2 font-medium text-sm flex items-center gap-2">
                <i class="fas fa-images"></i> Hero Banners ({{ $banners->count() }})
            </button>
            <button @click="activeTab = 'testimonials'" :class="activeTab === 'testimonials' ? 'border-orange-500 text-orange-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="whitespace-nowrap py-3 px-1 border-b-2 font-medium text-sm flex items-center gap-2">
                <i class="fas fa-quote-left"></i> Testimonials ({{ $testimonials->count() }})
            </button>
            <button @click="activeTab = 'blogs'" :class="activeTab === 'blogs' ? 'border-orange-500 text-orange-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="whitespace-nowrap py-3 px-1 border-b-2 font-medium text-sm flex items-center gap-2">
                <i class="fas fa-newspaper"></i> Blog Articles ({{ $blogs->count() }})
            </button>
            <button @click="activeTab = 'faqs'" :class="activeTab === 'faqs' ? 'border-orange-500 text-orange-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="whitespace-nowrap py-3 px-1 border-b-2 font-medium text-sm flex items-center gap-2">
                <i class="fas fa-question-circle"></i> FAQs ({{ $faqs->count() }})
            </button>
        </nav>
    </div>

    <!-- TAB 1: BANNERS -->
    <div x-show="activeTab === 'banners'" class="space-y-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Add New Banner -->
            <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm space-y-4">
                <h3 class="font-bold text-gray-900 text-base flex items-center gap-2">
                    <i class="fas fa-plus-circle text-orange-500"></i> Add Hero Banner
                </h3>
                <form action="{{ route('admin.cms.banner.store') }}" method="POST" class="space-y-3">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Headline Title *</label>
                        <input type="text" name="title" required placeholder="e.g. Discover Enchanting Kashmir" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Subtitle / Tagline</label>
                        <input type="text" name="subtitle" placeholder="e.g. 5 Nights Dal Lake Houseboats & Gulmarg Gondola" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Image URL *</label>
                        <input type="url" name="image" required placeholder="https://images.unsplash.com/..." class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2">
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Button Text</label>
                            <input type="text" name="button_text" value="Explore Now" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Sort Order</label>
                            <input type="number" name="sort_order" value="1" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Button Link URL</label>
                        <input type="text" name="button_url" value="/packages" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2">
                    </div>
                    <button type="submit" class="w-full py-2.5 bg-orange-600 hover:bg-orange-700 text-white font-semibold rounded-lg text-sm transition-colors">
                        Save Banner
                    </button>
                </form>
            </div>

            <!-- Existing Banners List -->
            <div class="lg:col-span-2 space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @forelse($banners as $b)
                        <div class="bg-white rounded-xl border border-gray-100 overflow-hidden shadow-sm flex flex-col justify-between">
                            <div class="h-40 bg-cover bg-center relative" style="background-image: url('{{ $b->image }}')">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/30 to-transparent"></div>
                                <div class="absolute bottom-3 left-3 right-3 text-white">
                                    <span class="text-[10px] uppercase font-bold tracking-wider px-2 py-0.5 bg-orange-600 rounded">Order: {{ $b->sort_order }}</span>
                                    <h4 class="font-bold text-sm mt-1 leading-snug">{{ $b->title }}</h4>
                                    @if($b->subtitle)
                                        <p class="text-xs text-gray-200 line-clamp-1">{{ $b->subtitle }}</p>
                                    @endif
                                </div>
                            </div>
                            <div class="p-3 bg-gray-50 flex items-center justify-between text-xs text-gray-500">
                                <span>Link: {{ $b->button_url }}</span>
                                <span class="text-emerald-600 font-semibold"><i class="fas fa-check-circle"></i> Active</span>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-2 text-center py-12 text-gray-500 bg-white rounded-xl border border-gray-100">
                            No banners currently configured.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- TAB 2: TESTIMONIALS -->
    <div x-show="activeTab === 'testimonials'" class="space-y-6" style="display: none;">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Add Testimonial -->
            <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm space-y-4">
                <h3 class="font-bold text-gray-900 text-base flex items-center gap-2">
                    <i class="fas fa-plus-circle text-orange-500"></i> Add Customer Review
                </h3>
                <form action="{{ route('admin.cms.testimonial.store') }}" method="POST" class="space-y-3">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Customer Name *</label>
                        <input type="text" name="name" required placeholder="e.g. Dr. Ramesh Gupta" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">City / Designation</label>
                        <input type="text" name="designation_or_city" placeholder="e.g. Mumbai, Maharashtra" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2">
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Star Rating (1-5)</label>
                            <select name="rating" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2">
                                <option value="5">⭐⭐⭐⭐⭐ (5 Stars)</option>
                                <option value="4">⭐⭐⭐⭐ (4 Stars)</option>
                                <option value="3">⭐⭐⭐ (3 Stars)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Destination Visited</label>
                            <input type="text" name="destination_visited" placeholder="e.g. Kerala Backwaters" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Review Content *</label>
                        <textarea name="content" rows="3" required placeholder="Write customer feedback..." class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2"></textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Avatar Image URL (Optional)</label>
                        <input type="url" name="avatar" placeholder="https://images.unsplash.com/..." class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2">
                    </div>
                    <button type="submit" class="w-full py-2.5 bg-orange-600 hover:bg-orange-700 text-white font-semibold rounded-lg text-sm transition-colors">
                        Post Testimonial
                    </button>
                </form>
            </div>

            <!-- Existing Testimonials -->
            <div class="lg:col-span-2">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @forelse($testimonials as $t)
                        <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm flex flex-col justify-between">
                            <div>
                                <div class="flex items-center gap-1 text-amber-400 text-xs mb-2">
                                    @for($i = 0; $i < $t->rating; $i++)
                                        <i class="fas fa-star"></i>
                                    @endfor
                                </div>
                                <p class="text-xs text-gray-700 italic">"{{ $t->content }}"</p>
                            </div>
                            <div class="mt-4 pt-3 border-t border-gray-100 flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center font-bold text-xs flex-shrink-0">
                                    {{ substr($t->name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="text-xs font-bold text-gray-900">{{ $t->name }}</div>
                                    <div class="text-[11px] text-gray-500">{{ $t->designation_or_city ?? 'Traveller' }} @if($t->destination_visited) &bull; {{ $t->destination_visited }} @endif</div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-2 text-center py-12 text-gray-500 bg-white rounded-xl border border-gray-100">
                            No customer reviews added yet.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- TAB 3: BLOGS -->
    <div x-show="activeTab === 'blogs'" class="space-y-6" style="display: none;">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Add Blog Article -->
            <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm space-y-4">
                <h3 class="font-bold text-gray-900 text-base flex items-center gap-2">
                    <i class="fas fa-plus-circle text-orange-500"></i> Publish Travel Article
                </h3>
                <form action="{{ route('admin.cms.blog.store') }}" method="POST" class="space-y-3">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Post Title *</label>
                        <input type="text" name="title" required placeholder="e.g. 10 Secret Cafes in Old Manali" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Category *</label>
                        <select name="category" required class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2">
                            <option value="Travel Guides">Travel Guides</option>
                            <option value="Visa Guides">Visa Guides</option>
                            <option value="Family Holidays">Family Holidays</option>
                            <option value="Budget Travel">Budget Travel</option>
                            <option value="Luxury Escapes">Luxury Escapes</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Cover Image URL</label>
                        <input type="url" name="image" placeholder="https://images.unsplash.com/..." class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Summary / Excerpt *</label>
                        <textarea name="summary" rows="2" required placeholder="Brief 1-2 sentence teaser..." class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2"></textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Full Article Body *</label>
                        <textarea name="content" rows="6" required placeholder="Full formatted text..." class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2"></textarea>
                    </div>
                    <button type="submit" class="w-full py-2.5 bg-orange-600 hover:bg-orange-700 text-white font-semibold rounded-lg text-sm transition-colors">
                        Publish Article
                    </button>
                </form>
            </div>

            <!-- Existing Blogs List -->
            <div class="lg:col-span-2 space-y-3">
                <div class="bg-white rounded-xl border border-gray-100 overflow-hidden shadow-sm">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
                            <tr>
                                <th class="px-5 py-3">Article</th>
                                <th class="px-5 py-3">Category</th>
                                <th class="px-5 py-3">Author</th>
                                <th class="px-5 py-3">Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($blogs as $blog)
                                <tr>
                                    <td class="px-5 py-3 font-semibold text-gray-900">
                                        <a href="{{ route('blogs.show', $blog->slug) }}" target="_blank" class="hover:text-orange-600 flex items-center gap-1.5">
                                            {{ $blog->title }} <i class="fas fa-external-link-alt text-[10px] text-gray-400"></i>
                                        </a>
                                    </td>
                                    <td class="px-5 py-3 text-xs">
                                        <span class="px-2 py-0.5 rounded bg-blue-50 text-blue-700 font-medium">{{ $blog->category }}</span>
                                    </td>
                                    <td class="px-5 py-3 text-xs text-gray-600">{{ $blog->author }}</td>
                                    <td class="px-5 py-3 text-xs text-gray-500">{{ $blog->published_at ? $blog->published_at->format('d M, Y') : 'Draft' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-5 py-8 text-center text-gray-500">No blog posts available.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- TAB 4: FAQS -->
    <div x-show="activeTab === 'faqs'" class="space-y-6" style="display: none;">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Add FAQ -->
            <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm space-y-4">
                <h3 class="font-bold text-gray-900 text-base flex items-center gap-2">
                    <i class="fas fa-plus-circle text-orange-500"></i> Add FAQ Question
                </h3>
                <form action="{{ route('admin.cms.faq.store') }}" method="POST" class="space-y-3">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Category *</label>
                        <select name="category" required class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2">
                            <option value="General">General</option>
                            <option value="Booking & Cancellation">Booking & Cancellation</option>
                            <option value="Visa & Passport">Visa & Passport</option>
                            <option value="Payments & GST">Payments & GST</option>
                            <option value="B2B Agent Portal">B2B Agent Portal</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Question *</label>
                        <input type="text" name="question" required placeholder="e.g. What is the GST rate applied on holiday packages?" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Answer *</label>
                        <textarea name="answer" rows="4" required placeholder="Enter clear explanation..." class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2"></textarea>
                    </div>
                    <button type="submit" class="w-full py-2.5 bg-orange-600 hover:bg-orange-700 text-white font-semibold rounded-lg text-sm transition-colors">
                        Save FAQ
                    </button>
                </form>
            </div>

            <!-- Existing FAQs List -->
            <div class="lg:col-span-2 space-y-3">
                @forelse($faqs as $f)
                    <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm">
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded bg-gray-100 text-gray-700">{{ $f->category }}</span>
                            <span class="text-xs text-emerald-600 font-semibold"><i class="fas fa-check-circle"></i> Active</span>
                        </div>
                        <h4 class="font-bold text-sm text-gray-900 mt-1">{{ $f->question }}</h4>
                        <p class="text-xs text-gray-600 mt-1.5 leading-relaxed">{{ $f->answer }}</p>
                    </div>
                @empty
                    <div class="text-center py-12 text-gray-500 bg-white rounded-xl border border-gray-100">
                        No FAQs currently listed.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

