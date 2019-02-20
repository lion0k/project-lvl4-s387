<?php

namespace SimpleTaskManager\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function edit()
    {
        $user = auth()->user();
        return view('users.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $data = $request->all();

        $request->validate([
            'name' => 'required|string|max:255',
            'password' => 'required|string|min:6|confirmed',
            'email' => ['required', 'string', 'email', 'max:255',  Rule::unique('users')->ignore($user->id)]
        ]);

        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->password = Hash::make($data['password']);
        $user->saveOrFail();


        flash('Your profile has been successfully updated')->success()->important();
        return redirect('home');
    }

    public function destroy()
    {
        $user = Auth::user();
        $user->delete();
        flash('Your profile has been successfully deleted')->error()->important();
        return redirect('/');
    }
}
