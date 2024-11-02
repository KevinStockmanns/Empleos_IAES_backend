<?php

namespace App\Services;

use App\Models\Horario;

class HorarioService
{
    public function buscarORegistrar($data): Horario
    {
        $dias = $data['dias'] ?? '1,2,3,4,5';

        $diasArray = array_unique(explode(',', $dias));
        sort($diasArray);
        $diasOrdenados = implode(',', $diasArray);

        $horario = Horario::where('desde', $data['desde'])
            ->where('hasta', $data['hasta'])
            ->where('dias', $diasOrdenados)
            ->first();

        if (!$horario) {
            $horario = Horario::create([
                'desde' => $data['desde'],
                'hasta' => $data['hasta'],
                'dias' => $diasOrdenados
            ]);
        }

        return $horario;
    }
}
