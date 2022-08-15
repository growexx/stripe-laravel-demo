<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;


class UserController extends Controller
{
    public function index()
    {
        if (Auth::user()->role == 'admin') {
            $users = User::all();
            return view('user.index', compact('users'));
        } else {
            return view('404');
        }
    }

    public function show(User $user)
    {
        if (Auth::user()->role == 'admin') {
            return view('user.show', compact('user'));
        } else {
            return view('404');
        }
    }

    public function edit(User $user)
    {
        if (Auth::user()->role == 'admin') {
            return view('user.edit', compact('user'));
        } else {
            return view('404');
        }
    }

    public function update(Request $request, User $user)
    {
        if (Auth::user()->role == 'admin') {
            $request->validate([
                'name' => 'required',
                'email' => 'required',
                'role' => 'required',
            ]);

            $user->update($request->all());

            return redirect()->route('user.index')
                ->with('success', 'User updated successfully.');
        } else {
            return view('404');
        }
    }

    public function destroy(User $user)
    {
        if (Auth::user()->role == 'admin') {
            $user->delete();

            return redirect()->route('user.index')
                ->with('success', 'User deleted successfully.');
        } else {
            return view('404');
        }
    }
}
