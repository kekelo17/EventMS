<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    public function index()
    {
        $users = User::withCount(['tickets','events'])->latest()->paginate(30);
        return view('admin.users.index', compact('users'));
    }

    public function show(User $user)
    {
        $user->load('tickets.event','events','payments','refundRequests');
        return view('admin.users.show', compact('user'));
    }

    public function toggleActive(User $user)
    {
        if ($user->role === 'admin') return back()->with('error', "Cannot disable admin account.");
        $user->update(['is_active' => !$user->is_active]);
        $status = $user->is_active ? 'activated' : 'deactivated';
        return back()->with('success', "User {$user->name} has been {$status}.");
    }
}
