<?php

namespace Database\Seeders;

use App\Models\Machine;
use App\Models\Service;
use Illuminate\Database\Seeder;

class MachineServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $machines = [
            'MC' => [
                'capacity' => 8,
                'type' => 'Mesin Cuci',
                'duration' => 50,
            ],
            'PN' => [
                'capacity' => 5,
                'type' => 'Pengering',
                'duration' => 45,
            ],
            'ST' => [
                'capacity' => 1,
                'type' => 'Setrika',
                'duration' => 12.5,
            ],
        ];

        foreach ($machines as $key => $payload) {
            $machines[$key] = Machine::create($payload);
        }

        $services = [
            [
                'name' => 'Cuci Reguler',
                'duration' => 3,
                'price' => 6000,
                'machines' => ['MC', 'PN', 'ST'],
            ],
            [
                'name' => 'Cuci Express',
                'duration' => 1,
                'price' => 10000,
                'machines' => ['MC', 'PN', 'ST'],
            ],
            [
                'name' => 'Kering Reguler',
                'duration' => 3,
                'price' => 5000,
                'machines' => ['PN', 'ST'],
            ],
            [
                'name' => 'Kering Express',
                'duration' => 1,
                'price' => 9000,
                'machines' => ['PN', 'ST'],
            ],
            [
                'name' => 'Setrika Reguler',
                'duration' => 3,
                'price' => 4000,
                'machines' => ['ST'],
            ],
            [
                'name' => 'Setrika Express',
                'duration' => 1,
                'price' => 8000,
                'machines' => ['ST'],
            ],
        ];

        foreach ($services as $payload) {
            $service = Service::create([
                'name' => $payload['name'],
                'duration' => $payload['duration'],
                'price' => $payload['price'],
            ]);

            foreach ($payload['machines'] as $machine) {
                $service->machines()->attach($machines[$machine]);
            }
        }
    }
}
