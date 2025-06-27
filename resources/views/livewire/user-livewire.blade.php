<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Gestor de usuario') }}
        </h2>
    </x-slot>
    <form action="{{ route('usuarios') }}" method="POST" enctype="multipart/form-data">
        @csrf


        <p></p>

    </form>






















</div>
