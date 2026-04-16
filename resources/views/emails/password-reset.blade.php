<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f3f4f6; margin: 0; padding: 20px;">
    <div style="max-w-md w-full max-width: 600px; margin: 0 auto; background-color: #ffffff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
        <h2 style="color: #1f2937; text-align: center;">Reset Password Anda</h2>
        <p style="color: #4b5563; line-height: 1.6;">Halo,</p>
        <p style="color: #4b5563; line-height: 1.6;">Anda menerima email ini karena kami menerima permintaan reset password untuk akun Anda.</p>
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ url('password/reset', $token) }}?email={{ urlencode($email) }}" style="background-color: #2563eb; color: #ffffff; padding: 12px 24px; text-decoration: none; border-radius: 6px; font-weight: bold; display: inline-block;">Reset Password</a>
        </div>
        <p style="color: #4b5563; line-height: 1.6;">Tautan reset password ini akan kadaluarsa dalam 60 menit.</p>
        <p style="color: #4b5563; line-height: 1.6;">Jika Anda tidak meminta reset password, abaikan email ini.</p>
        <p style="color: #4b5563; line-height: 1.6;">Salam,<br>Tim FISHERIES</p>
    </div>
</body>
</html>
