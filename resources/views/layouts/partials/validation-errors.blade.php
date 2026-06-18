@if (isset($errors) && $errors->any())
    <x-alert type="error">
        <p class="font-semibold">Veuillez corriger les erreurs suivantes :</p>
        <ul class="mt-2 list-inside list-disc space-y-1">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </x-alert>
@endif
