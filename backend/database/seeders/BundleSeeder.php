<?php

namespace Database\Seeders;

use App\Models\Bundle;
use App\Models\Category;
use App\Models\Gym;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BundleSeeder extends Seeder
{
    public function run(): void
    {
        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
        Bundle::truncate();
        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();

        $gyms = Gym::all();
        $categories = Category::all();

        if ($categories->isEmpty()) {
            $this->command->error('Seed categories first!');
            return;
        }

        $times = ['06:00:00', '08:30:00', '12:00:00', '17:30:00', '19:00:00'];

        // Global bundles (gym_id = NULL)
        $globalBundles = [
            [
                'category' => 'Open Gym Access',
                'name' => 'Daily Pass',
                'gym_zone' => 'All Zones',
                'start_time' => '05:00:00',
                'session_duration' => 1440, // 24 hours
                'price' => 500.00,
                'currency' => 'KES',
                'description' => 'Unlimited Gym Access • Premium Equipment • Complimentary Lockers • High-speed Guest WiFi',
            ],
            [
                'category' => 'Open Gym Access',
                'name' => 'One Month Pass',
                'gym_zone' => 'All Zones',
                'start_time' => '05:00:00',
                'session_duration' => 43200, // 30 days
                'price' => 5500.00,
                'currency' => 'KES',
                'description' => 'Unlimited Gym Access • Premium Equipment • Complimentary Lockers • High-speed Guest WiFi',
            ],
            [
                'category' => 'Open Gym Access',
                'name' => 'Three Months Pass',
                'gym_zone' => 'All Zones',
                'start_time' => '05:00:00',
                'session_duration' => 129600, // 90 days
                'price' => 15000.00,
                'currency' => 'KES',
                'description' => 'Unlimited Gym Access • Premium Equipment • Complimentary Lockers • High-speed Guest WiFi',
            ],
            [
                'category' => 'Open Gym Access',
                'name' => 'Six Months Pass',
                'gym_zone' => 'All Zones',
                'start_time' => '05:00:00',
                'session_duration' => 259200, // 180 days
                'price' => 25000.00,
                'currency' => 'KES',
                'description' => 'Unlimited Gym Access • Premium Equipment • Complimentary Lockers • High-speed Guest WiFi',
            ],
            [
                'category' => 'Open Gym Access',
                'name' => '12 Month Elite Pass',
                'gym_zone' => 'All Zones',
                'start_time' => '05:00:00',
                'session_duration' => 525600, // 365 days
                'price' => 45000.00,
                'currency' => 'KES',
                'description' => 'Unlimited Gym Access • Premium Equipment • Complimentary Lockers • High-speed Guest WiFi • Billed Annually',
            ],
        ];

        // Seed global bundles
        foreach ($globalBundles as $data) {
            $category = $this->findCategory($categories, $data['category']);
            if (!$category) {
                $this->command->warn("Missing category: {$data['category']}");
                continue;
            }

            $slug = Str::slug($data['name']);

            Bundle::updateOrCreate(
                ['gym_id' => null, 'slug' => $slug],
                [
                    'category_id' => $category->id,
                    'name' => $data['name'],
                    'slug' => $slug,
                    'description' => $data['description'],
                    'gym_zone' => $data['gym_zone'],
                    'start_time' => $data['start_time'],
                    'session_duration' => $data['session_duration'],
                    'price' => $data['price'],
                    'currency' => $data['currency'],
                ]
            );
        }

        $this->command->info('Bundles seeded successfully! (Global bundles have gym_id = NULL)');
    }

    private function findCategory($categories, string $name): ?Category
    {
        return $categories->firstWhere('name', $name);
    }

    private function randomTime(array $times): string
    {
        return $times[array_rand($times)];
    }
}
