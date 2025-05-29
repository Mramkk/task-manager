<?php

namespace App\Http\Controllers;

use App\Events\UserActivityLogged;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;

class AuthController extends Controller
{
    public function loginView()
    {
        return view('auth.login');
    }
    public function registerView()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);
        try {
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = bcrypt($request->password);
            $user->save();
            auth()->login($user);
            toast('Register successfully !', 'success');
            Event::dispatch(new UserActivityLogged(
                'User registered successfully ! ' . $user->email
            ));
            return redirect()->route('dashboard')->with('success', 'Registration successful!');
        } catch (\Exception $e) {
            toast('Registration failed. Please try again.', 'error');
            return redirect()->route('register.view');
        }
    }
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if (auth()->attempt($request->only('email', 'password'), $request->remember)) {
            Event::dispatch(new UserActivityLogged(
                'User logged in successfully ! ' . auth()->user()->email
            ));
            toast('Login successfully !', 'success');
            return redirect()->route('dashboard');
        } else {
            toast('Login failed. Please check your credentials.', 'error');
            return redirect()->route('login.view');
        }
    }

    public function logout()
    {

        Event::dispatch(new UserActivityLogged('User logged out successfully ! ' . auth()->user()->email));
        toast('Logged out successfully !', 'success');
        auth()->logout();
        return redirect()->route('login.view');
    }
}
