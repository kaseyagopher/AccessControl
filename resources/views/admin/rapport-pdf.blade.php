<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Rapport visites — MMG</title>
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    <link rel="alternate icon" href="{{ asset('favicon.ico') }}" sizes="any">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: Inter, sans-serif; padding: 2rem; color: #1e293b; }
        h1 { font-size: 1.5rem; margin-bottom: 0.5rem; }
        p { color: #64748b; margin-bottom: 1.5rem; }
        table { width: 100%; border-collapse: collapse; font-size: 0.875rem; }
        th, td { border: 1px solid #e2e8f0; padding: 0.5rem 0.75rem; text-align: left; }
        th { background: #f8fafc; font-weight: 600; }
        tr:nth-child(even) { background: #f8fafc; }
        button { margin-top: 1.5rem; padding: 0.5rem 1rem; background: #E31B23; color: white; border: none; border-radius: 0.5rem; cursor: pointer; }
        @media print { button { display: none; } }
    </style>
</head>
<body>
    <h1>Rapport des visites</h1>
    <p>Période : {{ $debut }} au {{ $fin }}</p>
    <table>
        <thead>
            <tr><th>Visiteur</th><th>Entreprise</th><th>Superviseur</th><th>Date</th><th>Statut</th><th>Motif</th></tr>
        </thead>
        <tbody>
            @foreach ($demandes as $d)
                <tr>
                    <td>{{ $d->visiteur->nomComplet() }}</td>
                    <td>{{ $d->visiteur->entreprise }}</td>
                    <td>{{ $d->superviseur->name }}</td>
                    <td>{{ $d->date_prevue->format('d/m/Y') }}</td>
                    <td>{{ $d->statut }}</td>
                    <td>{{ $d->motif }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <button onclick="window.print()">Imprimer / Enregistrer en PDF</button>
</body>
</html>
