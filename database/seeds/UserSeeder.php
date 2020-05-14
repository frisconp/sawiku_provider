<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // customer account
        User::create([
            'name' => 'Frisco Nirwana Putra',
            'email' => 'friscoputra.id@gmail.com',
            'phone_number' => '081259152990',
            'password' => Hash::make('12345678'),
            'role_id' => 1,
        ]);

        // seller account
        User::create([
            'name' => 'Nadhila Choirul Hardiana',
            'email' => 'nadhilach@gmail.com',
            'phone_number' => '085236754444',
            'password' => Hash::make('12345678'),
            'role_id' => 2,
        ]);
    }
}
