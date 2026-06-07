@extends('base')

@section('title', 'Inscription')

@section('content')

<div class="bg-slate-100 flex items-center justify-center min-h-screen">
    @if(session('success'))
        <div class="bg-red-100 border-green-400 text-green-700 px-4 py-3 rounded relative " role="alert">
            <strong class="font-bold">Success </strong>
            <span class="block sm:inline "> {{session('success')}} </span>
        </div>

    @endif
        <div class="bg-white p-8 rounded-2xl shadow-md w-full max-w-md">
            <div class="text-center mb-8">
                <h1 class="text-2xl font-bold text-slate-800">Contrôle d'Accès</h1>
                <p class="text-sm text-slate-500 mt-1">S'inscrire</p>
            </div>
        
    
        <form action="{{route('registration.register')}}" method="POST" class="space-y-6">
            @csrf 



            <div>
                <label for="name" class="block text-sm font-medium text-slate-700 mb-1">name</label>
                <input type="text" name="name" id="name" 
                    class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-hidden focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                    placeholder="Franck">
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-slate-700 mb-1">Adresse Email</label>
                <input type="email" name="email" id="email" 
                    class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-hidden focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                    placeholder="exemple@domaine.com">
            </div>

            <div>
                <label for="matricul" class="block text-sm font-medium text-slate-700 mb-1">Matricule</label>
                <input type="text" name="matricul" id="matricul" 
                    class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-hidden focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                    placeholder="MMG520">
            </div>

            <div>
                <label for="role" class="block text-sm font-medium text-slate-700 mb-1">role</label>
                <input type="text" name="role" id="role" 
                    class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-hidden focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                    placeholder="securite ou superviseur">
            </div>

            <div>
                <div class="flex justify-between items-center mb-1">
                    <label for="password" class="block text-sm font-medium text-slate-700">Mot de passe</label>
                    <a href="{{route('login')}}" class="text-xs text-blue-600 hover:underline">Deja un compte ? </a>
                </div>
                <input type="password" name="password" id="password" 
                    class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-hidden focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                    placeholder="••••••••"
                >

                <div>
                <div class="flex justify-between items-center mb-1">
                    <label for="password_confirmation" class="block text-sm font-medium text-slate-700">Confirmer votre mots de passe</label>
                </div>
                <input type="password" name="password_confirmation" id="password_confirmation"  
                    class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-hidden focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                    placeholder="••••••••"
                >


            </div>
            </div>


            

            <div class="flex items-center">
                <input type="checkbox" name="remember" id="remember" 
                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-slate-300 rounded-sm">
                <label for="remember" class="ml-2 block text-sm text-slate-600 select-none">
                    Se souvenir de moi
                </label>
            </div>


            <div class="flex items-center">
                
                <a href="{{route('register')}}" for="remember" class="ml-2 block text-sm text-slate-600 select-none">
                    S'inscrire maintenant
                </a>
            </div>

            <div>
                <button type="submit" 
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 px-4 rounded-lg shadow-sm transition-colors focus:outline-hidden focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    S'inscrire
                </button>
            </div>
        </form>
    </div>
</div>


@endsection