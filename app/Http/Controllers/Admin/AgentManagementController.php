<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AgentManagementController extends Controller
{
    public function index(Request $request): View
    {
        $query = Agent::with('user')->withCount('bookings');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('agency_name', 'like', "%{$search}%")
                ->orWhere('agency_code', 'like', "%{$search}%")
                ->orWhere('owner_name', 'like', "%{$search}%")
                ->orWhere('city', 'like', "%{$search}%");
        }

        $agents = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        return view('admin.agents.index', compact('agents'));
    }

    public function show(int $id): View
    {
        $agent = Agent::with(['user', 'bookings.package', 'commissions.booking', 'payments'])->findOrFail($id);

        return view('admin.agents.show', compact('agent'));
    }

    public function updateStatus(Request $request, int $id): RedirectResponse
    {
        $agent = Agent::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|string|in:pending,approved,rejected,suspended',
            'credit_limit' => 'nullable|numeric|min:0',
            'commission_rate' => 'nullable|numeric|min:0|max:100',
            'notes' => 'nullable|string',
        ]);

        $agent->update([
            'status' => $validated['status'],
            'credit_limit' => $validated['credit_limit'] ?? $agent->credit_limit,
            'commission_rate' => $validated['commission_rate'] ?? $agent->commission_rate,
            'notes' => $validated['notes'] ?? $agent->notes,
            'approved_at' => $validated['status'] === 'approved' && ! $agent->approved_at ? now() : $agent->approved_at,
        ]);

        return back()->with('success', "Agent status updated to '{$validated['status']}'.");
    }
}
