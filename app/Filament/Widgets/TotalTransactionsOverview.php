<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Transaction; // Penting: Impor model Transaction


class TotalTransactionsOverview extends BaseWidget
{
   protected int | string | array $columnSpan = 'full';

protected function getColumns(): int
{
    return 3;
}
protected function getStats(): array
    {
        $totalTransactions = Transaction::count();
        $totalAmount = Transaction::where('payment_status', 'paid')
                                     ->orWhere('payment_status', 'settlement')
                                     ->sum('total_amount');

        // <<< TAMBAHKAN BARIS INI >>>
        $totalCanceledTransactions = Transaction::where('payment_status', 'canceled')->count();

        return [
            Stat::make('Total Transaksi', $totalTransactions)
                ->description('Jumlah semua transaksi booking')
                ->descriptionIcon('heroicon-o-receipt-percent')
                ->color('info'),
            Stat::make('Total Nominal Sukses', 'Rp' . number_format($totalAmount, 0, ',', '.'))
                ->description('Total nominal transaksi berhasil')
                ->descriptionIcon('heroicon-o-banknotes')
                ->color('primary'),
            // <<< TAMBAHKAN STAT INI >>>
            Stat::make('Total Dibatalkan', $totalCanceledTransactions)
                ->description('Jumlah transaksi yang dibatalkan')
                ->descriptionIcon('heroicon-o-x-circle') // Ikon silang
                ->color('danger'), // Warna merah
        ];
}
}