<?php

namespace App\Filament\Resources\UserResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\User; 

class TotalUsersOverview extends BaseWidget
{
    protected function getStats(): array // <<< UBAH INI: Implementasikan getStats
    {
        $totalUsers = User::where('role', '!=', 'admin')->count();


        return [
            Stat::make('Total User', $totalUsers)
                ->description('Total User Pencari Kos ')
                ->descriptionIcon('heroicon-o-users') // Ikon pengguna
                ->color('success'), // Warna hijau
        ];
    }
}
