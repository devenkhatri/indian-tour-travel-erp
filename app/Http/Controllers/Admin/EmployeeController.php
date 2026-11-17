<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class EmployeeController extends Controller
{
    public function index(Request $request): View
    {
        $query = Employee::with(['user', 'branch', 'department']);

        if ($request->filled('branch_id')) {
            $query->where('branch_id', $request->branch_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('employee_code', 'like', "%{$search}%")
                    ->orWhere('designation', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($uq) use ($search) {
                        $uq->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        $employees = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();
        $branches = Branch::where('is_active', true)->get();

        return view('admin.employees.index', compact('employees', 'branches'));
    }

    public function create(): View
    {
        $branches = Branch::where('is_active', true)->get();
        $departments = Department::where('is_active', true)->get();
        $roles = Role::whereNotIn('slug', ['customer', 'agent'])->get();

        return view('admin.employees.create', compact('branches', 'departments', 'roles'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'email' => 'required|email|max:150|unique:users',
            'phone' => 'required|string|max:25',
            'password' => 'required|string|min:8',
            'role_slug' => 'required|string|exists:roles,slug',
            'branch_id' => 'nullable|exists:branches,id',
            'department_id' => 'nullable|exists:departments,id',
            'designation' => 'required|string|max:100',
            'sales_target' => 'nullable|numeric|min:0',
            'commission_rate' => 'nullable|numeric|min:0|max:100',
            'joining_date' => 'nullable|date',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'password' => Hash::make($validated['password']),
            'role' => 'staff',
            'status' => 'active',
        ]);

        $role = Role::where('slug', $validated['role_slug'])->first();
        if ($role) {
            $user->roles()->attach($role->id);
        }

        $empCode = 'EMP-'.str_pad($user->id, 3, '0', STR_PAD_LEFT);

        Employee::create([
            'user_id' => $user->id,
            'branch_id' => $validated['branch_id'] ?? null,
            'department_id' => $validated['department_id'] ?? null,
            'employee_code' => $empCode,
            'designation' => $validated['designation'],
            'sales_target' => $validated['sales_target'] ?? 0,
            'commission_rate' => $validated['commission_rate'] ?? 0,
            'joining_date' => $validated['joining_date'] ?? now()->toDateString(),
            'is_active' => true,
        ]);

        return redirect()->route('admin.employees.index')->with('success', "Staff member '{$validated['name']}' ({$empCode}) created successfully.");
    }
}
