<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class BlacklistController extends Controller
{
    public function index()
    {
        $customers = User::where('role', 'customer')
            ->latest()
            ->paginate(10);

        return view('admin.blacklist', [ // Diubah dari 'admin.blacklist.index' ke 'admin.blacklist'
            'customers' => $customers,
            'title' => 'Customer Blacklist'
        ]);
    }

    public function toggleBlacklist(User $user)
    {
        if ($user->role !== 'customer') {
            return back()->with('error', 'Only customers can be blacklisted!');
        }

        $user->update([
            'is_blacklisted' => !$user->is_blacklisted,
            'blacklisted_at' => $user->is_blacklisted ? null : now()
        ]);

        $action = $user->is_blacklisted ? 'blacklisted' : 'removed from blacklist';
        return back()->with('success', "Customer {$user->name} has been {$action}!");
    }

    public function destroy(User $user)
    {
        if ($user->role !== 'customer') {
            return back()->with('error', 'Only customers can be deleted!');
        }

        $user->delete();
        return back()->with('success', 'Customer deleted successfully!');
    }
}