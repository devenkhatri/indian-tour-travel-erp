<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class SupplierController extends Controller
{
    public function index(Request $request): View
    {
        $query = Supplier::withCount('assignments');

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%")
                    ->orWhere('contact_person', 'like', "%{$search}%");
            });
        }

        $suppliers = $query->orderBy('name')->paginate(15)->withQueryString();

        return view('admin.suppliers.index', compact('suppliers'));
    }

    public function create(): View
    {
        return view('admin.suppliers.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'category' => 'required|string|in:airline,hotel,transport,cab,dmc,visa,insurance,cruise,other',
            'contact_person' => 'nullable|string|max:100',
            'email' => 'nullable|email|max:150',
            'phone' => 'nullable|string|max:25',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'gstin' => 'nullable|string|max:30',
            'pan' => 'nullable|string|max:20',
            'rating' => 'nullable|numeric|min:1|max:5',
            'notes' => 'nullable|string',
        ]);

        $code = 'SUP-'.strtoupper(substr($validated['category'], 0, 3)).'-'.strtoupper(Str::random(4));

        Supplier::create(array_merge($validated, [
            'code' => $code,
            'rating' => $validated['rating'] ?? 5.00,
            'is_active' => true,
        ]));

        return redirect()->route('admin.suppliers.index')->with('success', "Supplier '{$validated['name']}' created successfully.");
    }

    public function show(int $id): View
    {
        $supplier = Supplier::with(['assignments.trip.booking'])->findOrFail($id);

        return view('admin.suppliers.show', compact('supplier'));
    }

    public function edit(int $id): View
    {
        $supplier = Supplier::findOrFail($id);

        return view('admin.suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $supplier = Supplier::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'category' => 'required|string|in:airline,hotel,transport,cab,dmc,visa,insurance,cruise,other',
            'contact_person' => 'nullable|string|max:100',
            'email' => 'nullable|email|max:150',
            'phone' => 'nullable|string|max:25',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'gstin' => 'nullable|string|max:30',
            'pan' => 'nullable|string|max:20',
            'rating' => 'nullable|numeric|min:1|max:5',
            'notes' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $supplier->update(array_merge($validated, [
            'is_active' => $request->boolean('is_active', true),
        ]));

        return redirect()->route('admin.suppliers.index')->with('success', 'Supplier updated successfully.');
    }
}
