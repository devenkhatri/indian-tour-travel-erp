<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use App\Models\Trip;
use App\Models\TripAssignment;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OperationsController extends Controller
{
    public function tripsIndex(Request $request): View
    {
        $query = Trip::with(['booking.customer', 'booking.package.destination', 'tourLeader']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('title', 'like', "%{$search}%")
                ->orWhere('trip_code', 'like', "%{$search}%");
        }

        $trips = $query->orderBy('start_date', 'asc')->paginate(15)->withQueryString();

        return view('admin.operations.index', compact('trips'));
    }

    public function tripShow(int $id): View
    {
        $trip = Trip::with(['booking.customer.passengers', 'booking.package.itineraries', 'tourLeader', 'assignments.supplier'])
            ->findOrFail($id);

        $suppliers = Supplier::where('is_active', true)->get();
        $staffMembers = User::whereIn('role', ['super_admin', 'admin', 'staff'])->get();

        return view('admin.operations.show', compact('trip', 'suppliers', 'staffMembers'));
    }

    public function updateTrip(Request $request, int $id): RedirectResponse
    {
        $trip = Trip::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|string|in:upcoming,ongoing,completed,delayed,cancelled',
            'tour_leader_id' => 'nullable|exists:users,id',
            'vehicle_info' => 'nullable|string|max:150',
            'driver_info' => 'nullable|string|max:150',
            'guide_info' => 'nullable|string|max:150',
            'special_instructions' => 'nullable|string',
        ]);

        $trip->update($validated);

        return back()->with('success', 'Trip operations data updated.');
    }

    public function assignSupplier(Request $request, int $id): RedirectResponse
    {
        $trip = Trip::findOrFail($id);

        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'type' => 'required|string|in:vehicle,driver,hotel,guide,transfer,activity',
            'details' => 'nullable|string',
            'cost' => 'required|numeric|min:0',
            'status' => 'required|string|in:assigned,confirmed,completed',
        ]);

        TripAssignment::create([
            'trip_id' => $trip->id,
            'supplier_id' => $validated['supplier_id'],
            'type' => $validated['type'],
            'details' => ['notes' => $validated['details'] ?? ''],
            'cost' => $validated['cost'],
            'status' => $validated['status'],
        ]);

        return back()->with('success', 'Supplier assignment recorded.');
    }
}
