<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Appointment;
use App\Models\Client;
use App\Models\Dog;
use Carbon\Carbon;
use Faker\Factory as Faker;

class AppointmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // Get all clients with their dogs
        $clients = Client::with('dogs')->get();
        
        if ($clients->isEmpty()) {
            $this->command->warn('No clients found. Please run ClientDogSeeder first.');
            return;
        }

        // Define services with variable pricing based on dog size
        $services = [
            1 => ['name' => 'Basic Grooming'],
            2 => ['name' => 'Full Grooming'],
            3 => ['name' => 'Nail Trim'],
            4 => ['name' => 'Ear Cleaning'],
            5 => ['name' => 'De-shedding Treatment'],
            6 => ['name' => 'Puppy Grooming'],
        ];

        // Define pricing based on dog size
        $pricing = [
            'small' => [
                1 => 35.00, // Basic Grooming
                2 => 60.00, // Full Grooming
                3 => 12.00, // Nail Trim
                4 => 10.00, // Ear Cleaning
                5 => 30.00, // De-shedding Treatment
                6 => 25.00, // Puppy Grooming
            ],
            'medium' => [
                1 => 45.00, // Basic Grooming
                2 => 75.00, // Full Grooming
                3 => 15.00, // Nail Trim
                4 => 12.00, // Ear Cleaning
                5 => 40.00, // De-shedding Treatment
                6 => 35.00, // Puppy Grooming
            ],
            'large' => [
                1 => 55.00, // Basic Grooming
                2 => 90.00, // Full Grooming
                3 => 18.00, // Nail Trim
                4 => 15.00, // Ear Cleaning
                5 => 50.00, // De-shedding Treatment
                6 => 45.00, // Puppy Grooming
            ],
            'extra_large' => [
                1 => 65.00, // Basic Grooming
                2 => 110.00, // Full Grooming
                3 => 20.00, // Nail Trim
                4 => 18.00, // Ear Cleaning
                5 => 60.00, // De-shedding Treatment
                6 => 55.00, // Puppy Grooming
            ],
        ];

        // Appointment statuses with weights for realistic distribution
        $statuses = [
            'completed' => 60,    // 60% completed
            'scheduled' => 20,    // 20% scheduled
            'confirmed' => 10,    // 10% confirmed
            'cancelled' => 8,     // 8% cancelled
            'in_progress' => 2,   // 2% in progress
        ];

        // Payment statuses with weights
        $paymentStatuses = [
            'paid' => 70,         // 70% paid
            'pending' => 20,      // 20% pending
            'partial' => 8,       // 8% partial
            'refunded' => 2,      // 2% refunded
        ];

        // Payment modes
        $paymentModes = ['cash', 'payid', 'card', 'bank_transfer'];

        // Business hours (9 AM to 6 PM)
        $businessHours = [
            'start' => 9,  // 9 AM
            'end' => 18,   // 6 PM
        ];

        // Generate appointments for the last 3 months and next 2 months
        $startDate = Carbon::now()->subMonths(3);
        $endDate = Carbon::now()->addMonths(2);
        
        $appointmentsCreated = 0;
        $maxAppointments = 100; // Limit to prevent too many appointments

        // Generate appointments
        for ($date = $startDate->copy(); $date <= $endDate; $date->addDay()) {
            // Skip Sundays (assuming business is closed on Sundays)
            if ($date->dayOfWeek === 0) {
                continue;
            }

            // Generate 1-4 appointments per day
            $appointmentsPerDay = $faker->numberBetween(1, 4);
            
            for ($i = 0; $i < $appointmentsPerDay && $appointmentsCreated < $maxAppointments; $i++) {
                // Select a random client and dog
                $client = $faker->randomElement($clients);
                $dog = $faker->randomElement($client->dogs);
                
                // Determine appointment time (business hours only)
                $hour = $faker->numberBetween($businessHours['start'], $businessHours['end'] - 1);
                $minute = $faker->randomElement([0, 15, 30, 45]);
                $startTime = Carbon::parse($date->format('Y-m-d') . " {$hour}:{$minute}:00");
                
                // Determine appointment duration (30-120 minutes)
                $duration = $faker->randomElement([30, 45, 60, 75, 90, 120]);
                $endTime = $startTime->copy()->addMinutes($duration);
                
                // Select services (1-3 services per appointment)
                $numServices = $faker->numberBetween(1, 3);
                $selectedServices = $faker->randomElements(array_keys($services), $numServices);
                
                // Calculate total price based on dog size and selected services
                $totalPrice = 0;
                $servicesData = [];
                $dogSize = $dog->size ?? 'medium';
                
                foreach ($selectedServices as $serviceId) {
                    if (isset($pricing[$dogSize][$serviceId])) {
                        $price = $pricing[$dogSize][$serviceId];
                        $totalPrice += $price;
                        $servicesData[] = [
                            'id' => $serviceId,
                            'name' => $services[$serviceId]['name'],
                            'price' => $price
                        ];
                    }
                }
                
                // Determine status based on date
                $status = 'scheduled';
                $paymentStatus = 'pending';
                $amountPaid = 0.00;
                $paidAt = null;
                
                if ($date->isPast()) {
                    // Past appointments are mostly completed
                    $status = $faker->randomElement(array_keys($statuses));
                    $paymentStatus = $faker->randomElement(array_keys($paymentStatuses));
                    
                    if ($paymentStatus === 'paid') {
                        $amountPaid = $totalPrice;
                        $paidAt = $faker->dateTimeBetween($startTime, $startTime->copy()->addDays(7));
                    } elseif ($paymentStatus === 'partial') {
                        $amountPaid = $faker->randomFloat(2, $totalPrice * 0.3, $totalPrice * 0.8);
                        $paidAt = $faker->dateTimeBetween($startTime, $startTime->copy()->addDays(7));
                    }
                }
                
                // Create appointment
                Appointment::create([
                    'client_id' => $client->id,
                    'dog_id' => $dog->id,
                    'appointment_date' => $date->format('Y-m-d'),
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                    'status' => $status,
                    'total_price' => $totalPrice,
                    'services_data' => json_encode($servicesData),
                    'notes' => $faker->optional(0.7)->sentence(),
                    'payment_status' => $paymentStatus,
                    'payment_mode' => $paymentStatus === 'paid' ? $faker->randomElement($paymentModes) : null,
                    'amount_paid' => $amountPaid,
                    'paid_at' => $paidAt,
                ]);
                
                $appointmentsCreated++;
            }
        }

        $this->command->info("Generated {$appointmentsCreated} appointments successfully!");
        $this->command->info("Date range: {$startDate->format('M d, Y')} to {$endDate->format('M d, Y')}");
    }
}
