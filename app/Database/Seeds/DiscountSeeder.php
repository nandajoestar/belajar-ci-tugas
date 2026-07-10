<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DiscountSeeder extends Seeder
{
    public function run()
    {
        // Tanggal mulai: 2026-07-03 (hari migration dibuat)
        // 10 data untuk 10 hari berbeda, tidak ada tanggal yang sama
        $data = [
            [
                'tanggal'    => '2026-07-03',
                'nominal'    => 100000,
                'created_at' => '2026-07-03 02:57:52',
                'updated_at' => '2026-07-03 02:57:52',
                'deleted_at' => null,
            ],
            [
                'tanggal'    => '2026-07-07',
                'nominal'    => 100000,
                'created_at' => '2026-07-03 02:57:52',
                'updated_at' => '2026-07-03 02:57:52',
                'deleted_at' => null,
            ],
            [
                'tanggal'    => '2026-07-08',
                'nominal'    => 200000,
                'created_at' => '2026-07-03 02:57:52',
                'updated_at' => '2026-07-03 02:57:52',
                'deleted_at' => null,
            ],
            [
                'tanggal'    => '2026-07-09',
                'nominal'    => 150000,
                'created_at' => '2026-07-03 02:57:52',
                'updated_at' => '2026-07-03 02:57:52',
                'deleted_at' => null,
            ],
            [
                'tanggal'    => '2026-07-10',
                'nominal'    => 250000,
                'created_at' => '2026-07-03 02:57:52',
                'updated_at' => '2026-07-03 02:57:52',
                'deleted_at' => null,
            ],
            [
                'tanggal'    => '2026-07-11',
                'nominal'    => 300000,
                'created_at' => '2026-07-03 02:57:52',
                'updated_at' => '2026-07-03 02:57:52',
                'deleted_at' => null,
            ],
            [
                'tanggal'    => '2026-07-12',
                'nominal'    => 300000,
                'created_at' => '2026-07-03 02:57:52',
                'updated_at' => '2026-07-03 02:57:52',
                'deleted_at' => null,
            ],
            [
                'tanggal'    => '2026-07-13',
                'nominal'    => 300000,
                'created_at' => '2026-07-03 02:57:52',
                'updated_at' => '2026-07-03 02:57:52',
                'deleted_at' => null,
            ],
            [
                'tanggal'    => '2026-07-14',
                'nominal'    => 300000,
                'created_at' => '2026-07-03 02:57:52',
                'updated_at' => '2026-07-03 02:57:52',
                'deleted_at' => null,
            ],
            [
                'tanggal'    => '2026-07-15',
                'nominal'    => 300000,
                'created_at' => '2026-07-03 02:57:52',
                'updated_at' => '2026-07-03 02:57:52',
                'deleted_at' => null,
            ],
        ];

        $this->db->table('discount')->insertBatch($data);
    }
}
