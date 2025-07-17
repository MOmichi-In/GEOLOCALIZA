<div class="p-6 bg-white shadow-lg rounded-lg max-w-2xl mx-auto my-8">
    <h2 class="text-2xl font-bold mb-6 text-gray-800">Registro de Asistencia</h2>


    @if (session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('message') }}</span>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <form wire:submit.prevent="submitRegistro">
        {{-- Aquí irán todos los campos del formulario --}}


        {{-- Tarea Asignada --}}
        <div>
            <label htmlFor="tareaAsignada" class="block text-gray-700 text-sm font-semibold mb-2">Tarea
                Asignada:</label>
            <select id="tareaAsignada"
                class="w-full p-3 border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                wire:model="tarea_asignada_id">
                <option value="">Seleccione una tarea</option>

            </select>
            @error('tarea_asignada_id')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        {{-- Tipo de Registro (Inicio/Final) --}}
        <div>
            <label htmlFor="tipoRegistro" class="block text-gray-700 text-sm font-semibold mb-2">Tipo de
                Registro:</label>
            <select id="tipoRegistro"
                class="w-full p-3 border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                wire:model="tipo">
                @foreach ($registroTipos as $rt)
                    <option value="{{ $rt }}">{{ ucfirst($rt) }}</option>
                @endforeach
            </select>
            @error('tipo')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        {{-- Fecha y Hora de Captura (Automática) --}}
        <div>
            <label class="block text-gray-700 text-sm font-semibold mb-2">Fecha y Hora de Captura:</label>
            <input type="text"
                class="w-full p-3 border border-gray-300 rounded-lg bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500"
                wire:model="hora_captura" readonly />

            {{-- Tipo de actividad realizada (selección) --}}
            <div>
                <label htmlFor="tipoActividad" class="block text-gray-700 text-sm font-semibold mb-2">Tipo de Actividad
                    Realizada:</label> {{-- Cambiado --}}
                <select id="tipoActividad" {{-- Cambiado --}}
                    class="w-full p-3 border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                    wire:model="tipo_actividad" {{-- Cambiado --}}>
                    @foreach ($activityTypes as $type)
                        <option value="{{ $type }}">{{ $type }}</option>
                    @endforeach
                </select>
                @error('tipo_actividad')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror {{-- Cambiado --}}
            </div>
        </div>
        <div class="flex items-center justify-end mt-6">
            <button type="submit"
                class="bg-red-700 hover:bg-red-600 text-white font-bold py-2 px-6 rounded-lg focus:outline-none focus:shadow-outline transition duration-150 ease-in-out">
                Guardar Registro
            </button>
        </div>
    </form>

</div>
