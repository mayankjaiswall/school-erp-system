<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->applyMailSettings();
    }

    /**
     * Override mail config with SMTP settings saved from the admin panel.
     */
    protected function applyMailSettings(): void
    {
        try {
            if (! Schema::hasTable('settings')) {
                return;
            }

            $mail = Setting::group('mail');
        } catch (\Throwable $e) {
            // Database not ready (e.g. during install/migrations) — keep .env config.
            return;
        }

        if (empty($mail['mail_host'])) {
            return;
        }

        $encryption = $mail['mail_encryption'] ?? 'none';

        config([
            'mail.default' => 'smtp',
            'mail.mailers.smtp.host' => $mail['mail_host'],
            'mail.mailers.smtp.port' => (int) ($mail['mail_port'] ?? 587),
            'mail.mailers.smtp.username' => $mail['mail_username'] ?: null,
            'mail.mailers.smtp.password' => $mail['mail_password'] ?: null,
            'mail.mailers.smtp.encryption' => $encryption === 'none' ? null : $encryption,
            'mail.mailers.smtp.scheme' => $encryption === 'ssl' ? 'smtps' : null,
            'mail.from.address' => $mail['mail_from_address'] ?? config('mail.from.address'),
            'mail.from.name' => $mail['mail_from_name'] ?? config('mail.from.name'),
        ]);
    }
}
