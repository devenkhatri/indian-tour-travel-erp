<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\PassportApplication;
use App\Models\User;
use App\Models\VisaApplication;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VisaPassportController extends Controller
{
    public function visaIndex(Request $request): View
    {
        $query = VisaApplication::with(['country', 'customer', 'assignedEmployee']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('country_id')) {
            $query->where('country_id', $request->country_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('applicant_name', 'like', "%{$search}%")
                    ->orWhere('application_number', 'like', "%{$search}%")
                    ->orWhere('passport_number', 'like', "%{$search}%")
                    ->orWhere('contact_phone', 'like', "%{$search}%");
            });
        }

        $applications = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();
        $countries = Country::where('is_active', true)->get();

        return view('admin.visa.index', compact('applications', 'countries'));
    }

    public function visaShow(int $id): View
    {
        $application = VisaApplication::with(['country', 'customer', 'documents', 'assignedEmployee'])->findOrFail($id);
        $staffMembers = User::whereIn('role', ['super_admin', 'admin', 'staff'])->get();

        return view('admin.visa.show', compact('application', 'staffMembers'));
    }

    public function updateVisaStatus(Request $request, int $id): RedirectResponse
    {
        $application = VisaApplication::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|string',
            'appointment_date' => 'nullable|date',
            'submission_date' => 'nullable|date',
            'actual_date' => 'nullable|date',
            'fee' => 'nullable|numeric|min:0',
            'assigned_to' => 'nullable|exists:users,id',
            'remarks' => 'nullable|string',
        ]);

        $application->update($validated);

        return back()->with('success', 'Visa application updated successfully.');
    }

    public function passportIndex(Request $request): View
    {
        $query = PassportApplication::with(['customer', 'assignedEmployee']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('applicant_name', 'like', "%{$search}%")
                ->orWhere('application_number', 'like', "%{$search}%")
                ->orWhere('contact_phone', 'like', "%{$search}%");
        }

        $applications = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        return view('admin.passport.index', compact('applications'));
    }

    public function passportShow(int $id): View
    {
        $application = PassportApplication::with(['customer', 'assignedEmployee'])->findOrFail($id);
        $staffMembers = User::whereIn('role', ['super_admin', 'admin', 'staff'])->get();

        return view('admin.passport.show', compact('application', 'staffMembers'));
    }

    public function updatePassportStatus(Request $request, int $id): RedirectResponse
    {
        $application = PassportApplication::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|string',
            'appointment_date' => 'nullable|date',
            'appointment_location' => 'nullable|string|max:150',
            'fee' => 'nullable|numeric|min:0',
            'assigned_to' => 'nullable|exists:users,id',
            'remarks' => 'nullable|string',
        ]);

        $application->update($validated);

        return back()->with('success', 'Passport application details updated.');
    }
}
