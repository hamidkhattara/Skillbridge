<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Filament\Http\Responses\Auth\Contracts\LoginResponse as LoginResponseContract;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Bind the logic directly into memory, bypassing the file system!
        $this->app->singleton(
            LoginResponseContract::class,
            function () {
                return new class implements LoginResponseContract {
                    public function toResponse($request)
                    {
                        $user = auth()->user();

                        // 1. If Employee, send to Coach panel
                        if ($user->role === 'coach') {
                            return redirect()->to('/coach');
                        }

                        // 2. If Boss, send to Admin panel
                        return redirect()->to('/');
                    }
                };
            }
        );
    }

    public function boot(): void
    {
        //
    }
}