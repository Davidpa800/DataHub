<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Encuesta NOM-035</title>
    <style>
        body { font-family: sans-serif; background-color: #f4f7f6; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 20px auto; background-color: #ffffff; padding: 20px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05); }
        .header { text-align: center; border-bottom: 1px solid #e0e0e0; padding-bottom: 10px; margin-bottom: 20px; }
        .header img { max-width: 150px; }
        .content h1 { color: #1e3a8a; font-size: 24px; margin-bottom: 15px; }
        .content p { color: #555555; font-size: 16px; line-height: 1.6; margin-bottom: 15px; }
        .button-container { text-align: center; margin: 30px 0; }
        .button {
            background-color: #2563eb;
            color: #ffffff !important;
            padding: 12px 25px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
            display: inline-block;
        }
        .footer { text-align: center; margin-top: 25px; padding-top: 15px; border-top: 1px solid #e0e0e0; font-size: 12px; color: #999; }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        {{-- Usar una URL absoluta para el logo --}}
        <img src="{{ url('/img/osh_logo.png') }}" alt="OSH Consulting Logo">
    </div>

    <div class="content">
        <h1>Hola, {{ $asignacion->empleado->nombre_completo ?? 'Empleado' }},</h1>

        <p>Se te ha asignado una nueva encuesta obligatoria para dar cumplimiento a la <strong>NOM-035-STPS-2018</strong>.</p>
        <p>Por favor, tómate un momento para responder el cuestionario asignado a tu contrato <strong>{{ $asignacion->contrato->nombre ?? 'N/A' }}</strong>.</p>

        <div class="button-container">
            {{-- Generar el enlace de la encuesta usando el token --}}
            <a href="{{ url('/encuesta/' . $asignacion->token) }}" class="button">
                Comenzar Encuesta
            </a>
        </div>

        <p><strong>Detalles de la Encuesta:</strong></p>
        <ul>
            <li><strong>Cuestionario:</strong> {{ $asignacion->cuestionario->nombre ?? 'N/A' }}</li>
            <li><strong>Empresa:</strong> {{ $asignacion->contrato->empresa->nombre ?? 'N/A' }}</li>
            <li><strong>Total de Preguntas:</strong> {{ $asignacion->total_preguntas ?? 'N/A' }}</li>
        </ul>

        <p>Este enlace es único y personal. Por favor, no lo compartas.</p>
    </div>

    <div class="footer">
        <p>&copy; {{ date('Y') }} OSH Consulting. Todos los derechos reservados.</p>
    </div>
</div>
</body>
</html>

