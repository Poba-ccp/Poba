<?php
//  controllers/Admin/AdminUserController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\AdminCreated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%");
            });
        }

        $admins = $query->orderByDesc('created_at')->paginate(10);
        return view('admin.admin-users.index', compact('admins'));
    }

    public function create()
    {
        $allPermissions = User::allPermissions();
        return view('admin.admin-users.create', compact('allPermissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email',
            'gender'      => 'required|string',
            'role'        => 'required|in:superadmin,admin',
            'permissions' => 'nullable|array',
        ]);

        /** @var \App\Models\User $currentUser */
        $currentUser = Auth::user();

        if ($request->role === 'superadmin' && ! $currentUser->isSuperAdmin()) {
            return back()->with('error', 'Only Super Admins can create other Super Admins.');
        }

        $tempPassword = Str::random(12);

        $admin = User::create([
            'name'        => $request->name,
            'email'       => $request->email,
            'gender'      => $request->gender,
            'role'        => $request->role,
            'permissions' => $request->role === 'superadmin' ? null : ($request->permissions ?? []),
            'password'    => Hash::make($tempPassword),
        ]);

        // Send email with credentials
        $admin->notify(new AdminCreated($tempPassword));

        return redirect()->route('admin.admin-users.index')
            ->with('success', "Admin '{$admin->name}' created. Login credentials emailed to {$admin->email}.");
    }

    public function edit(int $id)
    {
        $admin          = User::findOrFail($id);
        $allPermissions = User::allPermissions();

        /** @var \App\Models\User $currentUser */
        $currentUser = Auth::user();

        if ($admin->isSuperAdmin() && ! $currentUser->isSuperAdmin()) {
            abort(403);
        }

        return view('admin.admin-users.create', compact('admin', 'allPermissions'));
    }

    public function update(Request $request, int $id)
    {
        $admin = User::findOrFail($id);

        $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email,' . $id,
            'gender'      => 'required|string',
            'role'        => 'required|in:superadmin,admin',
            'permissions' => 'nullable|array',
        ]);

        /** @var \App\Models\User $currentUser */
        $currentUser = Auth::user();

        if ($request->role === 'superadmin' && ! $currentUser->isSuperAdmin()) {
            return back()->with('error', 'Only Super Admins can grant Super Admin role.');
        }

        $data = [
            'name'        => $request->name,
            'email'       => $request->email,
            'gender'      => $request->gender,
            'role'        => $request->role,
            'permissions' => $request->role === 'superadmin' ? null : ($request->permissions ?? []),
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $admin->update($data);

        return redirect()->route('admin.admin-users.index')
            ->with('success', 'Admin user updated successfully.');
    }

    public function destroy(int $id)
    {
        $admin = User::findOrFail($id);

        if ($admin->id === Auth::id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        /** @var \App\Models\User $currentUser */
        $currentUser = Auth::user();

        if ($admin->isSuperAdmin() && ! $currentUser->isSuperAdmin()) {
            abort(403);
        }

        $admin->delete();
        return back()->with('success', 'Admin user deleted successfully.');
    }
}