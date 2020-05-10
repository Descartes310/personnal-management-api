<?php

use Illuminate\Database\Seeder;
use App\Setting;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Setting::truncate();

        $settings = [
            [
                'key' => 'an-example-key',
                'value' => 'Une valeur de test',
                'description' => "Un exemple d'utilisation de Setting"
            ],
        ];

        foreach ($settings as $setting) {
            Setting::create($setting);
        }

    }
}
