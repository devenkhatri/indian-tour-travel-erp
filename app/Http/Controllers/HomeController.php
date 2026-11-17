<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Blog;
use App\Models\Destination;
use App\Models\Faq;
use App\Models\PackageCategory;
use App\Models\Subscriber;
use App\Models\Testimonial;
use App\Models\TourPackage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $banners = Banner::where('is_active', true)->orderBy('sort_order')->get();
        $categories = PackageCategory::where('is_active', true)->get();
        $popularDestinations = Destination::where('is_active', true)->where('is_popular', true)->take(6)->get();
        $trendingDestinations = Destination::where('is_active', true)->where('is_trending', true)->take(4)->get();

        $featuredPackages = TourPackage::with(['destination', 'category'])
            ->where('is_active', true)
            ->where('status', 'published')
            ->where('is_featured', true)
            ->take(12)
            ->get();

        $domesticPackages = TourPackage::with('destination')
            ->where('is_active', true)
            ->where('status', 'published')
            ->where('type', 'domestic')
            ->take(4)
            ->get();

        $internationalPackages = TourPackage::with('destination')
            ->where('is_active', true)
            ->where('status', 'published')
            ->where('type', 'international')
            ->take(4)
            ->get();

        $testimonials = Testimonial::where('is_active', true)->where('is_featured', true)->take(4)->get();
        $latestBlogs = Blog::where('is_published', true)->orderBy('published_at', 'desc')->take(12)->get();
        $faqs = Faq::where('is_active', true)->orderBy('sort_order')->take(6)->get();

        return view('pages.home', compact(
            'banners',
            'categories',
            'popularDestinations',
            'trendingDestinations',
            'featuredPackages',
            'domesticPackages',
            'internationalPackages',
            'testimonials',
            'latestBlogs',
            'faqs'
        ));
    }

    public function about(): View
    {
        $testimonials = Testimonial::where('is_active', true)->take(4)->get();

        return view('pages.about', compact('testimonials'));
    }

    public function contact(): View
    {
        return view('pages.contact');
    }

    public function submitContact(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'email' => 'required|email|max:150',
            'phone' => 'required|string|max:25',
            'subject' => 'required|string|max:200',
            'message' => 'required|string',
        ]);

        return back()->with('success', 'Thank you for reaching out! Our travel advisory team will contact you within 2 hours.');
    }

    public function subscribeNewsletter(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => 'required|email|max:150',
        ]);

        Subscriber::firstOrCreate(
            ['email' => $validated['email']],
            ['subscribed_at' => now(), 'is_active' => true]
        );

        return response()->json([
            'success' => true,
            'message' => 'You have successfully subscribed to BharatTravel secret deals & discounts!',
        ]);
    }
}
