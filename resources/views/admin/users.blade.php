@extends('layouts.simple')

@section('title', 'Admin')

@section('content')
    @forelse ($users as $user)
        <div class="bg-white shadow-md rounded-lg p-4 mb-4">
            <h3 class="text-lg font-semibold text-gray-800">{{ $user->name }}</h3>
            <p class="text-gray-600">{{ $user->email }}</p>
            <p class="text-gray-600">Role: {{ $user->role }}</p>
            <a href="{{ route('admin.update-user', $user->id) }}" class="text-blue-500 hover:underline mt-2 inline-block">Modifier</a>
        </div>

    @empty
        <p class="text-gray-600">Aucun utilisateur trouvé.</p>
    @endforelse    
@endsection
