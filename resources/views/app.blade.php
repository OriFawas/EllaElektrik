<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"  @class(['dark' => ($appearance ?? 'system') == 'dark'])>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        {{-- Inline script to detect system dark mode preference and apply it immediately --}}
        <script>
            (function() {
                const appearance = '{{ $appearance ?? "system" }}';

                if (appearance === 'system') {
                    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

                    if (prefersDark) {
                        document.documentElement.classList.add('dark');
                    }
                }
            })();
        </script>

        {{-- Inline style to set the HTML background color based on our theme in app.css --}}
        <style>
            html {
                background-color: oklch(1 0 0);
            }

            html.dark {
                background-color: oklch(0.145 0 0);
            }
        </style>

        <title inertia>{{ config('app.name', 'Laravel') }}</title>

        <meta name="csrf-token" content="{{ csrf_token() }}">

        <link rel="icon" href="/favicon.ico" sizes="any">
        <link rel="icon" href="/favicon.svg" type="image/svg+xml">
        <link rel="apple-touch-icon" href="/apple-touch-icon.png">

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        {{-- Tambahkan reCAPTCHA script di sini --}}
        <script 
            src="https://www.google.com/recaptcha/api.js?onload=vueRecaptchaLoaded&render=explicit" 
            async 
            defer>
        </script>

        @vite(['resources/js/app.ts', "resources/js/pages/{$page['component']}.vue"])
        @inertiaHead
    </head>
    <body class="font-sans antialiased">
        @inertia
        
        {{-- Tambahkan script untuk handle reCAPTCHA --}}
        <script>
            // Global callback untuk reCAPTCHA
            window.vueRecaptchaLoaded = function() {
                console.log('reCAPTCHA loaded successfully');
                
                // Beri tahu semua komponen Vue bahwa reCAPTCHA sudah siap
                if (window.dispatchEvent) {
                    window.dispatchEvent(new Event('recaptchaLoaded'));
                }
                
                // Simpan status di window untuk diakses oleh Vue
                window.recaptchaReady = true;
                
                // Inisialisasi grecaptcha jika belum ada
                if (!window.grecaptcha) {
                    console.warn('grecaptcha object not found');
                }
            };
            
            // Fallback: Cek apakah reCAPTCHA sudah dimuat setelah 3 detik
            setTimeout(function() {
                if (window.grecaptcha && typeof window.grecaptcha.render === 'function') {
                    console.log('reCAPTCHA ready (fallback check)');
                    window.recaptchaReady = true;
                }
            }, 3000);
        </script>
    </body>
</html>