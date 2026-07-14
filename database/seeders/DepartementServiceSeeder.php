<?php

namespace Database\Seeders;

use App\Models\Departement;
use App\Models\Service;
use Illuminate\Database\Seeder;

class DepartementServiceSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            'Direction Générale' => [
                'Secrétariat',
                'Communication',
            ],
            'Département MMG' => [
                'Sécurité',
                'IT',
                'RH',
                'Logistique',
                'Mine',
            ],
            'Informatique' => [
                'Support technique',
                'Développement',
            ],
            'Ressources Humaines' => [
                'Recrutement',
                'Formation',
            ],
            'Sous-traitance' => [
                'Maintenance',
                'Transport',
                'Construction',
                'Nettoyage',
                'Fourniture',
            ],
        ];

        $entreprises = config('entreprises', []);
        $nomsGardes = array_keys($data);

        foreach ($data as $departementNom => $services) {
            $departement = Departement::firstOrCreate(['nom' => $departementNom]);

            foreach ($services as $serviceNom) {
                $departement->services()->firstOrCreate(['nom' => $serviceNom]);
            }

            $departement->services()
                ->whereNotIn('nom', $services)
                ->delete();
        }

        if (! empty($entreprises)) {
            Service::whereIn('nom', $entreprises)->delete();
        }

        // Garder uniquement les départements définis dans ce seeder
        Departement::whereNotIn('nom', $nomsGardes)->each(function (Departement $departement) {
            $departement->services()->delete();
            $departement->delete();
        });
    }
}
