<?php

namespace App\Filament\Pages;

use App\Models\Team;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Tenancy\RegisterTenant;

class RegisterTeam extends RegisterTenant
{
    public static function getLabel(): string
    {
        return 'Register your team';
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Name')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    protected function handleRegistration(array $data): Team
    {
        /** @var Team $teams */
        parent::handleRegistration($data);

        $firstTeamFromUser = auth()->user()->teams()->first();

        if ($firstTeamFromUser) {
            return $firstTeamFromUser;
        }

        $firstTeamTotal = Team::first();

        auth()->user()->teams()->attach($firstTeamTotal->id);

        return $firstTeamTotal;
    }
}
