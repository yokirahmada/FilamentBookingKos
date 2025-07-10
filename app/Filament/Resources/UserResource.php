<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User; // Pastikan ini diimpor
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn; // Impor ini
use Filament\Forms\Components\TextInput; // Impor ini
use Filament\Forms\Components\Textarea; // Impor ini
use Filament\Forms\Components\Select; // Impor ini
use Filament\Forms\Components\DateTimePicker; // Impor ini
use Illuminate\Support\Facades\Hash; // Impor ini, jika ingin kelola password

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    // Metode ini mengatur apa yang muncul di form (saat membuat/mengedit user)
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true), // Penting agar email unik, kecuali saat edit user itu sendiri
                TextInput::make('password')
                    ->password()
                    ->maxLength(255)
                    ->required(fn (string $operation): bool => $operation === 'create') // Wajib saat create, opsional saat edit
                    ->dehydrateStateUsing(fn (string $state): string => Hash::make($state)) // Hash password
                    ->dehydrated(fn (?string $state): bool => filled($state)) // Hanya simpan jika diisi
                    ->label('Password (biarkan kosong jika tidak ingin mengubah)'), // Label yang lebih informatif

                // Kolom baru Anda
                Select::make('role')
                    ->options([
                        'user' => 'User Biasa',
                        'admin' => 'Administrator',
                    ])
                    ->required()
                    ->default('user') // Default saat membuat user baru di admin
                    ->placeholder('Pilih Peran'),

                TextInput::make('phone_number')
                    ->tel() // Tipe input telepon
                    ->maxLength(20)
                    ->nullable()
                    ->label('Nomor Telepon'), // Label yang lebih baik

                Textarea::make('address')
                    ->maxLength(500)
                    ->nullable()
                    ->label('Alamat Lengkap'), // Label yang lebih baik

                DateTimePicker::make('email_verified_at')
                    ->label('Email Verified At')
                    ->nullable(),
            ]);
    }

    // Metode ini mengatur apa yang muncul di tabel daftar user
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('role') // Kolom role
                    ->badge() // Menampilkan sebagai badge
                    ->color(fn (string $state): string => match ($state) {
                        'admin' => 'success',
                        'user' => 'primary',
                        default => 'secondary',
                    })
                    ->searchable()
                    ->sortable(),
                TextColumn::make('phone_number') // Kolom phone_number
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false), // Bisa disembunyikan/ditampilkan
                TextColumn::make('address') // Kolom address
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('email_verified_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true), // Defaultnya tersembunyi
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // Anda bisa menambahkan filter di sini
                Tables\Filters\SelectFilter::make('role')
                    ->options([
                        'admin' => 'Administrator',
                        'user' => 'User Biasa',
                    ])
                    ->label('Filter Berdasarkan Peran'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            // Anda bisa menambahkan relasi di sini jika user memiliki relasi lain
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    // Opsi ini untuk mengurutkan daftar user secara default, misalnya berdasarkan created_at terbaru
    public static function getDefaultTableSortColumn(): ?string
    {
        return 'created_at';
    }

    public static function getDefaultTableSortDirection(): ?string
    {
        return 'desc';
    }
}