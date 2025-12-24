<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center" style="background: #f0f1f7">
    <tbody>
        <tr>
            <td align="center">
                {{-- <img src="{{ asset('backend/images/logo-mail.png') }}" style="display: block" alt="Logo"> --}}
                @if (env('APP_ENV') == 'local')
                    <img src="https://ubekuapp.com/backend/images/logo-mail.png" style="display: block" alt="Logo">
                @else
                    <img src="{{ asset('backend/images/logo-mail.png') }}" style="display: block" alt="Logo">
                @endif
            </td>
        </tr>
        <tr>
            <td>
                <table width="610px" border="0" cellpadding="0px" cellspacing="0" align="center" bgColor="#ffffff">
                    <tbody>
                        <tr>
                            <td style="color:#000000; padding-right: 40px;padding-bottom: 45px; padding-left: 40px;font-family:Verdana, Geneva, Tahoma, sans-serif;font-size:14px;font-weight:400;line-height:21px;">
                                Hello {{ $user->name }},<br><br>
                                You recently requested to reset your password for your {{ env('APP_NAME') }} account. Click the button below to reset it.<br><br>
                                <a href="{{ $link }}" style="display:inline-block;padding:12px 25px;background-color:#1d72b8;color:#ffffff !important;text-decoration:none;border-radius:5px;font-weight:bold;font-family:Arial,sans-serif;text-align:center;">Click To Reset Password</a><br><br>

                                This link is valid for {{ $minutes }} minutes.<br>
                                If you did not request a password reset, you can safely ignore this email.<br><br>

                                Thanks,<br>
                                {{ env('APP_NAME') }} Team<br>
                            </td>
                        </tr>
                        <tr>
                            <td style="color: #f3fafb; font-family:Verdana, Geneva, Tahoma, sans-serif;font-size:12px;font-weight:400;text-align:center; height:43px;"
                                bgcolor="#070922">
                                <b>Note:</b> Please do not reply to this email as this is an auto-generated response.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </tbody>
</table>
