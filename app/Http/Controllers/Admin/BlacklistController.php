<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Blacklist;
use Illuminate\Http\Request;

class BlacklistController extends Controller
{
    public function index()
    {
        $customers = User::where('role', 'customer')
            ->with('blacklist')
            ->paginate(10);

        return view('admin.blacklist', [
            'title' => 'Customer Blacklist',
            'customers' => $customers
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'reason' => 'nullable|string|max:255'
        ]);

        $user = User::findOrFail($validated['user_id']);

        if ($user->role !== 'customer') {
            return back()->with('error', 'Only customer accounts can be blacklisted.');
        }

        Blacklist::create([
            'user_id' => $user->id,
            'reason' => $validated['reason'],
            'banned_by' => auth()->id()
        ]);

        return redirect()->route('admin.blacklist.index')->with('success', 'Customer blacklisted successfully!');
    }

    public function destroy($id)
    {
        $blacklist = Blacklist::where('user_id', $id)->firstOrFail();
        $blacklist->delete();

        return redirect()->route('admin.blacklist.index')->with('success', 'Customer removed from blacklist successfully!');
    }
}
