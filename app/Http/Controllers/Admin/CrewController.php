<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CrewController extends Controller
{
    public function index()
    {
        $crews = User::where('role', 'cashier')->latest()->paginate(10);
        
        return view('admin.crews', [
            'title' => 'Crew Management',
            'crews' => $crews
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['role'] = 'cashier';

        User::create($validated);

        return redirect()->route('admin.crews.index')->with('success', 'Crew created successfully!');
    }

    public function update(Request $request, User $crew)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$crew->id,
            'password' => 'nullable|string|min:8',
        ]);

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $validated['role'] = 'cashier';

        $crew->update($validated);

        return redirect()->route('admin.crews.index')->with('success', 'Crew updated successfully!');
    }

    public function destroy(User $crew)
    {
        // if ($crew->id === auth()->id()) {
        //     return back()->with('error', 'You cannot delete your own account!');
        // }

        $crew->delete();
        return redirect()->route('admin.crews.index')->with('success', 'Crew deleted successfully!');
    }
}