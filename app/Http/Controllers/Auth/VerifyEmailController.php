<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        $user = $request->user();

        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended($this->redirectPath($user).'?verified=1');
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return redirect()->intended($this->redirectPath($user).'?verified=1');
    }

    /**
     * Get the post-verification redirect path based on user role.
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
