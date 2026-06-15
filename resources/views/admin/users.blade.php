@extends('layouts.simple')

@section('title', 'Admin')
@section('content')
    @forelse ($users as $user)
        <div class="bg-white shadow-md rounded-lg p-4 mb-4">
            <h3 class="text-lg font-semibold text-gray-800">{{ $user->name }}</h3>
            <p class="text-gray-600">{{ $user->email }}</p>
            <p class="text-gray-600">Role: {{ $user->role }}</p>
           
            <a href="{{ route('admin.update-user', $user->id) }}" class="text-blue-500 hover:underline mt-2 inline-block">Modifier</a>
            


                

                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST"
                    onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer définitivement {{ $user->name }} ?');">
                    @csrf
                    @method('DELETE')
                    
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-medium py-1.5 px-3 rounded-lg text-sm transition">
                        Supprimer
                    </button>
                </form>

                @if ($user->trashed())

            

                    <form action="{{ $user->trashed() ? route('admin.users.restore', $user->id)  : route('admin.users.disable', $user->id)  }}" method="POST"
                        onsubmit="return confirm('Êtes-vous sûr de vouloir {{$user->trashed() ? 'Reactiver' : 'Activer'}}  ce compte {{ $user->name }} ?');">
                        @csrf
                        @method('PATCH')
                        
                        <button type="submit" class="bg-gray-600 hover:bg-gray-700 text-white font-medium py-1.5 px-3 rounded-lg text-sm transition">
                            Reactiver
                        </button>
                    </form>

                @else 
                    <form action="{{ $user->trashed() ? route('admin.users.restore', $user->id)  : route('admin.users.disable', $user->id)  }}" method="POST"
                        onsubmit="return confirm('Êtes-vous sûr de vouloir {{$user->trashed() ? 'Reactiver' : 'Activer'}}  ce compte {{ $user->name }} ?');">
                        @csrf
                        @method('DELETE')
                        
                        <button type="submit" class="bg-gray-600 hover:bg-gray-700 text-white font-medium py-1.5 px-3 rounded-lg text-sm transition">
                            Desactiver
                        </button>
                    </form>
                @endif

            
        </div>

    @empty
        <p class="text-gray-600">Aucun utilisateur trouvé.</p>
    @endforelse    
@endsection
