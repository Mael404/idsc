<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // Authenticate the user
        $request->authenticate();

        // Regenerate the session to prevent session fixation
        $request->session()->regenerate();

        // Check the role of the authenticated user
        if (Auth::user()->role === 'vp_admin') {
            // Redirect admin to the admin's specific page (vpadmin_db)
            return redirect()->route('vpadmin.vpadmin_db');
        }

        // Check if the role is vpacademics and redirect accordingly
        if (Auth::user()->role === 'vp_academic') {
            // Redirect vpacademics to their specific page (vpacademics_db)
            return redirect()->route('vp_academic.vpacademic_db');
        }

        // For other users, redirect them to the default dashboard
        return redirect()->intended(route('dashboard', absolute: false));
    }


    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
