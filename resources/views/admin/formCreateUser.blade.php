@extends('layouts.simple')

@section('title', 'Admin')

@section('content')
    <div class="w-md  p-8 rounded-xl shadow-lg w-full max-h-screen p-4 ">
        <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center pb-4">
            Formulaire d'Enregistrement
        </h2>
        <form class="space-y-5" action="{{route('admim.enregistrement')}}" method="post">
            @csrf
            <div class=" gap-4">
                <div class="">
                @error('name')
                    <p class="text-red-500 text-sm mb-2">{{ $message }}</p>
                @enderror
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nom *</label>
                    <input id="nom" name="name"  type="text" 
                    class="w-full px-4 py-2 border border-gray-300 rouded-lg focus:ring-blue-500 focus:border-blue-500 outline-none transition">
                </div>
                
                <div class="">
                @error('lastName')
                    <p class="text-red-500 text-sm mb-2">{{ $message }}</p>
                @enderror
                    <label for="lastName" class="block text-sm font-medium text-gray-700 mb-1">Postnom </label>
                    <input id="nom" name="firstName"  type="text" 
                    class="w-full px-4 py-2 border border-gray-300 rouded-lg focus:ring-blue-500 focus:border-blue-500 outline-none transition">
                </div>

                <div class="">
                @error('firstName')
                    <p class="text-red-500 text-sm mb-2">{{ $message }}</p>
                @enderror
                    <label for="firstName" class="block text-sm font-medium text-gray-700 mb-1">Prenom *</label>
                    <input id="nom" name="lastName"  type="text" 
                    class="w-full px-4 py-2 border border-gray-300 rouded-lg focus:ring-blue-500 focus:border-blue-500 outline-none transition">
                </div>
            </div>

            <div class="">
                <div>
                @error('email')
                    <p class="text-red-500 text-sm mb-2">{{ $message }}</p>
                @enderror
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Adresse Email *</label>
                    <input type="email" id="email" name="email"  placeholder="example@gmail.com"
                        class="w-full px-4 py-2 border border-gray-300 focus:ring-blue-500 outline-none transit
                    ">
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Mot de passe *</label>
                    <input type="password" id="password" name="password"  
                        class="w-full px-4 py-2 border border-gray-300 focus:ring-blue-500 outline-none transit
                    ">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <label for="" class="block text-sm font-medium text-gray-700 mb-1">Matricule *</label>
                <input type="text" id="matricule" name="matricule" placeholder="ACC-565X"  class="w-full px 4 py-2 border border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue outline-none transition">
            </div>

            <div>
                <label for="" class="block text-sm font-medium text-gray-700 mb-1">Role *</label>
                <select name="role" id="role"  class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline transition">
                    <option value="" disabled selected>Choisir un role</option>
                    <option value="agent-de-security" >Sécurité</option>
                    <option value="superviseur" >Superviseur</option>
                </select>
            </div>

            <div class="pt-4">
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 px-4  rounded-lg shadow transition duration-200  ease-in-out transform hover:-translate-y-0.5">
                    Enregistrer l'utilisateur
                </button>
            </div>
        </form>
    </div>
    
@endsection
