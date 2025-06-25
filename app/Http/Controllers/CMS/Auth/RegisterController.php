<?php

namespace App\Http\Controllers\CMS\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    /**
     * Show the registration form.
     */
    public function showRegistrationForm()
    {
        return view('cms.auth.register');
    }

    /**
     * Handle the registration request.
     */
    public function register(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'fname' => 'required|string|max:255',
            'mname' => 'nullable|string|max:255',
            'lname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|confirmed|min:6',
            'image' => 'nullable|string|max:255',
            'terms' => 'accepted',  // 'accepted' checks that checkbox is ticked
        ],[
        'fname.required' => 'Please enter your first name.',
        'lname.required' => 'Please enter your last name.',
        'email.required' => 'Please enter your email address.',
        'email.email' => 'Please enter a valid email address.',
        'phone.required' => 'Please enter your phone number.',
        'password.required' => 'Please set a password.',
        'password.confirmed' => 'Password confirmation does not match.',
        'password.min' => 'Password must be at least 6 characters long.',
        'terms.accepted' => 'You must agree to the terms and conditions.',
    ]);

        // Create the user
        $user = User::create([
            'fname' => $request->fname,
            'mname' => $request->mname,
            'lname' => $request->lname,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'image' => $request->image,
        ]);

        // Assign default role
        $user->assignRole('User'); // Make sure this role exists

        // Automatically log in the user
        Auth::login($user);

        // Redirect to dashboard
        return redirect()->route('dashboard.index')->with('success', 'Account created successfully!');
    }
}