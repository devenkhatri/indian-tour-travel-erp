<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Blog;
use App\Models\Faq;
use App\Models\Testimonial;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CmsController extends Controller
{
    public function index(): View
    {
        $banners = Banner::orderBy('sort_order')->get();
        $testimonials = Testimonial::all();
        $blogs = Blog::orderBy('published_at', 'desc')->get();
        $faqs = Faq::orderBy('sort_order')->get();

        return view('admin.cms.index', compact('banners', 'testimonials', 'blogs', 'faqs'));
    }

    public function storeBanner(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:200',
            'subtitle' => 'nullable|string|max:255',
            'button_text' => 'nullable|string|max:50',
            'button_url' => 'nullable|string',
            'image' => 'required|string',
            'sort_order' => 'nullable|integer',
        ]);

        Banner::create(array_merge($validated, ['is_active' => true]));

        return back()->with('success', 'Banner created successfully.');
    }

    public function storeTestimonial(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'designation_or_city' => 'nullable|string|max:100',
            'rating' => 'required|integer|min:1|max:5',
            'content' => 'required|string',
            'avatar' => 'nullable|string',
            'destination_visited' => 'nullable|string|max:100',
        ]);

        Testimonial::create(array_merge($validated, [
            'is_featured' => true,
            'is_active' => true,
        ]));

        return back()->with('success', 'Customer testimonial added.');
    }

    public function storeBlog(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'image' => 'nullable|string',
            'summary' => 'required|string',
            'content' => 'required|string',
            'author' => 'nullable|string|max:100',
        ]);

        $slug = Str::slug($validated['title']).'-'.strtolower(Str::random(4));

        Blog::create(array_merge($validated, [
            'slug' => $slug,
            'author' => $validated['author'] ?? 'BharatTravel Editorial Desk',
            'published_at' => now()->toDateString(),
            'is_published' => true,
        ]));

        return back()->with('success', 'Blog article published successfully.');
    }

    public function storeFaq(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'category' => 'required|string|max:50',
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
        ]);

        Faq::create(array_merge($validated, [
            'is_active' => true,
            'sort_order' => Faq::count() + 1,
        ]));

        return back()->with('success', 'FAQ added.');
    }
}
