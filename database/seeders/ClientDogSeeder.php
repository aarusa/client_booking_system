<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Client;
use App\Models\Dog;
use Faker\Factory as Faker;

class ClientDogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // Sample dog breeds
        $breeds = [
            'Golden Retriever', 'Labrador Retriever', 'German Shepherd', 'Bulldog', 'Beagle',
            'Poodle', 'Rottweiler', 'Yorkshire Terrier', 'Boxer', 'Dachshund',
            'Shih Tzu', 'Great Dane', 'Doberman Pinscher', 'Miniature Schnauzer', 'Chihuahua',
            'Pomeranian', 'Siberian Husky', 'Cavalier King Charles Spaniel', 'Shetland Sheepdog', 'Australian Shepherd',
            'Border Collie', 'Bernese Mountain Dog', 'Maltese', 'Bichon Frise', 'Cocker Spaniel'
        ];

        // Sample coat types
        $coatTypes = ['Short', 'Medium', 'Long', 'Curly', 'Wiry', 'Smooth', 'Double', 'Silky', 'Corded', 'Hairless'];

        // Sample cities and states for Australia
        $locations = [
            ['city' => 'Essendon', 'state' => 'VIC'],
            ['city' => 'Moonee Ponds', 'state' => 'VIC'],
            ['city' => 'Ascot Vale', 'state' => 'VIC'],
            ['city' => 'Flemington', 'state' => 'VIC'],
            ['city' => 'North Melbourne', 'state' => 'VIC'],
            ['city' => 'West Melbourne', 'state' => 'VIC'],
            ['city' => 'Kensington', 'state' => 'VIC'],
            ['city' => 'Parkville', 'state' => 'VIC'],
            ['city' => 'Brunswick', 'state' => 'VIC'],
            ['city' => 'Coburg', 'state' => 'VIC'],
            ['city' => 'Pascoe Vale', 'state' => 'VIC'],
            ['city' => 'Strathmore', 'state' => 'VIC'],
            ['city' => 'Niddrie', 'state' => 'VIC'],
            ['city' => 'Keilor East', 'state' => 'VIC'],
            ['city' => 'Airport West', 'state' => 'VIC'],
            ['city' => 'Tullamarine', 'state' => 'VIC'],
            ['city' => 'Gladstone Park', 'state' => 'VIC'],
            ['city' => 'Glenroy', 'state' => 'VIC'],
            ['city' => 'Hadfield', 'state' => 'VIC'],
            ['city' => 'Oak Park', 'state' => 'VIC']
        ];

        // Generate 20 clients with dogs
        for ($i = 0; $i < 20; $i++) {
            $location = $faker->randomElement($locations);
            
            // Create client
            $client = Client::create([
                'first_name' => $faker->firstName(),
                'last_name' => $faker->lastName(),
                'email' => $faker->unique()->safeEmail(),
                'phone' => $faker->phoneNumber(),
                'address' => $faker->streetAddress(),
                'city' => $location['city'],
                'state' => $location['state'],
                'zipcode' => $faker->numberBetween(3000, 3999),
                'notes' => $faker->optional(0.7)->sentence(),
                'reminder' => $faker->optional(0.6)->randomElement(['Text message', 'Email', 'Phone call', 'SMS']),
            ]);

            // Generate 1-3 dogs per client
            $numDogs = $faker->numberBetween(1, 3);
            
            for ($j = 0; $j < $numDogs; $j++) {
                $gender = $faker->randomElement(['Male', 'Female']);
                $age = $faker->numberBetween(1, 15);
                
                Dog::create([
                    'client_id' => $client->id,
                    'name' => $faker->firstName($gender),
                    'breed' => $faker->randomElement($breeds),
                    'age' => $age,
                    'gender' => $gender,
                    'weight' => $faker->numberBetween(2, 80),
                    'coat_type' => $faker->randomElement($coatTypes),
                    'spayed_neutered' => $faker->randomElement(['Yes', 'No', 'Unknown']),
                    'behavior' => $faker->optional(0.8)->sentence(),
                    'notes' => $faker->optional(0.6)->sentence(),
                    'before_photo' => null, // Will be handled separately if needed
                    'after_photo' => null,  // Will be handled separately if needed
                ]);
            }
        }

        $this->command->info('Generated 20 clients with 1-3 dogs each successfully!');
    }
} 