<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Login page-ai kaatta
    public function showLogin() {
        return view('auth.login');
    }

    public function login(Request $request) {
    // Form data-vai edukkuroam
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);
if (Auth::attempt($credentials)) {
    $request->session()->regenerate();
    return redirect()->intended('/dashboard'); // Success-na ulla kootitu po
}
  

    return back()->withErrors(['email' => 'Check your email/password!']); // Thappa irundha error
}


    // Logout panna
    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}