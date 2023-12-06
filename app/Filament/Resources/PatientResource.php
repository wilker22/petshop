<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PatientResource\Pages;
use App\Filament\Resources\PatientResource\RelationManagers;
use App\Models\Patient;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PatientResource extends Resource
{
    protected static ?string $model = Patient::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                                ->required()
                                ->maxLength(255),
                Select::make('type')
                                ->options([
                                    'cat' => 'Cat',
                                    'dog' => 'Dog',
                                    'rabbit' => 'Rabbit'
                                ])->required(),
                DatePicker::make('date_of_birth')
                                ->displayFormat('d/m/Y')
                                ->required()
                                ->maxDate(now()),
                Select::make('owner_id')
                                ->relationship('owner', 'name')
                                ->required()
                                ->searchable()
                                ->preload()
                                ->createOptionForm([
                                    TextInput::make('name')
                                                ->required()
                                                ->maxLength(255),
                                    TextInput::make('email')
                                                ->label('E-mail Address')
                                                ->email()
                                                ->required()
                                                ->maxLength(255),
                                    TextInput::make('phone')
                                                ->required()
                                                ->label('Phone Number')
                                                ->tel(),

                                ])->required(),

                                ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Nome')->searchable(),
                TextColumn::make('type')->label('Tipo'),
                TextColumn::make('date_of_birth')
                            ->label('Nascimento')
                            ->dateTime('d-m-Y')
                            ->sortable(),

                TextColumn::make('owner.name')->label('Telefone Dono')->searchable(),
                TextColumn::make('owner.phone')->label('Telefone Dono'),
                TextColumn::make('owner.email')->label('E-mail Dono'),
            ])
            ->filters([
                SelectFilter::make('type')->options([
                    'cat' => 'Cat',
                    'dog' => 'Dog',
                    'rabbit' => 'Rabbit'
                ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListPatients::route('/'),
            'create' => Pages\CreatePatient::route('/create'),
            'edit' => Pages\EditPatient::route('/{record}/edit'),
        ];
    }
}
