<?php

use Illuminate\Database\Seeder;
use App\Profile;

class ProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Profile::truncate();

        $profiles = [
            [
                'name' => 'First name',
                'type' => 'text',
                'placeholder' => "Votre nom",
                'is_required' => '1',
                'slug' => 'first_name',
                'is_updatable' => '1',
                'min' => '0',
                'max' => '100',
                'step' => '0',
                'is_unique' => '0',
                'default' => '',
                'description' => "Nom de l'employé",
            ],[
                'name' => 'last name',
                'type' => 'text',
                'placeholder' => "Votre prénom",
                'is_required' => '1',
                'slug' => 'last_name',
                'is_updatable' => '1',
                'min' => '0',
                'max' => '100',
                'step' => '0',
                'is_unique' => '0',
                'default' => '',
                'description' => "Prenom de l'employé",
            ],[
                'name' => 'Avatar',
                'type' => 'file',
                'placeholder' => "Votre photo de profil",
                'is_required' => '1',
                'slug' => 'avatar',
                'is_updatable' => '1',
                'min' => '0',
                'max' => '100',
                'step' => '0',
                'is_unique' => '0',
                'default' => '',
                'description' => "Avatar de l'employé",
            ],[
                'name' => 'Email',
                'type' => 'email',
                'placeholder' => "Votre email",
                'is_required' => '1',
                'slug' => 'email',
                'is_updatable' => '1',
                'min' => '0',
                'max' => '100',
                'step' => '0',
                'is_unique' => '0',
                'default' => '',
                'description' => "Email de l'employé",
            ],[
                'name' => 'Date de naissance',
                'type' => 'date',
                'placeholder' => "Votre date de naissance",
                'is_required' => '1',
                'slug' => 'birth_date',
                'is_updatable' => '0',
                'min' => '0',
                'max' => '100',
                'step' => '0',
                'is_unique' => '0',
                'default' => '',
                'description' => "Date de naissance de l'employé",
            ]
        ];

        foreach ($profiles as $profile) {
            Setting::create($profile);
        }

    }
}
