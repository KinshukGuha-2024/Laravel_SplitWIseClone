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
                                @if($page == "register") 
                                
                                    @if(!empty($is_resend_otp))
                                        You requested a new OTP to continue with your account verification.<br><br>
                                        Your OTP: {{ $otp }}. <br><br>
                                    @else 
                                        Welcome to {{ env('APP_NAME') }},<br>
                                        To complete your registration, please enter the following One-Time Password (OTP): <br><br>
                                        {{ $otp }}<br><br>
                                    @endif

                                    This code will expire in {{ $minutes }} minutes.<br>
                                    Do not share this code with anyone.<br><br>
                                    We’re excited to have you onboard!<br><br>

                                    Thanks,<br>
                                    {{ env('APP_NAME') }} Team<br>

                                @elseif($page == "forget-password")

                                    @if(!empty($is_resend_otp))
                                        You requested a new OTP to continue with your account verification.<br><br>
                                        Your OTP: {{ $otp }}. <br><br>
                                    @else 
                                        We received a request to reset your {{ env('APP_NAME') }} account password.<br>
                                        Your One-Time Password (OTP) is: <br><br>
                                        {{ $otp }}<br><br>
                                    @endif

                                    This code is valid for {{ $minutes }} minutes.<br>
                                    Please do not share this code with anyone.<br><br>
                                    If you did not request a password reset, you can safely ignore this email.<br><br>

                                    Thanks,<br>
                                    {{ env('APP_NAME') }} Team<br>

                                @endif
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
