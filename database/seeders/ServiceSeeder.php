<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Service;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $services = [
            [
                'name' => 'Basic Grooming',
                'description' => 'Bath, brush, nail trim, and basic styling',
                'price' => 45.00,
                'duration' => 60,
                'is_active' => true,
            ],
            [
                'name' => 'Full Grooming',
                'description' => 'Complete grooming including haircut, bath, brush, nail trim, and styling',
                'price' => 75.00,
                'duration' => 90,
                'is_active' => true,
            ],
            [
                'name' => 'Nail Trim',
                'description' => 'Nail trimming and filing',
                'price' => 15.00,
                'duration' => 15,
                'is_active' => true,
            ],
            [
                'name' => 'Ear Cleaning',
                'description' => 'Ear cleaning and inspection',
                'price' => 12.00,
                'duration' => 15,
                'is_active' => true,
            ],
            [
                'name' => 'De-shedding Treatment',
                'description' => 'Specialized treatment to reduce shedding',
                'price' => 40.00,
                'duration' => 45,
                'is_active' => true,
            ],
            [
                'name' => 'Puppy Grooming',
                'description' => 'Gentle grooming for puppies (under 6 months)',
                'price' => 35.00,
                'duration' => 45,
                'is_active' => true,
            ],
        ];

        foreach ($services as $service) {
            Service::firstOrCreate(
                ['name' => $service['name']],
                $service
            );
        }
    }
} 