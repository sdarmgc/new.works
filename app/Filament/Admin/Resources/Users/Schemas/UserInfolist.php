<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Users\Schemas;

use Filament\Infolists\Components\Entry;
use Filament\Schemas\Schema;

class UserInfolist
{
    protected static array $schema = [];

    public static function configure(Schema $schema): Schema
    {
        return $schema->components(static::getSchema())
                ->columns([
                    'sm' => 1,
                    'xl' => 2,
                    '2xl' => 4,
                ]);
    }

    public static function getDefaultComponents(): array
    {
        $components = [];

        $components[] = Entries\Name::make();
        $components[] = Entries\Email::make();
        $components[] = Entries\Verified::make();

        // user_profile
        $components[] = Entries\Gender::make();
        $components[] = Entries\FirstName::make();
        $components[] = Entries\LastName::make();
        $components[] = Entries\Phone::make();
        $components[] = Entries\Responsibility::make();
        $components[] = Entries\Extra::make();
        $components[] = Entries\Active::make();
        $components[] = Entries\Notify::make();
        $components[] = Entries\LastLoginAt::make();
        $components[] = Entries\LastLoginIP::make();
        
        $components[] = Entries\Roles::make();
        $components[] = Entries\Languages::make();
        $components[] = Entries\Countries::make();
        $components[] = Entries\MailGroups::make();
        return $components;
    }

    private static function getSchema(): array
    {
        return array_merge(self::getDefaultComponents(), self::$schema);
    }

    public static function register(Entry | array $component): void
    {
        if (is_array($component)) {
            foreach ($component as $item) {
                if (! $item instanceof Entry) {
                    continue;
                }

                self::$schema[] = $item;
            }

            return;
        }

        self::$schema[] = $component;
    }
}
