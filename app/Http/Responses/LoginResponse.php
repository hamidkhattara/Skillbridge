<?php

namespace App\Http\Responses;

use Filament\Http\Responses\Auth\Contracts\LoginResponse as LoginResponseContract;
use Illuminate\Http\RedirectResponse;
use Livewire\Features\SupportRedirects\Redirector;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request): RedirectResponse | Redirector
    {
        // 1. Get the user who just successfully logged in
        $user = auth()->user();

        // 2. Check their role and send them to the correct panel!
        if ($user->role === 'coach') {
            return redirect()->to('/coach');
        }

        // 3. If they are the boss, send them to the admin panel
        return redirect()->to('/admin');
    }
}