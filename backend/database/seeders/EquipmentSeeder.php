<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Equipment;
use Carbon\Carbon;
use Illuminate\Support\Str;

class EquipmentSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        // 100 Unique Items mapped to 15 Categories with KES pricing
        $equipmentPool = [
            // 1: Strength Training
            ['name' => 'Monster Power Rack', 'brand' => 'Rogue Fitness', 'cat' => 1, 'price' => 350000],
            ['name' => 'Olympic Barbell 20kg', 'brand' => 'Eleiko', 'cat' => 1, 'price' => 135000],
            ['name' => 'Leg Press 45 Degree', 'brand' => 'Hammer Strength', 'cat' => 1, 'price' => 550000],
            ['name' => 'Dumbbell Set (2-50kg)', 'brand' => 'ZIVA', 'cat' => 1, 'price' => 650000],
            ['name' => 'Smith Machine', 'brand' => 'Technogym', 'cat' => 1, 'price' => 700000],
            ['name' => 'Lat Pulldown Machine', 'brand' => 'Life Fitness', 'cat' => 1, 'price' => 450000],
            ['name' => 'Seated Row', 'brand' => 'Hammer Strength', 'cat' => 1, 'price' => 420000],

            // 2: Cardio & Endurance
            ['name' => 'Commercial Treadmill T80', 'brand' => 'Life Fitness', 'cat' => 2, 'price' => 1250000],
            ['name' => 'Concept2 RowErg', 'brand' => 'Concept2', 'cat' => 2, 'price' => 220000],
            ['name' => 'StairMaster Gauntlet', 'brand' => 'Core Health', 'cat' => 2, 'price' => 950000],
            ['name' => 'Spin Bike S11', 'brand' => 'Keiser', 'cat' => 2, 'price' => 320000],
            ['name' => 'Curve Treadmill', 'brand' => 'Woodway', 'cat' => 2, 'price' => 1500000],
            ['name' => 'Arc Trainer', 'brand' => 'Cybex', 'cat' => 2, 'price' => 850000],

            // 3: HIIT & Circuit
            ['name' => 'Assault Air Bike', 'brand' => 'Assault Fitness', 'cat' => 3, 'price' => 145000],
            ['name' => 'SkiErg', 'brand' => 'Concept2', 'cat' => 3, 'price' => 185000],
            ['name' => 'Sled Prowler', 'brand' => 'Rogue Fitness', 'cat' => 3, 'price' => 75000],
            ['name' => 'Battle Ropes (50ft)', 'brand' => 'Living Fit', 'cat' => 3, 'price' => 25000],
            ['name' => 'Plyo Box Set (Soft)', 'brand' => 'Rogue Fitness', 'cat' => 3, 'price' => 85000],
            ['name' => 'Agility Ladder', 'brand' => 'SKLZ', 'cat' => 3, 'price' => 6500],

            // 4: Yoga & Mobility
            ['name' => 'Yoga Mat Premium', 'brand' => 'Manduka', 'cat' => 4, 'price' => 18000],
            ['name' => 'Yoga Block (Cork)', 'brand' => 'Gaiam', 'cat' => 4, 'price' => 4500],
            ['name' => 'Yoga Wheel', 'brand' => 'UpCircleSeven', 'cat' => 4, 'price' => 9500],
            ['name' => 'Stretching Strap', 'brand' => 'OPTP', 'cat' => 4, 'price' => 3500],
            ['name' => 'Bolster Cushion', 'brand' => 'Hugger Mugger', 'cat' => 4, 'price' => 12000],

            // 5: CrossFit & Functional
            ['name' => 'Gymnastic Rings (Wood)', 'brand' => 'Rogue Fitness', 'cat' => 5, 'price' => 18000],
            ['name' => 'Kettlebell Set (8-32kg)', 'brand' => 'Kettlebell Kings', 'cat' => 5, 'price' => 195000],
            ['name' => 'Wall Ball (9kg)', 'brand' => 'Rogue Fitness', 'cat' => 5, 'price' => 17500],
            ['name' => 'Sandbag (Trainable)', 'brand' => 'Brute Force', 'cat' => 5, 'price' => 28000],
            ['name' => 'GHD Machine', 'brand' => 'Rogue Fitness', 'cat' => 5, 'price' => 145000],

            // 6: Combat Sports
            ['name' => 'Heavy Bag (100lb)', 'brand' => 'Everlast', 'cat' => 6, 'price' => 65000],
            ['name' => 'Thai Pads (Pair)', 'brand' => 'Fairtex', 'cat' => 6, 'price' => 22000],
            ['name' => 'Grappling Dummy', 'brand' => 'Century', 'cat' => 6, 'price' => 55000],
            ['name' => 'Speed Bag Platform', 'brand' => 'Title Boxing', 'cat' => 6, 'price' => 45000],
            ['name' => 'Boxing Ring (Floor)', 'brand' => 'Rival', 'cat' => 6, 'price' => 850000],

            // 7: Personal Training
            ['name' => 'Adjustable Dumbbells', 'brand' => 'PowerBlock', 'cat' => 7, 'price' => 75000],
            ['name' => 'TRX Pro4 System', 'brand' => 'TRX', 'cat' => 7, 'price' => 38000],
            ['name' => 'Portable Training Bench', 'brand' => 'Bowflex', 'cat' => 7, 'price' => 55000],
            ['name' => 'Fitness Tracking Kiosk', 'brand' => 'Fitbench', 'cat' => 7, 'price' => 280000],

            // 8: Aquatics
            ['name' => 'Pool Pace Clock', 'brand' => 'Competitor', 'cat' => 8, 'price' => 95000],
            ['name' => 'Aqua Dumbbells (Pair)', 'brand' => 'TYR', 'cat' => 8, 'price' => 12000],
            ['name' => 'Lane Rope Reel', 'brand' => 'AntiWave', 'cat' => 8, 'price' => 185000],
            ['name' => 'Aquatic Treadmill', 'brand' => 'HydroWorx', 'cat' => 8, 'price' => 2200000],
            ['name' => 'Kickboard Stack', 'brand' => 'Speedo', 'cat' => 8, 'price' => 35000],

            // 9: Pilates & Core
            ['name' => 'Pilates Reformer V2', 'brand' => 'Merrithew', 'cat' => 9, 'price' => 650000],
            ['name' => 'Pilates Cadillac', 'brand' => 'Stott Pilates', 'cat' => 9, 'price' => 850000],
            ['name' => 'Wunda Chair', 'brand' => 'Balanced Body', 'cat' => 9, 'price' => 195000],
            ['name' => 'Stability Ball (65cm)', 'brand' => 'TheraBand', 'cat' => 9, 'price' => 6500],

            // 10: Senior Fitness
            ['name' => 'Low-Impact Stepper', 'brand' => 'Technogym', 'cat' => 10, 'price' => 320000],
            ['name' => 'Resistance Chair', 'brand' => 'VQ ActionCare', 'cat' => 10, 'price' => 55000],
            ['name' => 'Balance Pad', 'brand' => 'Airex', 'cat' => 10, 'price' => 11000],

            // 11: Youth Athletics
            ['name' => 'Junior Olympic Bar', 'brand' => 'Rogue Fitness', 'cat' => 11, 'price' => 45000],
            ['name' => 'Youth Plyo Box', 'brand' => 'Rep Fitness', 'cat' => 11, 'price' => 35000],
            ['name' => 'Agility Hurdles', 'brand' => 'SKLZ', 'cat' => 11, 'price' => 14000],

            // 12: Recovery & Wellness
            ['name' => 'Infrared Sauna (4-Person)', 'brand' => 'Clearlight', 'cat' => 12, 'price' => 950000],
            ['name' => 'Commercial Cold Plunge', 'brand' => 'Plunge', 'cat' => 12, 'price' => 750000],
            ['name' => 'Hypervolt 2 Pro', 'brand' => 'Hyperice', 'cat' => 12, 'price' => 65000],
            ['name' => 'Compression Boots', 'brand' => 'Normatec', 'cat' => 12, 'price' => 145000],
            ['name' => 'Vibration Plate', 'brand' => 'Power Plate', 'cat' => 12, 'price' => 550000],

            // 13: Nutrition Coaching
            ['name' => 'InBody 270 Body Comp', 'brand' => 'InBody', 'cat' => 13, 'price' => 850000],
            ['name' => 'Medical Grade Tape', 'brand' => 'Seca', 'cat' => 13, 'price' => 3500],
            ['name' => 'Skinfold Calipers', 'brand' => 'Harpenden', 'cat' => 13, 'price' => 55000],

            // 14: Dance Fitness
            ['name' => 'Dance Floor Mirror', 'brand' => 'Glassless Mirror', 'cat' => 14, 'price' => 125000],
            ['name' => 'Wall-Mounted Barre', 'brand' => 'Vita Vibe', 'cat' => 14, 'price' => 75000],
            ['name' => 'PA Sound System', 'brand' => 'JBL Pro', 'cat' => 14, 'price' => 220000],

            // 15: Open Gym Access
            ['name' => 'Digital Locker Bank', 'brand' => 'Salsbury', 'cat' => 15, 'price' => 450000],
            ['name' => 'Elkay Refill Station', 'brand' => 'Elkay', 'cat' => 15, 'price' => 280000],
            ['name' => 'AED Plus Defibrillator', 'brand' => 'Zoll', 'cat' => 15, 'price' => 320000],
            ['name' => 'Sanitization Stand', 'brand' => 'Purell', 'cat' => 15, 'price' => 45000],
        ];

        // Ensure we hit exactly 100 unique items by padding with generic variations
        $brands = ['Matrix', 'Technogym', 'Star Trac', 'Body-Solid', 'Precor'];
        while (count($equipmentPool) < 100) {
            $cat = rand(1, 15);
            $equipmentPool[] = [
                'name' => 'Utility Accessory ' . (count($equipmentPool) + 1),
                'brand' => $brands[array_rand($brands)],
                'cat' => $cat,
                'price' => rand(25000, 300000)
            ];
        }

        $statuses = ['active', 'active', 'active', 'under_maintenance', 'faulty', 'decommissioned'];
        $locations = ['Level 1 - Main', 'Level 2 - Studio', 'North Wing', 'South Wing', 'Mezzanine', 'Outdoor Deck'];

        for ($gymId = 1; $gymId <= 5; $gymId++) {
            foreach ($equipmentPool as $index => $item) {

                // Realism: Weighted Status Logic
                $rand = rand(1, 100);
                if ($rand <= 85) $status = 'active';
                elseif ($rand <= 92) $status = 'under_maintenance';
                elseif ($rand <= 98) $status = 'faulty';
                else $status = 'decommissioned';

                $notes = null;
                $hazard = false;

                if ($status === 'under_maintenance') {
                    $notes = 'Bimonthly preventative maintenance check.';
                } elseif ($status === 'faulty') {
                    $notes = 'Reported mechanical noise or wear. Inspection pending.';
                    $hazard = rand(1, 10) > 7;
                } elseif ($status === 'decommissioned') {
                    $notes = 'End of lifecycle. Scheduled for salvage/removal.';
                }

                $purchaseDate = $now->copy()->subDays(rand(30, 1800));
                $lastService = $now->copy()->subDays(rand(1, 180));

                Equipment::updateOrCreate(
                    ['manufacturer_serial_no' => strtoupper(substr($item['brand'], 0, 3)) . "-" . Str::random(6) . "-G" . $gymId . "-" . $index],
                    [
                        'gym_id' => $gymId,
                        'category_id' => $item['cat'],
                        'name' => $item['name'],
                        'brand' => $item['brand'],
                        'model_number' => 'SERIES-' . rand(1, 10),
                        'usage_notes' => $notes,
                        'asset_code' => "G{$gymId}-" . strtoupper(substr(Str::slug($item['name']), 0, 4)) . "-" . str_pad($index + 1, 3, '0', STR_PAD_LEFT),
                        'purchase_price' => $item['price'],
                        'purchase_date' => $purchaseDate,
                        'warranty_expiration' => $purchaseDate->copy()->addYears(rand(1, 3)),
                        'status' => $status,
                        'is_safety_hazard' => $hazard,
                        'last_serviced_at' => $lastService,
                        'next_service_due_at' => $lastService->copy()->addDays(180),
                        'service_interval_days' => 180,
                        'floor_location' => $locations[array_rand($locations)],
                    ]
                );
            }
        }

        $this->command->info('Seeded 500 items across 15 categories with realistic Kenyan Shilling (KES) pricing.');
    }
}
