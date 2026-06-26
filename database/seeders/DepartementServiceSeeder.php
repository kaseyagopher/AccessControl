<?php

namespace Database\Seeders;

use App\Models\Departement;
use Illuminate\Database\Seeder;

class DepartementServiceSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            'Direction Générale' => ['Secrétariat', 'Communication'],
            'Ressources Humaines' => ['Recrutement', 'Formation'],
            'Informatique' => ['Support technique', 'Développement'],
        ];

        foreach ($data as $departementNom => $services) {
            $departement = Departement::firstOrCreate(['nom' => $departementNom]);

            foreach ($services as $serviceNom) {
                $departement->services()->firstOrCreate(['nom' => $serviceNom]);
            }
        }
    }
}
