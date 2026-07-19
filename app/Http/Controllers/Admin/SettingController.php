<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class SettingController extends Controller
{
    /**
     * Settings hub — lists the available setting sections.
     */
    public function index()
    {
        $mailConfigured = Setting::query()->where('group', 'mail')->exists();

        return view('admin.settings.index', compact('mailConfigured'));
    }

    /**
     * SMTP mail settings form.
     */
    public function mail()
    {
        $mail = Setting::group('mail');

        $settings = [
            'mail_host' => $mail['mail_host'] ?? '',
            'mail_port' => $mail['mail_port'] ?? '587',
            'mail_username' => $mail['mail_username'] ?? '',
            'mail_password' => $mail['mail_password'] ?? '',
            'mail_encryption' => $mail['mail_encryption'] ?? 'tls',
            'mail_from_address' => $mail['mail_from_address'] ?? '',
            'mail_from_name' => $mail['mail_from_name'] ?? config('app.name'),
        ];

        return view('admin.settings.mail', compact('settings'));
    }

    /**
     * Save SMTP mail settings.
     */
    public function updateMail(Request $request)
    {
        $data = $this->validateMail($request);

        // Keep the stored password when the field is left blank.
        if ($data['mail_password'] === null || $data['mail_password'] === '') {
            $data['mail_password'] = Setting::get('mail_password', '');
        }

        Setting::setMany($data, 'mail');

        return redirect()
            ->route('settings.mail')
            ->with('success', 'Mail settings saved successfully.');
    }

    /**
     * Send a test email using the settings submitted in the form
     * (so admin can verify before/after saving).
     */
    public function sendTestMail(Request $request)
    {
        $data = $this->validateMail($request);

        $request->validate([
            'test_email' => ['required', 'email'],
        ]);

        if ($data['mail_password'] === null || $data['mail_password'] === '') {
            $data['mail_password'] = Setting::get('mail_password', '');
        }

        $this->applyMailConfig($data);

        try {
            Mail::raw(
                "This is a test email from " . config('app.name') . ".\n\nYour SMTP mail settings are working correctly.",
                function ($message) use ($request) {
                    $message->to($request->input('test_email'))
                        ->subject('SMTP Test Email — ' . config('app.name'));
                }
            );
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send test email: ' . $e->getMessage(),
            ], 422);
        }

        return response()->json([
            'success' => true,
            'message' => 'Test email sent successfully to ' . $request->input('test_email') . '.',
        ]);
    }

    private function validateMail(Request $request): array
    {
        return $request->validate([
            'mail_host' => ['required', 'string', 'max:255'],
            'mail_port' => ['required', 'integer', 'between:1,65535'],
            'mail_username' => ['nullable', 'string', 'max:255'],
            'mail_password' => ['nullable', 'string', 'max:255'],
            'mail_encryption' => ['required', 'in:none,tls,ssl'],
            'mail_from_address' => ['required', 'email', 'max:255'],
            'mail_from_name' => ['required', 'string', 'max:255'],
        ], [], [
            'mail_host' => 'SMTP host',
            'mail_port' => 'SMTP port',
            'mail_username' => 'SMTP username',
            'mail_password' => 'SMTP password',
            'mail_encryption' => 'encryption',
            'mail_from_address' => 'from email address',
            'mail_from_name' => 'from name',
        ]);
    }

    private function applyMailConfig(array $mail): void
    {
        $encryption = $mail['mail_encryption'] ?? 'none';

        config([
            'mail.default' => 'smtp',
            'mail.mailers.smtp.host' => $mail['mail_host'],
            'mail.mailers.smtp.port' => (int) $mail['mail_port'],
            'mail.mailers.smtp.username' => $mail['mail_username'] ?: null,
            'mail.mailers.smtp.password' => $mail['mail_password'] ?: null,
            'mail.mailers.smtp.encryption' => $encryption === 'none' ? null : $encryption,
            'mail.mailers.smtp.scheme' => $encryption === 'ssl' ? 'smtps' : null,
            'mail.from.address' => $mail['mail_from_address'],
            'mail.from.name' => $mail['mail_from_name'],
        ]);

        // Rebuild the mailer so the new config is picked up.
        Mail::purge('smtp');
    }
}
