<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Service;
use App\Models\ServicePrice;

class ServicePriceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define pricing based on dog size
        $pricing = [
            'small' => [
                'Basic Grooming' => 35.00,
                'Full Grooming' => 60.00,
                'Nail Trim' => 12.00,
                'Ear Cleaning' => 10.00,
                'De-shedding Treatment' => 30.00,
                'Puppy Grooming' => 25.00,
            ],
            'medium' => [
                'Basic Grooming' => 45.00,
                'Full Grooming' => 75.00,
                'Nail Trim' => 15.00,
                'Ear Cleaning' => 12.00,
                'De-shedding Treatment' => 40.00,
                'Puppy Grooming' => 35.00,
            ],
            'large' => [
                'Basic Grooming' => 55.00,
                'Full Grooming' => 90.00,
                'Nail Trim' => 18.00,
                'Ear Cleaning' => 15.00,
                'De-shedding Treatment' => 50.00,
                'Puppy Grooming' => 45.00,
            ],
            'extra_large' => [
                'Basic Grooming' => 65.00,
                'Full Grooming' => 110.00,
                'Nail Trim' => 20.00,
                'Ear Cleaning' => 18.00,
                'De-shedding Treatment' => 60.00,
                'Puppy Grooming' => 55.00,
            ],
        ];

        $dogSizes = ['small', 'medium', 'large', 'extra_large'];

        // Get all services
        $services = Service::all();

        foreach ($services as $service) {
            foreach ($dogSizes as $size) {
                if (isset($pricing[$size][$service->name])) {
                    ServicePrice::firstOrCreate(
                        [
                            'service_id' => $service->id,
                            'dog_size' => $size,
                        ],
                        [
                            'price' => $pricing[$size][$service->name],
                        ]
                    );
                }
            }
        }

        $this->command->info('Service prices seeded successfully!');
    }
}
