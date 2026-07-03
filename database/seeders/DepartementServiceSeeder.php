<?php

namespace Database\Seeders;

use App\Models\Departement;
use Illuminate\Database\Seeder;

class DepartementServiceSeeder extends Seeder
{
    public function run(): void
    {
        $entreprises = config('entreprises');

        $data = [
            'Sous-traitance' => $entreprises,
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
