<head>
    <link rel="stylesheet" href="{{ asset('build/assets/top.css') }}" />
    <style>
        /* Inline styles to replace Tailwind with specific primary colors */

        /* Reset and basic styling */
        body {
            font-family: sans-serif;
            background-color: #f9fafb;
            margin: 0;
            padding: 0;
        }

        /* Table Styles */
        .container-table {
            width: 100%;
            height: 100%;
            background-color: #f9fafb;
            text-align: center;
        }

        .inner-table {
            width: 600px;
            margin: 0 auto;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        /* Header logo */
        .logo-cell {
            padding: 32px;
            text-align: center;
        }

        .logo-cell img {
            margin: 0 auto;
            width: 80px;
        }

        /* Content cell */
        .content-cell {
            padding: 14px;
            text-align: left;
            font-size: 16px;
            line-height: 1.5;
            color: #475569; /* Slate 700 */
        }

        .content-cell h1 {
            margin-top: 0;
            margin-bottom: 24px;
            font-size: 24px;
            font-weight: 600;
            color: #000000;
        }

        .content-cell p {
            margin: 0 0 24px;
        }

        a {
            text-decoration: none;
        }

        /* Button */
        .button-link {
            display: inline-block;
            padding: 14px 16px;
            font-size: 16px;
            font-weight: 600;
            color: #ffffff !important;
            background-color: #17a7af; /* Primary */
            border-radius: 4px;
            text-align: center;
            text-decoration: none;
        }

        .button-link:hover {
            background-color: #1d4ed8; /* Adjusted hover color for visual distinction */
        }

        /* Footer */
        .footer-cell {
            padding: 24px;
            font-size: 12px;
            color: #64748b; /* Slate 600 */
            text-align: center;
        }

        .footer-cell p {
            margin: 0;
            margin-bottom: 16px;
        }
    </style>
</head>
<body>
<table class="container-table">
    <tr>
        <td align="center" valign="top">
            <table class="inner-table">
                <!-- Logo Section -->
                <tr>
                    <td class="logo-cell">
                       <h1>Partydos</h1>
                    </td>
                </tr>

                <!-- Content Section -->
                <tr>
                    <td class="content-cell">
                        <h2>Password Reset Request</h2>
                        <p>Hello {{ $user->name }},</p>
                        <p>We received a request to reset your password. Click the button below to reset your password:</p>

                        <div style="text-align: center; margin: 24px 0;">
                            <a href="{{ $url }}" class="button-link">
                                Reset Password
                            </a>
                        </div>

                        <p>If you did not request a password reset, please ignore this email or contact support if you have questions.</p>
                        <p>Alternatively, you can copy and paste the following link into your browser:</p>
                        <p><a href="{{ $url }}" style="color: rgb(29 78 216 / var(--tw-text-opacity, 1));">{{ $url }}</a></p>
                        <p>This link will expire in {{ $durationPasswordValidMinutes }} minutes.</p>
                        <p>Thank you,</p>
                    </td>
                </tr>

                <!-- Footer Section -->
                <tr>
                    <td class="footer-cell">
                        <p>&copy; 2024 Partydos. All rights reserved.</p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
