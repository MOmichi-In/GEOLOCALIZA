<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Gestionar Tarea</title>
</head>

<style>
    @font-face {
        font-family: 'CenturyGothic';
        src: url('{{ storage_path('fonts/CenturyGothic.ttf') }}') format('truetype');
        font-weight: normal;
        font-style: normal;
    }

    @font-face {
        font-family: 'CenturyGothic';
        src: url('{{ storage_path('fonts/GOTHICB.TTF') }}') format('truetype');
        font-weight: bold;
        font-style: normal;
    }

    @font-face {
        font-family: 'CenturyGothic';
        src: url('{{ storage_path('fonts/GOTHICI.TTF') }}') format('truetype');
        font-weight: normal;
        font-style: italic;
    }

    @font-face {
        font-family: 'CenturyGothic';
        src: url('{{ storage_path('fonts/GOTHICBI.TTF') }}') format('truetype');
        font-weight: bold;
        font-style: italic;
    }

    @page {
        font-family: 'CenturyGothic', sans-serif;
        margin: 20px;
    }

    body {
        font-family: 'CenturyGothic', sans-serif;
        font-size: 10pt;
        margin: 0;
        padding: 0;
        position: relative;
        min-height: 100vh;
    }

    .content-wrapper {
        padding-bottom: 180px; /* Espacio para las firmas en el pie */
    }

    .bold {
        font-weight: bold;
    }

    .section-title {
        font-size: 12pt;
        margin-top: 20px;
        margin-bottom: 10px;
        border-bottom: 1px solid #ccc;
        padding-bottom: 5px;
        font-weight: bold;
        text-align: center;
    }

    .sub-section-title {
        font-size: 11pt;
        margin-top: 15px;
        margin-bottom: 8px;
        color: #333;
        font-weight: bold;
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 15px;
        font-size: 10pt;
    }

    .data-table th,
    .data-table td {
        border: 1px solid #ddd;
        padding: 6px;
        text-align: left;
        vertical-align: top;
    }

    .data-table tr td:first-child {
        vertical-align: middle;
    }

    .data-table th {
        background-color: #f2f2f2;
        font-weight: bold;
    }

    .data-table .data-label {
        font-weight: bold;
        width: 200px;
        background-color: #f9f9f9;
    }

    .main-title {
        font-size: 14pt;
        margin-bottom: 20px;
        text-align: center;
        font-weight: bold;
        color: #333;
    }

    .task-header {
        text-align: center;
        margin-bottom: 25px;
        font-size: 16pt;
        font-weight: bold;
        color: #333;
    }

    .task-title {
        display: inline;
        margin-right: 10px;
    }

    .task-number {
        display: inline;
    }

    .status-badge {
        display: inline-block;
        padding: 4px 8px;
        border-radius: 3px;
        font-size: 9pt;
        font-weight: bold;
        text-transform: uppercase;
    }

    .status-pendiente {
        background-color: #fff3cd;
        color: #856404;
        border: 1px solid #ffeaa7;
    }

    .status-completado {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    .status-en-proceso {
        background-color: #cce5ff;
        color: #004085;
        border: 1px solid #b3d9ff;
    }

    /* Estilos para firmas en el pie del documento */
    .signature-section {
        position: absolute;
        bottom: 60px; /* Espacio para el footer */
        left: 20px;
        right: 20px;
        page-break-inside: avoid;
    }

    .signature-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
        page-break-inside: avoid;
    }

    .signature-table td {
        width: 33.33%;
        text-align: center;
        vertical-align: bottom;
        padding: 0 15px;
        box-sizing: border-box;
        border: none;
        height: 100px; /* Altura fija para cada celda */
    }

    .signature-container {
        position: relative;
        height: 80px; /* Altura del contenedor de firma */
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
    }

    .signature-img {
        max-width: 120px;
        max-height: 40px;
        height: auto;
        display: block;
        margin: 0 auto 5px auto; /* Pequeño margen arriba de la línea */
        background: transparent;
        position: relative;
        z-index: 1;
    }

    .signature-line {
        border-top: 1px solid #797979;
        margin: 0 auto;
        width: 80%;
        height: 1px;
    }

    .signature-text {
        margin-top: 8px;
        font-size: 9pt;
        font-weight: bold;
        color: #333;
    }

    .signature-subtitle {
        font-size: 8pt;
        color: #666;
        margin-top: 3px;
    }

    .footer-text {
        position: absolute;
        bottom: 20px;
        left: 0;
        right: 0;
        text-align: center;
        font-size: 8pt;
        color: #777;
        margin: 0 20px;
    }

    /* Responsive adjustments */
    @media print {
        body {
            margin: 0;
            padding: 0;
        }
        
        .signature-section {
            position: fixed;
            bottom: 60px;
            left: 20px;
            right: 20px;
        }
        
        .footer-text {
            position: fixed;
            bottom: 20px;
            left: 0;
            right: 0;
            margin: 0 20px;
        }
    }
</style>

<body>
    <div class="content-wrapper">
        @php
            $pathLogo = public_path('images/img/logoRib.png');
            $pathMarcaAgua = public_path('images/marca_de_agua/MarcaAguaRib.png');
            $logo = ($logoContent = @file_get_contents($pathLogo)) ? 'data:image/png;base64,' . base64_encode($logoContent) : null;
            $marcaAgua = ($marcaAguaContent = @file_get_contents($pathMarcaAgua))
                ? 'data:image/png;base64,' . base64_encode($marcaAguaContent)
                : null;
        @endphp

        @if ($marcaAgua)
            <img src="{{ $marcaAgua }}"
                style="width: 700px; height: auto; opacity: 0.15; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: -1;">
        @endif

        <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px; font-size: 10pt;">
            <tr>
                <!-- Columna Logo -->
                <td rowspan="2" style="width: 25%; border: 1px solid black; text-align: center; vertical-align: middle;">
                    @if ($logo)
                        <img src="{{ $logo }}" alt="Logo RIB"
                            style="max-width: 100px; height: auto;">
                    @else
                        <span>Logo RIB</span>
                    @endif
                </td>

                <!-- Título superior central -->
                <td style="width: 50%; border: 1px solid black; text-align: center; font-weight: bold;">
                    FORMATO
                </td>

                <!-- Fecha -->
                <td style="width: 25%; border: 1px solid black; text-align: center;">
                    Fecha: <br> {{ now()->format('d/m/Y') }}
                </td>
            </tr>
            <tr>
                <!-- Título principal abajo en el centro -->
                <td style="border: 1px solid black; text-align: center; font-weight: bold;">
                    GESTIÓN DE TAREAS <br> OPERATIVAS
                </td>

                <!-- Versión -->
                <td style="border: 1px solid black; text-align: center;">
                    Versión: 01
                </td>
            </tr>
        </table>

        <div class="task-header">
            <span class="task-title">ASIGNACIÓN</span>
            <span class="task-number">N° {{ $tarea->id }}</span>
        </div>

        <div class="section-title">INFORMACIÓN DEL OPERADOR</div>
        <table class="data-table">
            <tr>
                <td class="data-label">Nombre Completo:</td>
                <td>{{ $tarea->operador->name }}</td>
            </tr>
            <tr>
                <td class="data-label">Cédula (CC):</td>
                <td>{{ $tarea->operador->cedula }}</td>
            </tr>
            <tr>
                <td class="data-label">Empresa:</td>
                <td>RIB</td>
            </tr>
        </table>

        <div class="section-title">DETALLES DE LA TAREA</div>
        <table class="data-table">
            <tr>
                <td class="data-label">Actividad Asignada:</td>
                <td>{{ $tarea->actividad->nombre }}</td>
            </tr>
            <tr>
                <td class="data-label">Cantidad:</td>
                <td>{{ $tarea->cantidad }}</td>
            </tr>
            <tr>
                <td class="data-label">Ciclo:</td>
                <td>{{ $tarea->ciclo->nombre }}</td>
            </tr>
            <tr>
                <td class="data-label">Correría:</td>
                <td>{{ $tarea->correria->nombre }}</td>
            </tr>
            <tr>
                <td class="data-label">Fecha de Inicio:</td>
                <td>{{ $tarea->fecha_inicio->format('d/m/Y') }}</td>
            </tr>
            <tr>
                <td class="data-label">Fecha de Entrega:</td>
                <td>{{ optional($tarea->fecha_entrega)->format('d/m/Y') ?? 'Pendiente' }}</td>
            </tr>
            <tr>
                <td class="data-label">Estado:</td>
                {{-- para ti que estas corrigiendo el pdf, puse un estado intermedio por si en algun momento se necesita --}}
                <td>
                    @if($tarea->estado == 'completado')
                        <span class="status-badge status-completado">{{ ucfirst($tarea->estado) }}</span>
                    @elseif($tarea->estado == 'en_proceso')
                        <span class="status-badge status-en-proceso">En Proceso</span>
                    @else
                        <span class="status-badge status-pendiente">{{ ucfirst($tarea->estado) }}</span>
                    @endif
                </td>
            </tr>
            <tr>
                <td class="data-label">Observaciones:</td>
                <td>{{ $tarea->observaciones ?? 'Sin observaciones' }}</td>
            </tr>
        </table>

        <div class="footer-text">
            Este documento es confidencial y para uso exclusivo de la organización. Generado el: {{ now()->format('d/m/Y H:i:s') }}
        </div>
    </div>

    <div class="signature-section">
        <div class="section-title">FIRMAS DE CONTROL</div>
        <table class="signature-table">
            <tr>
                <td>
                    <div class="signature-container">
                        @if($tarea->firma_inicio)
                            <img src="{{ $tarea->firma_inicio }}" alt="Firma Inicio" class="signature-img">
                        @endif
                        <div class="signature-line"></div>
                        <div class="signature-text">FIRMA DE INICIO</div>
                        <div class="signature-subtitle">
                            {{ $tarea->operador->name }}
                        </div>
                    </div>
                </td>
                <td>
                    <div class="signature-container">
                        @if($tarea->firma_final)
                            <img src="{{ $tarea->firma_final }}" alt="Firma Final" class="signature-img">
                        @endif
                        <div class="signature-line"></div>
                        <div class="signature-text">FIRMA DE FINALIZACIÓN</div>
                        <div class="signature-subtitle">
                            {{ $tarea->operador->name }}
                        </div>
                    </div>
                </td>
                <td>
                    <div class="signature-container">
                        @if($tarea->firma_supervisor)
                            <img src="{{ $tarea->firma_supervisor }}" alt="Firma Supervisor" class="signature-img">
                        @endif
                        <div class="signature-line"></div>
                        <div class="signature-text">FIRMA DE SUPERVISOR</div>
                        <div class="signature-subtitle">
                            Supervisor de Operaciones
                        </div>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <div class="footer-text">
        Este documento es confidencial y para uso exclusivo de la organización. Generado el: {{ now()->format('d/m/Y H:i:s') }}
    </div>
</body>

</html>