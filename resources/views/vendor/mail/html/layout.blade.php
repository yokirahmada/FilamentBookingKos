<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title> {{-- Ini akan menjadi judul email --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style type="text/css">
        /* Gaya dasar untuk kompatibilitas klien email */
        body { font-family: 'Poppins', Arial, sans-serif; margin: 0; padding: 0; background-color: #f4f4f4; color: #333333; }
        table { border-collapse: collapse; width: 100%; }
        td { padding: 0; }
        img { max-width: 100%; display: block; }

        /* Container utama email */
        .email-container { max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05); }

        /* Header email */
        .header { background-color: #D2EDE4; padding: 30px; text-align: center; color: #333333; }
        .header h1 { margin: 0; font-size: 28px; font-weight: bold; }

        /* Konten utama email */
        .content { padding: 30px; }
        .content p { margin-bottom: 15px; line-height: 1.6; font-size: 16px; }
        .content ul { list-style: none; padding: 0; margin-bottom: 20px; }
        .content ul li { margin-bottom: 8px; font-size: 16px; }
        .content ul li strong { color: #555555; }
        .content a.button { display: inline-block; padding: 12px 25px; margin-top: 20px; background-color: #FF5500; color: #ffffff; text-decoration: none; border-radius: 50px; font-weight: bold; } /* Gaya tombol untuk reset password */

        /* Bagian ringkasan transaksi */
        .transaction-summary { background-color: #f9f9f9; border: 1px solid #eeeeee; border-radius: 8px; padding: 20px; margin-top: 25px; }
        .transaction-summary h2 { margin-top: 0; color: #3A9B26; font-size: 20px; margin-bottom: 15px; }
        .transaction-summary p { margin-bottom: 10px; font-size: 16px; }
        .transaction-summary .amount { font-size: 24px; font-weight: bold; color: #FF5500; margin-top: 15px; }

        /* Footer email */
        .footer { background-color: #F2F9E6; padding: 20px; text-align: center; font-size: 12px; color: #555555; border-top: 1px solid #dddddd; border-radius: 0 0 8px 8px; margin-top: 30px; }
        .footer p { margin: 0; }

        /* Responsif */
        @media only screen and (max-width: 600px) {
            .email-container { width: 100% !important; border-radius: 0; }
            .content, .header, .footer { padding: 20px !important; }
            .header h1 { font-size: 24px !important; }
            .transaction-summary .amount { font-size: 20px !important; }
        }
    </style>
</head>
<body style="font-family: 'Poppins', Arial, sans-serif; margin: 0; padding: 0; background-color: #f4f4f4; color: #333333;">
    <center style="width: 100%; background-color: #f4f4f4;">
        <div class="email-container" style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);">
            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                <tr>
                    <td class="header" style="background-color: #D2EDE4; padding: 30px; text-align: center; color: #333333;">
                        <h1 style="margin: 0; font-size: 28px; font-weight: bold;">@yield('header-title', 'Pemberitahuan')</h1> {{-- Default title --}}
                    </td>
                </tr>
            </table>

            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                <tr>
                    <td class="content" style="padding: 30px;">
                        {{ $slot }} {{-- <<< INI KRUSIAL: Konten dari Mailable akan masuk ke sini >>> --}}
                    </td>
                </tr>
            </table>

            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                <tr>
                    <td class="footer" style="background-color: #F2F9E6; padding: 20px; text-align: center; font-size: 12px; color: #555555; border-top: 1px solid #dddddd; border-radius: 0 0 8px 8px; margin-top: 30px;">
                        <p style="margin: 0;">&copy; {{ date('Y') }} Istana Graha Kos. Semua hak dilindungi.</p>
                    </td>
                </tr>
            </table>
        </div>
    </center>
</body>
</html>