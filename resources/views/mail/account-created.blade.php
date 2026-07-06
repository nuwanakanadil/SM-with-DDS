<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Created</title>
</head>
<body style="margin:0;padding:24px;background:#f8fafc;font-family:Arial,sans-serif;color:#0f172a;">
    <div style="max-width:640px;margin:0 auto;background:#ffffff;border:1px solid #e2e8f0;border-radius:16px;padding:32px;">
        <p style="margin:0 0 16px;font-size:14px;letter-spacing:.08em;text-transform:uppercase;color:#2563eb;">
            {{ config('app.name') }}
        </p>
        <h1 style="margin:0 0 16px;font-size:28px;line-height:1.2;">
            Your account is ready
        </h1>
        <p style="margin:0 0 20px;font-size:15px;line-height:1.7;color:#334155;">
            Hello {{ $user->name }}, an account has been created for you as a {{ $role->value }}.
        </p>

        <div style="margin:0 0 24px;padding:20px;border-radius:14px;background:#eff6ff;border:1px solid #bfdbfe;">
            <p style="margin:0 0 10px;font-size:14px;color:#1e3a8a;"><strong>Email:</strong> {{ $user->email }}</p>
            <p style="margin:0 0 10px;font-size:14px;color:#1e3a8a;"><strong>Temporary Password:</strong> {{ $plainPassword }}</p>
            <p style="margin:0;font-size:14px;color:#1e3a8a;"><strong>Login Page:</strong> {{ route('login') }}</p>
        </div>

        <p style="margin:0 0 12px;font-size:14px;line-height:1.7;color:#475569;">
            Please sign in and change your password as soon as possible.
        </p>
    </div>
</body>
</html>
