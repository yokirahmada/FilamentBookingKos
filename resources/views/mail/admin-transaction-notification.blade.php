<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifikasi Transaksi Sukses</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style type="text/css">
        /* Gaya CSS yang sama dari template email Anda sebelumnya */
        body { font-family: 'Poppins', Arial, sans-serif; margin: 0; padding: 0; background-color: #f4f4f4; color: #333333; }
        table { border-collapse: collapse; width: 100%; }
        td { padding: 0; }
        img { max-width: 100%; display: block; }
        .email-container { max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05); }
        .header { background-color: #D2EDE4; padding: 30px; text-align: center; color: #333333; }
        .header h1 { margin: 0; font-size: 28px; font-weight: bold; }
        .content { padding: 30px; }
        .content p { margin-bottom: 15px; line-height: 1.6; font-size: 16px; }
        .content ul { list-style: none; padding: 0; margin-bottom: 20px; }
        .content ul li { margin-bottom: 8px; font-size: 16px; }
        .content ul li strong { color: #555555; }
        .transaction-summary { background-color: #f9f9f9; border: 1px solid #eeeeee; border-radius: 8px; padding: 20px; margin-top: 25px; }
        .transaction-summary h2 { margin-top: 0; color: #3A9B26; font-size: 20px; margin-bottom: 15px; }
        .transaction-summary p { margin-bottom: 10px; font-size: 16px; }
        .transaction-summary .amount { font-size: 24px; font-weight: bold; color: #FF5500; margin-top: 15px; }
        .footer { background-color: #F2F9E6; padding: 20px; text-align: center; font-size: 12px; color: #555555; border-top: 1px solid #dddddd; border-radius: 0 0 8px 8px; margin-top: 30px; }
        .footer p { margin: 0; }
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
                        <h1 style="margin: 0; font-size: 28px; font-weight: bold;">Notifikasi Transaksi Baru!</h1>
                    </td>
                </tr>
            </table>

            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                <tr>
                    <td class="content" style="padding: 30px;">
                        <p style="margin-bottom: 15px; line-height: 1.6; font-size: 16px;">Halo Admin,</p>
                        <p style="margin-bottom: 15px; line-height: 1.6; font-size: 16px;">Ada transaksi baru yang berhasil diselesaikan di Istana Graha Kos!</p>

                        <div class="transaction-summary" style="background-color: #f9f9f9; border: 1px solid #eeeeee; border-radius: 8px; padding: 20px; margin-top: 25px;">
                            <h2 style="margin-top: 0; color: #3A9B26; font-size: 20px; margin-bottom: 15px;">Detail Transaksi</h2>
                            <p style="margin-bottom: 10px; font-size: 16px;"><strong>Order ID:</strong> {{ $transaction->code }}</p>
                            <p style="margin-bottom: 10px; font-size: 16px;"><strong>Tanggal Transaksi:</strong> {{ date('d F Y H:i:s', strtotime($transaction->created_at)) }} WIB</p>
                            <p style="margin-bottom: 10px; font-size: 16px;"><strong>Status Pembayaran:</strong> Sukses</p>
                            <p style="margin-bottom: 10px; font-size: 16px;"><strong>Jumlah Dibayar:</strong> <span class="amount" style="font-size: 24px; font-weight: bold; color: #FF5500; margin-top: 15px;">Rp{{ number_format($transaction->total_amount, 0, ',', '.') }}</span></p>
                        </div>

                        <div class="transaction-summary" style="background-color: #f9f9f9; border: 1px solid #eeeeee; border-radius: 8px; padding: 20px; margin-top: 25px;">
                            <h2>Detail Pemesan</h2>
                            <ul>
                                <li style="margin-bottom: 8px; font-size: 16px;"><strong>Nama:</strong> {{ $transaction->user->name }}</li>
                                <li style="margin-bottom: 8px; font-size: 16px;"><strong>Email:</strong> {{ $transaction->user->email }}</li>
                                <li style="margin-bottom: 8px; font-size: 16px;"><strong>Telepon:</strong> {{ $transaction->user->phone_number }}</li>
                            </ul>
                        </div>

                        <div class="transaction-summary" style="background-color: #f9f9f9; border: 1px solid #eeeeee; border-radius: 8px; padding: 20px; margin-top: 25px;">
                            <h2>Detail Pemesanan Kos</h2>
                            <ul>
                                <li style="margin-bottom: 8px; font-size: 16px;"><strong>Nama Kos:</strong> {{ $transaction->boardingHouse->name }}</li>
                                <li style="margin-bottom: 8px; font-size: 16px;"><strong>Alamat Kos:</strong> {{ $transaction->boardingHouse->address }}</li>
                                <li style="margin-bottom: 8px; font-size: 16px;"><strong>Jenis Kos:</strong> {{ $transaction->boardingHouse->category->name }}</li>
                                <li style="margin-bottom: 8px; font-size: 16px;"><strong>Untuk:</strong> {{ $transaction->boardingHouse->gender == 'L' ? 'Pria' : ($transaction->boardingHouse->gender == 'P' ? 'Wanita' : 'Campur') }}</li>
                                <li style="margin-bottom: 8px; font-size: 16px;"><strong>Tanggal Masuk:</strong> {{ date('d F Y', strtotime($transaction->start_date)) }}</li>
                                <li style="margin-bottom: 8px; font-size: 16px;"><strong>Durasi Sewa:</strong> {{ $transaction->duration }} Bulan</li>
                            </ul>
                        </div>

                        <p style="margin-bottom: 15px; line-height: 1.6; font-size: 16px;">Untuk detail lebih lanjut, Anda bisa login ke panel admin.</p>
                        <p style="margin-bottom: 15px; line-height: 1.6; font-size: 16px;">Salam hormat,</p>
                        <p style="margin-bottom: 15px; line-height: 1.6; font-size: 16px;">Sistem Otomatis Istana Graha Kos</p>
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