<?php

use App\Settings\GeneralSettings;
use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('general.company_name', 'My Company' );
        $this->migrator->add('general.company_email', 'eoMwO@example.com' );
        $this->migrator->add('general.company_phone', '1234567890' );
        $this->migrator->add('general.company_logo', 'logo.png' );
    }
};
