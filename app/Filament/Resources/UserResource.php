<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static bool $isScopedToTenant = false;

    protected static ?string $tenantOwnershipRelationshipName = 'teams';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->required()
                    ->email()
                    ->maxLength(255),
                Forms\Components\TextInput::make('password')
                    ->hidden(fn ($record) => $record !== null)
                    ->required()
                    ->password()
                    ->minLength(8)
                    ->maxLength(255)
                    ->dehydrated(fn ($state) => ! empty($state))
                    ->confirmed('password_confirmation')
                    ->label('Password'),
                Forms\Components\TextInput::make('password_confirmation')
                    ->hidden(fn ($record) => $record !== null)
                    ->password()
                    ->maxLength(255)
                    ->dehydrated(false)
                    ->label('Confirm Password'),
                Forms\Components\Select::make('role_id')
                    ->relationship(
                        name: 'role',
                        titleAttribute: 'name',
                        modifyQueryUsing: fn ($query) => auth()->user()->role->name === 'admin'
                            ? $query
                            : $query->where('name', '!=', 'admin')
                    )
                    ->preload()
                    ->required()
                    ->label('Role'),
                Forms\Components\Select::make('teams')
                    ->relationship(
                        name: 'teams',
                        titleAttribute: 'name',
                        modifyQueryUsing: fn ($query) => auth()->user()->role->name === 'admin'
                            ? $query
                            : $query->where('team_id', Filament::getTenant()->id)
                    )
                    ->multiple()
                    ->preload()
                    ->required()
                    ->label('Teams'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn ($query) => auth()->user()->role->name === 'admin'
                ? $query
                : $query->whereHas('teams', fn ($q) => $q->where('teams.id', Filament::getTenant()->id))
            )
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->hidden(),
                Tables\Columns\TextColumn::make('name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('role.name')
                    ->sortable()
                    ->searchable()
                    ->label('Role'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                //
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
