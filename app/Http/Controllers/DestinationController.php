<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Destination;
use App\Models\TourPackage;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DestinationController extends Controller
{
    public function index(Request $request): View
    {
        $query = Destination::where('is_active', true);

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('state', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $destinations = $query->paginate(9)->withQueryString();
        $countries = Country::where('is_active', true)->get();

        return view('pages.destinations.index', compact('destinations', 'countries'));
    }

    public function show(string $slug): View
    {
        $destination = Destination::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $packages = TourPackage::where('destination_id', $destination->id)
            ->where('is_active', true)
            ->where('status', 'published')
            ->get();

        $relatedDestinations = Destination::where('type', $destination->type)
            ->where('id', '!=', $destination->id)
            ->where('is_active', true)
            ->take(3)
            ->get();

        return view('pages.destinations.show', compact('destination', 'packages', 'relatedDestinations'));
    }
}
