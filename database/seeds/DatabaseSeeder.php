<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        Schema::disableForeignKeyConstraints();

        $this->call(LaratrustSeeder::class);
        $this->call(CityAndCountrySeeder::class);
        $this->call(SettingSeeder::class);

        Schema::enableForeignKeyConstraints();
        Model::reguard();
    }
}
