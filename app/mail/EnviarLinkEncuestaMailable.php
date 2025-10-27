<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\EncuestaAsignada; // Importar el modelo

class EnviarLinkEncuestaMailable extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * La instancia de la encuesta asignada.
     *
     * @var EncuestaAsignada
     */
    public $asignacion;

    /**
     * Crea una nueva instancia del mensaje.
     */
    public function __construct(EncuestaAsignada $asignacion)
    {
        $this->asignacion = $asignacion;
    }

    /**
     * Obtiene el sobre del mensaje (Subject y From).
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Notificación: Tienes una nueva encuesta NOM-035 pendiente',
        );
    }

    /**
     * Obtiene la definición del contenido del mensaje.
     */
    public function content(): Content
    {
        // Se define qué archivo Blade usar para el cuerpo del correo
        return new Content(
            view: 'emails.encuesta_link',
        // Puedes pasar más variables aquí si es necesario
        );
    }

    /**
     * Obtiene los adjuntos para el mensaje.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
