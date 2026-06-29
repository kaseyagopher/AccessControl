<?php

namespace Database\Seeders;

use App\Models\Departement;
use Illuminate\Database\Seeder;

class DepartementServiceSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            'Sous-traitance' => [
                'ITM MMG',
                'Mexco',
                'SSL',
                'IFS',
                'GTI (Godwin trading for Investment sarl)',
                'SDI',
                'Gymtec',
            ],
            'Département MMG' => [
                'Sécurité',
                'IT',
                'RH',
                'Social',
                'Safety',
                'Mine',
                'Logistique',
                'Store',
                'Usine',
            ],
        ];

        foreach ($data as $departementNom => $services) {
            $departement = Departement::firstOrCreate(['nom' => $departementNom]);

            foreach ($services as $serviceNom) {
                $departement->services()->firstOrCreate(['nom' => $serviceNom]);
            }
        }
    }
}
