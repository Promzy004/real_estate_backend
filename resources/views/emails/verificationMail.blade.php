<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Your OTP Code</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f4f4f4; font-family: Arial, sans-serif;">
    <table width="100%" cellpadding="0" cellspacing="0" style="padding: 40px 0;">
        <tr>
            <td align="center">
                <table width="100%" style="max-width: 600px; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 8px rgba(0,0,0,0.05);">
                    <tr style="background-color: #007bff;">
                        <td align="center" style="padding: 30px;">
                            <h1 style="color: #ffffff; font-size: 28px; margin: 0;">PrimeDwell</h1>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 30px;">
                            <h2 style="color: #333333;">Hello {{ $user->firstname }},</h2>
                            <p style="font-size: 16px; color: #555555;">Use the OTP code below to verify your identity. This code is valid for 10 minutes.</p>

                            <div style="margin: 30px 0; text-align: center;">
                                <span style="display: inline-block; padding: 15px 30px; background-color: #007bff; color: #ffffff; font-size: 24px; letter-spacing: 2px; border-radius: 6px;">{{ $user->verification_code }}</span>
                            </div>

                            <p style="font-size: 14px; color: #999999;">
                                If you didn’t request this code, you can ignore this email.
                            </p>

                            <p style="margin-top: 40px; font-size: 14px; color: #666;">
                                Thanks,<br>
                                The PrimeDwell Team
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td align="center" style="background-color: #f0f0f0; padding: 20px; font-size: 12px; color: #888888;">
                            © {{ date('Y') }} PrimeDwell. All rights reserved.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
