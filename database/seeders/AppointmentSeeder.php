<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Appointment;
use App\Models\Client;
use App\Models\Dog;
use App\Models\Service;
use App\Models\ServicePrice;
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

        // Fetch all active services with their prices
        $allServices = Service::where('is_active', true)->with('prices')->get();
        if ($allServices->isEmpty()) {
            $this->command->warn('No services found. Please run ServiceSeeder and ServicePriceSeeder first.');
            return;
        }

        // Generate appointments for the current and next week only
        $startDate = Carbon::now()->startOfWeek(); // Monday this week
        $endDate = Carbon::now()->addWeek()->endOfWeek(); // Sunday next week

        // Appointment statuses with weights for realistic distribution (pro mostly)
        $statuses = [
            'in_progress' => 45,   // 45% in progress
            'confirmed' => 35,     // 35% confirmed
            'scheduled' => 10,     // 10% scheduled
            'completed' => 8,      // 8% completed
            'cancelled' => 2,      // 2% cancelled
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

        // Generate appointments
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
                
                // Select services (1-3 services per appointment) that have a price for the dog's size
                $dogSize = $dog->size ?? 'medium';
                $availableServices = $allServices->filter(function($service) use ($dogSize) {
                    return $service->prices->where('dog_size', $dogSize)->isNotEmpty();
                })->values();
                if ($availableServices->isEmpty()) {
                    continue; // skip if no services for this dog size
                }
                $numServices = $faker->numberBetween(1, min(3, $availableServices->count()));
                $selectedServices = $faker->randomElements($availableServices->all(), $numServices);
                // Calculate total price based on dog size and selected services
                $totalPrice = 0;
                $servicesData = [];
                foreach ($selectedServices as $service) {
                    $priceModel = $service->prices->where('dog_size', $dogSize)->first();
                    if ($priceModel) {
                        $price = $priceModel->price;
                        $totalPrice += $price;
                        $servicesData[] = [
                            'id' => $service->id,
                            'name' => $service->name,
                            'price' => $price
                        ];
                    }
                }
                if (empty($servicesData)) {
                    continue; // skip if no valid services
                }
                
                // Determine status and payment details based on date
                $status = 'scheduled';
                $paymentStatus = 'pending';
                $amountPaid = 0.00;
                $paidAt = null;
                $paymentMode = null;
                
                if ($date->isPast()) {
                    // Past appointments can have various statuses and payment details
                    $paymentStatus = $faker->randomElement(array_keys($paymentStatuses));
                    
                    // Set status based on payment status
                    if ($paymentStatus === 'paid' || $paymentStatus === 'refunded') {
                        $status = 'completed';
                    } else {
                        $status = $faker->randomElement(array_keys($statuses));
                    }
                    
                    if ($paymentStatus === 'paid') {
                        $amountPaid = $totalPrice;
                        $paidAt = $faker->dateTimeBetween($startTime, $startTime->copy()->addDays(7));
                        $paymentMode = $faker->randomElement($paymentModes);
                    } elseif ($paymentStatus === 'partial') {
                        $amountPaid = $faker->randomFloat(2, $totalPrice * 0.3, $totalPrice * 0.8);
                        $paidAt = $faker->dateTimeBetween($startTime, $startTime->copy()->addDays(7));
                        $paymentMode = $faker->randomElement($paymentModes);
                    } else {
                        // For pending and refunded, amount_paid remains 0.00
                        $amountPaid = 0.00;
                    }
                } else {
                    // Future appointments are scheduled and have no payment details
                    $status = 'scheduled';
                    $paymentStatus = 'pending';
                    $amountPaid = 0.00;
                    $paidAt = null;
                    $paymentMode = null;
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
                    'payment_mode' => $paymentMode,
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
