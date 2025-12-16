<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use App\Rules\Recaptcha;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Validator::extend('recaptcha', function ($attribute, $value, $parameters, $validator) {
            $rule = new Recaptcha();
            
            $passed = true;
            $errorMessage = '';
            
            $rule->validate($attribute, $value, function ($error) use (&$passed, &$errorMessage) {
                $passed = false;
                $errorMessage = $error;
            });
            
            if (!$passed) {
                $validator->addFailure($attribute, 'recaptcha', [$errorMessage]);
            }
            
            return $passed;
        }, 'Verifikasi keamanan gagal.');
    }
}