<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TestimonialResource\Pages;
use App\Filament\Resources\TestimonialResource\RelationManagers;
use App\Models\Testimonial;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle; // Tambahkan jika Anda menggunakannya di repeater
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn; // Untuk is_approved
use Filament\Tables\Actions\Action; // Untuk aksi 'approve'
use Filament\Tables\Actions\BulkAction; // Untuk bulk actions
use Filament\Tables\Actions\BulkActionGroup; // Untuk bulk actions
use Filament\Notifications\Notification; // Untuk notifikasi setelah aksi

// Impor model relasi
use App\Models\BoardingHouse;
use App\Models\User;
use App\Models\TestimonialPhoto;
use Illuminate\Support\Collection; // Untuk bulk actions


class TestimonialResource extends Resource
{
    protected static ?string $model = Testimonial::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';
    protected static ?string $modelLabel = 'Testimonials';
    protected static ?string $pluralModelLabel = 'Testimonials Kamar';
    protected static ?string $navigationLabel = 'Testimonials';
    protected static ?string $navigationGroup = 'Manajemen Properti';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('boarding_house_id')
                ->relationship('boardingHouse', 'name')
                ->searchable(['name'])
                ->preload()
                ->native(false)
                ->columnSpan(2)
                ->required(),
                Forms\Components\TextInput::make('name')
                ->autocomplete(false)
                ->autocapitalize()
                ->maxLength(50)
                ->required(),
                Forms\Components\TextInput::make('rating')
                ->numeric()
                ->minValue(1)
                ->maxValue(5)
                ->step(0.5)
                ->required(),
                Forms\Components\TextArea::make('content')
                ->columnSpan(2)
                ->required(),
                Forms\Components\TextInput::make('user_id')
                    ->numeric() // Karena ini ID
                    ->nullable()
                    ->columnSpan(2)
                    ->label('User ID (Opsional)'),
                Repeater::make('photos')
                ->relationship('photos') 
                ->columnSpan(2)
                ->schema([
                     Forms\Components\FileUpload::make('image_path')
                            ->acceptedFileTypes(['image/png', 'image/jpeg', 'image/jpg'])
                            ->directory('testimonial_gallery') // Folder penyimpanan banyak gambar testimonial
                            ->maxSize(2048)
                            ->imageEditor()
                            ->imageEditorAspectRatios([null, '1:1'])
                            ->required(),
                ])
                ->defaultItems(1),
                Forms\Components\Toggle::make('is_approved')
                    ->label('Disetujui')
                    ->inline(false) // Tampilkan label di atas toggle
                    ->default(false), // Default saat membuat baru

                        ]);
    }
    public static function table(Table $table): Table
        {
            return $table
                ->columns([

                    // Tables\Columns\ImageColumn::make('photos.image_path') // Untuk galeri
                    //     ->label('Galeri Foto')
                    //     ->circular()
                    //     ->stacked()
                    //     ->limit(3),
                    Tables\Columns\TextColumn::make('name')
                        ->label('Nama'),
                    Tables\Columns\TextColumn::make('boardingHouse.name')
                        ->label('Kos Terkait')
                        ->searchable()
                        ->sortable(),
                    Tables\Columns\TextColumn::make('content')
                        ->label('Ulasan')
                        ->limit(50),
                    Tables\Columns\TextColumn::make('rating')
                        ->label('Rating'),
                    
                    Tables\Columns\IconColumn::make('is_approved')
                        ->label('Disetujui')
                        ->boolean(), // Menampilkan ikon centang/silang
                
                    Tables\Columns\TextColumn::make('created_at')
                        ->dateTime()
                        ->sortable()
                        ->toggleable(isToggledHiddenByDefault: true),
                ])
                ->filters([
                    // Tambahkan filter untuk is_approved jika diinginkan
                    Tables\Filters\TernaryFilter::make('is_approved')
                        ->label('Disetujui'),
                ])
                ->actions([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                    
                    // Aksi Approve
                    Action::make('approve')
                        ->label('Setujui')
                        ->icon('heroicon-o-check-circle') // Ikon centang
                        ->color('success') // Warna hijau
                        ->visible(fn (Testimonial $record): bool => !$record->is_approved) // Hanya tampil jika belum disetujui
                        ->action(function (Testimonial $record) {
                            $record->is_approved = true;
                            $record->save();
                            Notification::make()
                                ->title('Testimonial Disetujui!')
                                ->success()
                                ->send();
                        }),
                    // Aksi Reject (opsional)
                    Action::make('reject')
                        ->label('Tolak')
                        ->icon('heroicon-o-x-circle') // Ikon silang
                        ->color('danger') // Warna merah
                        ->visible(fn (Testimonial $record): bool => $record->is_approved) // Hanya tampil jika sudah disetujui
                        ->action(function (Testimonial $record) {
                            $record->is_approved = false;
                            $record->save();
                            Notification::make()
                                ->title('Testimonial Ditolak!')
                                ->danger()
                                ->send();
                        }),
                ])
                ->bulkActions([
                    Tables\Actions\BulkActionGroup::make([
                        Tables\Actions\DeleteBulkAction::make(),
                        // Bulk Action Approve
                        Tables\Actions\BulkAction::make('approve_selected')
                            ->label('Setujui Terpilih')
                            ->icon('heroicon-o-check-circle')
                            ->color('success')
                            ->action(function (Collection $records) {
                                $records->each(fn (Testimonial $record) => $record->update(['is_approved' => true]));
                                Notification::make()
                                    ->title('Testimonial Terpilih Disetujui!')
                                    ->success()
                                    ->send();
                            }),
                        // Bulk Action Reject (opsional)
                        Tables\Actions\BulkAction::make('reject_selected')
                            ->label('Tolak Terpilih')
                            ->icon('heroicon-o-x-circle')
                            ->color('danger')
                            ->action(function (Collection $records) {
                                $records->each(fn (Testimonial $record) => $record->update(['is_approved' => false]));
                                Notification::make()
                                    ->title('Testimonial Terpilih Ditolak!')
                                    ->danger()
                                    ->send();
                            }),
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
            'index' => Pages\ListTestimonials::route('/'),
            'create' => Pages\CreateTestimonial::route('/create'),
            'edit' => Pages\EditTestimonial::route('/{record}/edit'),
        ];
    }
}
