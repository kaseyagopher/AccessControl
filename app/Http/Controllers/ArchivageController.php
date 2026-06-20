<?php

namespace App\Http\Controllers;

use App\Services\ArchivageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArchivageController extends Controller
{
    public function admin(Request $request)
    {
        $demandes = ArchivageService::query($request)->get();
        $annees = ArchivageService::annees();

        return view('admin.archivages', compact('demandes', 'annees'));
    }

    public function agent(Request $request)
    {
        $demandes = ArchivageService::query($request)->get();
        $annees = ArchivageService::annees();

        return view('agent-de-security.archivages', compact('demandes', 'annees'));
    }

    public function superviseur(Request $request)
    {
        $superviseurId = Auth::id();
        $demandes = ArchivageService::query($request, $superviseurId)->get();
        $annees = ArchivageService::annees($superviseurId);

        return view('superviseur.demandes.archivages', compact('demandes', 'annees'));
    }
}
