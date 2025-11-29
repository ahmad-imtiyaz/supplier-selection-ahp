<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        return view('users.show', compact('user'));
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

        $user->update(['role' => 'admin']);

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

        $user->update(['role' => 'user']);

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
        $user->delete();

        return redirect()->route('users.index')->with('success', "User {$userName} berhasil dihapus.");
    }
}
