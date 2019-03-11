<?php

namespace SimpleTaskManager\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use SimpleTaskManager\User;
use Illuminate\Support\Facades\Session;

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

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $users = User::paginate(10);
        return view('users.index', compact('users'));
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
        return redirect(route('home'));
    }

    public function destroy()
    {
        Session::flush();
        Auth::user()->delete();

        return redirect(route('index'));
    }
}
