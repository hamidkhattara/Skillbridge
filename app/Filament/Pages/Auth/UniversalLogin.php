<?php

namespace App\Filament\Pages\Auth;

use Filament\Pages\Auth\Login as BaseLogin;
use Filament\Facades\Filament;

class UniversalLogin extends BaseLogin
{
    public function authenticate(): ?\Filament\Http\Responses\Auth\Contracts\LoginResponse
    {
        $data = $this->form->getState();

        // 1. Check if the email and password are correct
        if (! Filament::auth()->attempt($this->getCredentialsFromFormData($data), $data['remember'] ?? false)) {
            $this->throwFailureValidationException();
        }

        // 2. Normally, Filament checks if the user is allowed into the Admin panel right here. 
        // We have purposely removed that check so Coaches don't get rejected!

        // 3. Send them to our Smart Router (LoginResponse.php) to be redirected
        return app(\Filament\Http\Responses\Auth\Contracts\LoginResponse::class);
    }
}