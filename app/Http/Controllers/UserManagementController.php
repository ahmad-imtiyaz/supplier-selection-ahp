<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ActivityLog;
use App\Helpers\ActivityLogHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserManagementController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->paginate(10);
        return view('users.index', compact('users'));
    }

    /**
     * Display the specified user.
     */
    public function show(User $user)
    {
        // Load activities dengan pagination (5 per page untuk better UX)
        $activities = ActivityLog::byUser($user->id)
            ->with('user')
            ->latest()
            ->paginate(5); // ← Changed from 20 to 5

        // Get activity statistics
        $activityStats = ActivityLogHelper::getActivityStats($user->id);

        return view('users.show', compact('user', 'activities', 'activityStats'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        // Validation
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => ['nullable', 'confirmed', Password::min(8)],
            'role' => 'required|in:admin,user',
        ]);

        // Prevent user from changing their own role
        if ($user->id === Auth::id() && $request->role !== $user->role) {
            return redirect()->back()
                ->with('error', 'Anda tidak dapat mengubah role Anda sendiri.');
        }

        // Capture old values for activity log
        $oldValues = [
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
        ];

        // Update user data
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->role = $validated['role'];

        // Update password if provided
        if ($request->filled('password')) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        // Prepare new values for log
        $newValues = [
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
        ];

        // Log the update activity
        ActivityLogHelper::logUpdate(
            'User',
            $user->id,
            $user->name . ' (' . $user->email . ')',
            $oldValues,
            $newValues
        );

        return redirect()->route('users.show', $user)
            ->with('success', 'Data user berhasil diperbarui!');
    }

    /**
     * Update user role to admin.
     */
    public function promoteToAdmin(User $user)
    {
        // Cegah promote diri sendiri
        if ($user->id === Auth::id()) {
            return redirect()->back()->with('error', 'Anda tidak dapat mengubah role Anda sendiri.');
        }

        if ($user->role === 'admin') {
            return redirect()->back()->with('warning', 'User ini sudah menjadi Admin.');
        }

        // Capture old values
        $oldValues = [
            'name' => $user->name,
            'email' => $user->email,
            'role' => 'user',
        ];

        $user->update(['role' => 'admin']);

        // Log the promotion
        ActivityLogHelper::logUpdate(
            'User',
            $user->id,
            $user->name . ' (' . $user->email . ')',
            $oldValues,
            [
                'name' => $user->name,
                'email' => $user->email,
                'role' => 'admin',
            ]
        );

        return redirect()->back()->with('success', "User {$user->name} berhasil dipromosikan menjadi Admin.");
    }

    /**
     * Demote user from admin to user.
     */
    public function demoteToUser(User $user)
    {
        // Cegah demote diri sendiri
        if ($user->id === Auth::id()) {
            return redirect()->back()->with('error', 'Anda tidak dapat mengubah role Anda sendiri.');
        }

        if ($user->role === 'user') {
            return redirect()->back()->with('warning', 'User ini sudah memiliki role User.');
        }

        // Capture old values
        $oldValues = [
            'name' => $user->name,
            'email' => $user->email,
            'role' => 'admin',
        ];

        $user->update(['role' => 'user']);

        // Log the demotion
        ActivityLogHelper::logUpdate(
            'User',
            $user->id,
            $user->name . ' (' . $user->email . ')',
            $oldValues,
            [
                'name' => $user->name,
                'email' => $user->email,
                'role' => 'user',
            ]
        );

        return redirect()->back()->with('success', "Role {$user->name} berhasil diubah menjadi User.");
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        // Cegah hapus diri sendiri
        if ($user->id === Auth::id()) {
            return redirect()->back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $userName = $user->name;
        $userEmail = $user->email;

        // Log before deleting
        ActivityLogHelper::logDelete(
            'User',
            $user->id,
            $userName . ' (' . $userEmail . ')'
        );

        $user->delete();

        return redirect()->route('users.index')->with('success', "User {$userName} berhasil dihapus.");
    }
}
