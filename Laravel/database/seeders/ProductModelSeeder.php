<?php

namespace Database\Seeders;

use App\Models\ProductModel;
use Illuminate\Database\Seeder;

class ProductModelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $models = ['S240 Lawn Tractor', 'X350 Riding Tractor', 'X590 Lawn Tractor', 'Z345R Zero‑Turn', 'Z530M Zero‑Turn', '100 Series Riding Mower', '300 Series Mower', '500 Series Mower', '700 Series Mower', 'E180 Riding Mower', 'Automower 315X', 'Automower 430X', 'LC 247 Push Mower', 'LC 121P Push Mower', 'Z254 Zero‑Turn', 'HU625H Walk‑Behind Mower', 'LC 221RH Rear‑Handle Mower', 'YTH18542 Riding Tractor', 'HU800AWDH All‑Wheel Drive Mower', '150BT Blower', 'Recycler Personal Pace 22"', 'Timemaster 30"', 'Z Master 6000', 'GrandStand 3000', '20332 Recycler', '60V Max Cordless Mower', 'SmartStow 22111 Mower', 'MX5075D Commercial Zero‑Turn', '521QZ Commercial Mower', '21380 Recycler', 'XT1 LT42 Riding Tractor', 'XT2 LX46 Riding Tractor', 'Ultima ZTS1 Zero‑Turn', 'Ultima ZT2 Zero‑Turn', 'CC 30 Push Mower', 'CC 42 Push Mower', 'RZT S 46 Zero‑Turn', 'CC 47 OH Stand‑On Mower', 'XT3 GSX Riding Tractor', 'LT1042 Riding Tractor', 'MS 170 Chainsaw', 'MS 271 Chainsaw', 'MS 391 Chainsaw', 'FS 56 RC‑E Trimmer', 'BG 56 Blower', 'BR 600 Backpack Blower', 'FSA 85 Battery Trimmer', 'MS 661 Chainsaw', 'FS 131 Trimmer', 'HS 45 Hedger', 'L2501 Compact Tractor', 'M7060 Utility Tractor', 'M8‑1 Series Tractor', 'B2601 Compact Tractor', 'BX2680 Sub‑Compact Tractor', 'Z726X Zero‑Turn', 'SVL97‑2s Skid Steer', 'U55‑5 Mini Excavator', 'K008‑3 Mini Excavator', 'RTV‑X1100C UTV', '320 GC Medium Excavator', '323‑07 Excavator', 'D6T Dozer', '854K Wheel Tractor Scraper', '930M Wheel Loader', '740 GC Articulated Truck', '259D3 Skid Steer', '160K Motor Grader', '336D2 Excavator', '988K Wheel Loader', 'PC1250‑7 Excavator', 'PC210LC‑11 Excavator', 'WA380‑8 Loader', 'D51PX‑24 Dozer', 'HD605‑8 Articulated Truck', 'PC55MR‑5 Mini Excavator', 'WA800‑8 Loader', 'GD655‑6 Motor Grader', 'PW148‑11 Wheeled Excavator', 'D61EX‑24 Dozer', 'EC220E Excavator', 'L90H Loader', 'ECR40D Compact Excavator', 'A25G Articulated Hauler', 'SD75B Soil Compactor', 'EWR150E Wheeled Excavator', 'L120H Loader', 'EC300E Excavator', 'S60 Compactor', 'MC115C Asphalt Paver', 'SY215C Excavator', 'SCC830E Crane', 'STC900 Tower Crane', 'SDR260 Motor Grader', 'XE215C Excavator', 'LW500K Loader', 'XT870 Dozer', '3‑Ton Dump Truck', 'QY50K Crane', 'RHB Series Roller', 'HRX217 Push Mower', 'HRC216 Push Mower', 'PB‑580T Backpack Blower', 'CS‑590 Chainsaw', '303447 Lawn Mower Engine', 'XML08R1 Cordless Mower', 'Rotak 43 Li Mower', 'PE‑2620 Pole Hedge Trimmer', 'Dingo TX 427 Compact Loader', 'MGCM‑23 Commercial Mower'];

        foreach ($models as $modelName) {
            ProductModel::create([
                'product_brand_id' => rand(1, 20),
                'name'             => $modelName,
            ]);
        }
    }
}
