<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Users\Schemas;

use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Field;
use Filament\Schemas\Schema;

class UserForm
{
    protected static array $schema = [];

    public static function configure(Schema $schema): Schema
    {
        return $schema->components(static::getSchema());
    }

    public static function getDefaultComponents(): array
    {
        $components = [];
        $components[] = Components\Name::make();
        $components[] = Components\Email::make();
        // $components[] = Components\Password::make();
        // $components[] = Components\PasswordConfirmation::make();

        // user_profile
        $components[] = Section::make('Profile')
                ->relationship('profile')
                ->columns([
                    'sm' => 1,
                    'xl' => 2,
                    '2xl' => 4,
                ])
                ->schema([
                    Components\Active::make(),
                    Components\Gender::make(),
                    Components\FirstName::make(),
                    Components\LastName::make(),
                    Components\Phone::make(),
                    Components\PhoneApp::make(),
                    Components\Notify::make(),
                    Components\Extra::make(),
                ])
                ->columnSpanFull()
                ;

        $components[] = Components\Roles::make();
        $components[] = Components\Languages::make();
        $components[] = Components\Countries::make();

        return $components;
    }

    private static function getSchema(): array
    {
        return array_merge(self::getDefaultComponents(), self::$schema);
    }

    public static function register(Field | array $component): void
    {
        if (is_array($component)) {
            foreach ($component as $item) {
                if (! $item instanceof Field) {
                    continue;
                }

                self::$schema[] = $item;
            }

            return;
        }

        self::$schema[] = $component;
    }
}
