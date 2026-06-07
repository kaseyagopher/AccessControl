@extends("base")

@section('title', 'se connecter')

@section("content")

    <div class="bg-slate-100 flex items-center justify-center min-h-screen">
        @if($errors->any())
            <div class="bg-red-100 border border-red-700 text-red-700  px-4 py-3 rounded relative h-16" role="alert">
            <strong class="font-bold">Eurreur </strong> 
            <span class="block sm:inline">{{$errors->first()}} </span>
        @endif
        <div class="bg-white p-8 rounded-2xl shadow-md w-full max-w-md">
            <div class="text-center mb-8">
                <h1 class="text-2xl font-bold text-slate-800">Contrôle d'Accès</h1>
                <p class="text-sm text-slate-500 mt-1">Connectez-vous pour accéder à votre espace</p>
            </div>

            <form action="{{route('login.submit')}}" method="POST" class="space-y-6">
                @csrf 

                <div>
                    <label for="email" class="block text-sm font-medium text-slate-700 mb-1">Adresse Email</label>
                    <input type="email" name="email" id="email"  value="{{old('email')}}"
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-hidden focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                        placeholder="exemple@domaine.com">

                        @error('email')
                            <span class="text-red-500">{{$message}}</span>
                        @enderror
                </div>

                <div>
                    <div class="flex justify-between items-center mb-1">
                        <label for="password" class="block text-sm font-medium text-slate-700">Mot de passe</label>
                        <a href="#" class="text-xs text-blue-600 hover:underline">Mot de passe oublié ?</a>
                    </div>
                    <input type="password" name="password" id="password"  
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-hidden focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                        placeholder="••••••••">
                        @error('password')
                            <span class="text-red-500">{{$message}}</span>
                        @enderror

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
                        Se connecter
                    </button>
                </div>
            </form>
        </div>
    </div>


@endsection