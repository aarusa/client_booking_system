<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $superAdmin = User::create([
            'fname' => 'Arusha',
            'mname' => '',
            'lname' => 'Shahi',
            'email' => 'shahiarusha@gmail.com',
            'phone' => '0123456789',
            'password' => Hash::make('Hello123'), // Change to a secure password!
            'image' => '',
        ]);

        $superAdmin->assignRole('Super Admin');
    }
}
