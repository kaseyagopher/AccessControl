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
            'Sous-traitance' => [
                'Maintenance',
                'Transport',
                'Construction',
                'Nettoyage',
                'Fourniture',
            ],
            'Département MMG' => [
                'Sécurité',
                'IT',
                'RH',
                'Logistique',
                'Mine',
            ],
        ];

        $entreprises = config('entreprises', []);

        foreach ($data as $departementNom => $services) {
            $departement = Departement::firstOrCreate(['nom' => $departementNom]);

            foreach ($services as $serviceNom) {
                $departement->services()->firstOrCreate(['nom' => $serviceNom]);
            }

            // Retirer les anciens services hors liste (ex. noms d'entreprises)
            // nullOnDelete : les VNF liées perdent le service_id
            $departement->services()
                ->whereNotIn('nom', $services)
                ->delete();
        }

        // Sécurité : supprimer tout service restant nommé comme une entreprise
        if (! empty($entreprises)) {
            Service::whereIn('nom', $entreprises)->delete();
        }
    }
}
