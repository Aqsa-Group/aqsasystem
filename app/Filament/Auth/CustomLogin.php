<?php

namespace App\Filament\Auth;

use Filament\Pages\Auth\Login;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Form;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Filament\Http\Responses\Auth\LoginResponse;
use App\Models\Market\User;
use Filament\Facades\Filament;

class CustomLogin extends Login
{
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('login')
                    ->label('نام کاربری')
                    ->required()
                    ->autofocus()
                    ->autocomplete('username'),

                TextInput::make('password')
                    ->label('رمز عبور')
                    ->password()
                    ->required()
                    ->autocomplete('current-password'),

                Checkbox::make('remember')
                    ->label('مرا به خاطر بسپار'),
            ])
            ->statePath('data');
    }

    public function authenticate(): ?LoginResponse
    {
        $data = $this->form->getState();

        $login = $data['login'];
        $password = $data['password'];
        $remember = $data['remember'] ?? false;

        $user = User::where('username', $login)->first();

        if ($user && Hash::check($password, $user->password)) {
            Auth::guard('market')->login($user, $remember);
            Auth::shouldUse('market');
            Filament::auth('market')->login($user, $remember);

            return app(LoginResponse::class);
        }

        $this->throwFailureValidationException();
    }

    protected function throwFailureValidationException(): never
    {
        throw ValidationException::withMessages([
            'data.login' => __('نام کاربری یا رمز عبور اشتباه است.'),
        ]);
    }

    protected function getRedirectUrl(): string
    {
        return Filament::getUrl();
    }
}
