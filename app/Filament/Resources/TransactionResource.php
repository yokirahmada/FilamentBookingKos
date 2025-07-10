<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransactionResource\Pages;
use App\Filament\Resources\TransactionResource\RelationManagers;
use App\Models\Transaction;
use Filament\Forms; // Pastikan ini diimpor (atau Anda bisa pakai use Filament\Forms\Components;)
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn; // Pastikan ini diimpor
use Filament\Forms\Components\TextInput; // Pastikan ini diimpor
use Filament\Forms\Components\Textarea; // Pastikan ini diimpor
use Filament\Forms\Components\Select; // <<< PASTIKAN INI DIIMPOR JUGA, jika Anda ingin menggunakan "Select" saja.
                                    // Atau Anda bisa menggunakan Forms\Components\Select::make() di bawah
use Filament\Forms\Components\DatePicker; // Pastikan ini diimpor
// use Filament\Forms\Components\DateTimePicker; // Pastikan ini diimpor jika digunakan
use Illuminate\Support\Facades\Hash; // Pastikan ini diimpor jika digunakan

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';
    protected static ?string $modelLabel = 'Transaksi';
    protected static ?string $pluralModelLabel = 'Transaksi';
    protected static ?string $navigationLabel = 'Transaksi';
    protected static ?string $navigationGroup = 'Transaksi';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('code')
                    ->required()
                    ->default(fn () => 'TRX-' . \Illuminate\Support\Str::random(10))
                    ->disabled()
                    ->unique(ignoreRecord: true),

                // Kolom user_id yang baru
                // <<< UBAH BARIS INI >>>
                Forms\Components\Select::make('user_id') // <<< INI HARUS Forms\Components\Select
                    ->relationship('user', 'name')
                    ->searchable(['name', 'email'])
                    ->preload()
                    ->native(false)
                    ->required()
                    ->label('User Pembooking'),

                Forms\Components\Select::make('boarding_house_id')
                    ->relationship('boardingHouse', 'name')
                    ->searchable(['name'])
                    ->preload()
                    ->native(false)
                    ->required(),
                Forms\Components\Select::make('room_id')
                    ->relationship('room', 'name')
                    ->searchable(['name'])
                    ->preload()
                    ->native(false)
                    ->required(),

                Forms\Components\Select::make('payment_method')
                    ->options([
                        'down_payment' => 'Down Payment',
                        'full_payment' => 'Full Payment',
                    ])
                    ->native(false)
                    ->required(),
                Forms\Components\TextInput::make('payment_status')
                    ->required(),
                Forms\Components\DatePicker::make('start_date')
                    ->native(false)
                    ->weekStartsOnMonday()
                    ->minDate(now())
                    ->displayFormat('d F Y')
                    ->locale('id')
                    ->required(),
                Forms\Components\TextInput::make('duration')
                    ->numeric()
                    ->suffix('days')
                    ->required(),
                Forms\Components\TextInput::make('total_amount')
                    ->numeric()
                    ->prefix('IDR')
                    ->required(),
                Forms\Components\DatePicker::make('transaction_date')
                    ->native(false)
                    ->weekStartsOnMonday()
                    ->minDate(now())
                    ->maxDate(now())
                    ->displayFormat('d F Y')
                    ->locale('id')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
{
    return $table
        ->columns([
            Tables\Columns\TextColumn::make('code')
                ->searchable() // Tambahkan searchable
                ->sortable(),  // Tambahkan sortable

            // Kolom user yang baru
            Tables\Columns\TextColumn::make('user.name') // Menampilkan nama user dari relasi
                ->label('Nama Pembooking')
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('user.email') // Menampilkan email user dari relasi
                ->label('Email Pembooking')
                ->searchable()
                ->sortable(),

            Tables\Columns\TextColumn::make('boardingHouse.name')
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('room.name')
                ->searchable()
                ->sortable(),

            // HAPUS BARIS INI: Tables\Columns\TextColumn::make('name'),
            // HAPUS BARIS INI: Tables\Columns\TextColumn::make('email'),

            Tables\Columns\TextColumn::make('payment_method')
                ->searchable()
                ->sortable()
                ->badge() // Bagus untuk enum
                ->color(fn (string $state): string => match ($state) {
                    'down_payment' => 'warning',
                    'full_payment' => 'success',
                    default => 'secondary',
                }),
            Tables\Columns\TextColumn::make('payment_status')
                ->searchable()
                ->sortable()
                ->badge() // Bagus untuk status
                ->color(fn (string $state): string => match ($state) {
                    'pending' => 'warning',
                    'paid' => 'success',
                    'cancelled' => 'danger',
                    default => 'secondary',
                }),
            Tables\Columns\TextColumn::make('total_amount')
                ->numeric()
                ->money('IDR') // Format sebagai mata uang IDR
                ->sortable(),
            Tables\Columns\TextColumn::make('transaction_date')
                ->date('d F Y') // Format tanggal lebih bagus
                ->sortable(),
            Tables\Columns\TextColumn::make('start_date') // Tambahkan start_date agar terlihat
                ->date('d F Y')
                ->sortable(),
            Tables\Columns\TextColumn::make('duration') // Tambahkan duration agar terlihat
                ->sortable(),
        ])
        ->filters([
            // Tambahkan filter berdasarkan user jika diperlukan
            Tables\Filters\SelectFilter::make('user_id')
                ->relationship('user', 'name')
                ->label('Filter Berdasarkan User'),
            Tables\Filters\SelectFilter::make('payment_status')
                ->options([
                    'pending' => 'Pending',
                    'paid' => 'Paid',
                    'cancelled' => 'Cancelled',
                ])
                ->label('Filter Status Pembayaran'),
        ])
        ->actions([
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make(), // Tambahkan delete action
        ])
        ->bulkActions([
            Tables\Actions\BulkActionGroup::make([
                Tables\Actions\DeleteBulkAction::make(),
            ]),
        ]);
}

public static function getRelations(): array
{
    return [
        //
    ];
}

public static function getPages(): array
{
    return [
        'index' => Pages\ListTransactions::route('/'),
        'create' => Pages\CreateTransaction::route('/create'),
        'edit' => Pages\EditTransaction::route('/{record}/edit'),
    ];
}
}