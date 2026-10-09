<x-app-layout>
    <x-slot name="header"><div><p>Tu cuenta</p><h2>Mi perfil</h2><p class="text-sm mt-2">Actualiza tus datos y la contraseña de acceso.</p></div></x-slot>
    <div class="max-w-3xl mx-auto px-4 sm:px-6 py-6 space-y-6">
        <div class="panel">@include('profile.partials.update-profile-information-form')</div>
        <div class="panel">@include('profile.partials.update-password-form')</div>
        <div class="panel !border-red-100">@include('profile.partials.delete-user-form')</div>
    </div>
</x-app-layout>
