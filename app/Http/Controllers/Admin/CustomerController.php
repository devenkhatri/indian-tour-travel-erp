<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\CustomerPassenger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CustomerController extends Controller
{
    public function index(Request $request): View
    {
        $query = Customer::withCount(['bookings', 'leads', 'invoices']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('customer_code', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%");
            });
        }

        $customers = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        return view('admin.customers.index', compact('customers'));
    }

    public function create(): View
    {
        return view('admin.customers.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'phone' => 'required|string|max:25|unique:customers',
            'email' => 'nullable|email|max:150|unique:customers',
            'alternate_phone' => 'nullable|string|max:25',
            'passport_number' => 'nullable|string|max:50',
            'passport_expiry' => 'nullable|date',
            'dob' => 'nullable|date',
            'gender' => 'nullable|string|in:Male,Female,Other',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'pincode' => 'nullable|string|max:20',
            'gstin' => 'nullable|string|max:30',
            'status' => 'required|string|in:active,inactive,blacklisted',
            'notes' => 'nullable|string',
        ]);

        $customerCode = 'CUST-'.date('Y').'-'.rand(1000, 9999);

        $customer = Customer::create(array_merge($validated, [
            'customer_code' => $customerCode,
            'created_by' => Auth::id(),
        ]));

        return redirect()->route('admin.customers.show', $customer->id)
            ->with('success', "Customer #{$customerCode} created successfully.");
    }

    public function show(int $id): View
    {
        $customer = Customer::with([
            'bookings.package',
            'passengers',
            'documents',
            'invoices.tax',
            'payments',
            'leads',
            'quotations',
        ])->findOrFail($id);

        return view('admin.customers.show', compact('customer'));
    }

    public function edit(int $id): View
    {
        $customer = Customer::findOrFail($id);

        return view('admin.customers.edit', compact('customer'));
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $customer = Customer::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'phone' => 'required|string|max:25|unique:customers,phone,'.$id,
            'email' => 'nullable|email|max:150|unique:customers,email,'.$id,
            'alternate_phone' => 'nullable|string|max:25',
            'passport_number' => 'nullable|string|max:50',
            'passport_expiry' => 'nullable|date',
            'dob' => 'nullable|date',
            'gender' => 'nullable|string|in:Male,Female,Other',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'pincode' => 'nullable|string|max:20',
            'gstin' => 'nullable|string|max:30',
            'status' => 'required|string|in:active,inactive,blacklisted',
            'notes' => 'nullable|string',
        ]);

        $customer->update($validated);

        return redirect()->route('admin.customers.show', $customer->id)
            ->with('success', 'Customer profile updated successfully.');
    }

    public function addPassenger(Request $request, int $id): RedirectResponse
    {
        $customer = Customer::findOrFail($id);

        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'gender' => 'required|string|in:Male,Female,Other',
            'dob' => 'nullable|date',
            'relationship' => 'required|string|max:50',
            'passport_number' => 'nullable|string|max:50',
            'passport_expiry' => 'nullable|date',
        ]);

        CustomerPassenger::create(array_merge($validated, ['customer_id' => $customer->id]));

        return back()->with('success', 'Passenger profile added for customer.');
    }
}
