<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmailVerificationPromptController extends Controller
{
    /**
     * Display the email verification prompt.
     */
    public function __invoke(Request $request): RedirectResponse|View
    {
        $user = $request->user();
        return $request->user()->hasVerifiedEmail()
                    ? redirect()->intended($this->redirectPath($user))
                    : view('auth.verify-email');
    }

    /**
     * Get the redirect path based on user role.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @return string
     */
    protected function redirectPath($user): string
    {
        if ($user->role) {
            switch ($user->role->role_name) {
                case 'admin':
                    return route('admin.dashboard_admin');
                case 'courier':
                    return route('kurir.dashboard');
            }
        }

        return RouteServiceProvider::HOME; // Default for customer
    }
}
