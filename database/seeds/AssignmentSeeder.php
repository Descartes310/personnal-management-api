<?php

use Illuminate\Database\Seeder;

class AssignmentSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::table('assignments')->insert([
            'user_id' => 1,
            'assignment_type_id' => 1,
            'destination' => 'douala',
            'signature_date' => 'mar 12mai 2020',
            'installation_date' => '1 juin 2020',
            'raison' => 'retardataire',
        ]);
    }
}
