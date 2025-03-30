<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder

{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@mail.com',
            'password' => Hash::make('iamadmin'),
            'email_verified_at' => Carbon::now(), // Manually verify the email because of the fake email
        ]);

        $dataEntry = User::create([
            'name' => 'Data Entry User',
            'email' => 'dataentry@mail.com',
            'password' => Hash::make('iamdataentry'),
            'email_verified_at' => Carbon::now(), // Manually verify the email because of the fake email
        ]);

        $admin->assignRole('Administrator');
        $dataEntry->assignRole('Data Entry');
    }
}
