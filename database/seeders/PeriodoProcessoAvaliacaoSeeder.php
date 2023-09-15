<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PeriodoProcessoAvaliacaoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('periodos_processo')->insert([
            [
                'id' => 1,
                'nome' => '1° Período',
                'dia_inicial' => 1,
                'dia_final' => 180
            ],
            [
                'id' => 2,
                'nome' => '2° Período',
                'dia_inicial' => 181,
                'dia_final' => 360
            ],
            [
                'id' => 3,
                'nome' => '3° Período',
                'dia_inicial' => 361,
                'dia_final' => 540
            ],
            [
                'id' => 4,
                'nome' => '4° Período',
                'dia_inicial' => 541,
                'dia_final' => 720
            ],
            [
                'id' => 5,
                'nome' => '5° Período',
                'dia_inicial' => 721,
                'dia_final' => 900
            ],
            [
                'id' => 6,
                'nome' => '6° Período',
                'dia_inicial' => 901,
                'dia_final' => 1080
            ],
        ]);
    }
}
