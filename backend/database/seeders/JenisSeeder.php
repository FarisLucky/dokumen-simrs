<?php

namespace Database\Seeders;

use App\Models\Jenis;
use Illuminate\Database\Seeder;

class JenisSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $jenis = [
            [
                'nama' => 'RADIOLOGI',
                'unit' => ['PENDAFTARAN', 'RAWAT JALAN', 'RAWAT INAP'],
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'LABORATORIUM',
                'unit' => ['PENDAFTARAN', 'RAWAT JALAN', 'RAWAT INAP'],
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'LAINNYA',
                'unit' => ['PENDAFTARAN', 'RAWAT JALAN', 'RAWAT INAP'],
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($jenis as $j) {
            Jenis::create($j);
        }
    }
}
