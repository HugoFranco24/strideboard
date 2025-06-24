<table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #ffffff; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
    <tr>
        <td align="center" style="padding: 20px 0;">
            <table role="presentation" border="0" cellpadding="0" cellspacing="0" style="max-width: 500px; width: 100%; background-color: hsl(0, 0%, 92%); border-radius: 4px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
                <tr>
                    <td align="center" style="padding: 20px 30px; color: hsl(0, 0%, 10%);">
                        <h1 style="font-size: 28px; color: #113F59; margin: 0 0 20px;">Reset Your Password</h1>
                        <p style="font-size: 16px; line-height: 24px; margin: 0px; color: #4d4d4d">
                            Hello <span style="font-weight: 600">{{ $user->name }}</span>, clicking the button bellow will take you to a page to reset your password.
                        </p>
                        <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                            <tr>
                                <td align="center" style="padding: 20px 0px 20px;">
                                    <a href="{{ $url }}" style="background-color: #113F59; color: #ffffff; text-decoration: none; padding: 10px 50px; font-size: 16px; border-radius: 4px; display: inline-block;">Reset Password</a>
                                </td>
                            </tr>
                        </table>
                        <p style="font-size: 14px; line-height: 22px; color: #4d4d4d; margin: 0 0 0px;">
                            If the button doesn't work, copy and paste this link into your browser:<br>
                        </p>
                        <a href="{{ $url }}" style="color: #113F59; text-decoration: underline;">{{ $url }}</a>
                        <p style="font-size: 14px; line-height: 22px; color: #4d4d4d; margin: 0;">
                            If you didn’t request a password reset, ignore this email.
                        </p>
                    </td>
                </tr>
                <tr>
                    <td align="center" style="padding: 20px; background-color: #113F59; color: #ffffff; font-size: 14px; border-bottom-left-radius: 4px; border-bottom-right-radius: 4px;">
                        <p style="margin: 0;">Best regards, Strideboard</p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>