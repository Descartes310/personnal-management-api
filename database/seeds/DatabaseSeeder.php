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

        // $this->call(CityAndCountrySeeder::class);
        $this->call(SettingSeeder::class);
        $this->call(LaratrustSeeder::class);
        $this->call(AssignmentSeeder::class);

        Schema::enableForeignKeyConstraints();
        Model::reguard();
    }
}
