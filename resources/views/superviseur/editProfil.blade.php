@extends('layouts.simple')

@section('title', 'Admin')

@section('content')

<div class="w-md  p-8 rounded-xl shadow-lg w-full max-h-screen p-4 ">
        <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center pb-4">
            Formulaire  de mise a jour
        </h2>
        <form class="space-y-5" action="{{route('superviseur.settings.update')}}" method="post">
            @csrf
            @method('PUT')
            <div class=" gap-4">
                <div class="">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nom *</label>
                    <input id="nom" value="{{old('name', Auth::user()->name)}}" name="name"  type="text" 
                    class="w-full px-4 py-2 border border-gray-300 rouded-lg focus:ring-blue-500 focus:border-blue-500 outline-none transition
                    @error('name') border-red-500 focus:ring-red-500 @else border-gray-300 focus:ring-blue-500 @enderror
                    ">

                    @error('name')
                    <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                    @enderror
                    </div>
                
                <div class="">
                    <label for="lastName" class="block text-sm font-medium text-gray-700 mb-1">Postnom </label>
                    <input id="nom" value="{{ old('lastName', Auth::user()->lastName) }}"  name="lastName"  type="text" 
                    class="w-full px-4 py-2 border border-gray-300 rouded-lg focus:ring-blue-500 focus:border-blue-500 outline-none transition
                    @error('lastName') border-red-500 focus:ring-red-500 @else border-gray-300 focus:ring-blue-500 @enderror
                    ">
                    @error('lastName')
                    <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div class="">
                    <label for="firstName" class="block text-sm font-medium text-gray-700 mb-1">Prenom *</label>
                    <input id="nom" name="firstName" value="{{ old('firstName', Auth::user()->firstName)}}"  type="text" 
                    class="w-full px-4 py-2 border border-gray-300 rouded-lg focus:ring-blue-500 focus:border-blue-500 outline-none transition 
                    @error('firstName') border-red-500 focus:ring-red-500 @else border-gray-300 focus:ring-blue-500 @enderror 
                    ">

                    @error('firstName')
                        <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                    @enderror
            </div>

            <div class="">
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Adresse Email *</label>
                    <input type="email" value="{{old('emaiil', Auth::user()->email) }}" id="email" name="email"  placeholder="example@gmail.com"
                        class="w-full px-4 py-2 border border-gray-300 focus:ring-blue-500 outline-none transit 
                        @error('email') border-red-500 focus:ring-red-500 @else border-gray-300 focus:ring-blue-500 @enderror
                    ">
                    @error('email')
                        <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Mot de passe *</label>
                    <input type="password" id="password" name="password"  
                        class="w-full px-4 py-2 border border-gray-300 focus:ring-blue-500 outline-none transit
                        @error('password') border-red-500 focus:ring-red-500 @else border-gray-300 focus:ring-blue-500 @enderror
                    ">

                    @error('password')
                        <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>


                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Confirmer le mot de passe *</label>
                    <input type="password" id="password_confirmation" name="password_confirmation"  
                        class="w-full px-4 py-2 border border-gray-300 focus:ring-blue-500 outline-none transit 
                        @error('password_confirmation') border-red-500 focus:ring-red-500 @else border-gray-300 focus:ring-blue-500 @enderror
                    ">

                    @error('password_confirmation')
                        <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>



                
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <label for="" class="block text-sm font-medium text-gray-700 mb-1">Matricule *</label>
                <input type="text" id="matricule" name="matricule"  value="{{old('matricule', Auth::user()->matricule) }}" placeholder="ACC-565X"  class="w-full px 4 py-2 border border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue outline-none transition 
                @error('matricule') border-red-500 focus:ring-red-500 @else border-gray-300 focus:ring-blue-500 @enderror
                ">

                @error('matricule')
                    <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Role *</label>
                <select name="role" id="role" class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline transition">
                    <option value="" disabled>Choisir un role</option>
                    <option value="superviseur" @selected(Auth::user()->role === 'superviseur')>Superviseur</option>

                </select>
            </div>
            <div class="pt-4">
                <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-2.5 px-4  rounded-lg shadow transition duration-200  ease-in-out transform hover:-translate-y-0.5">
                    Mettre a jour les informations
                </button>
            </div>
        </form>
    </div>

@endsection