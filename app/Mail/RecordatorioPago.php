<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\{Estudiante, Apoderado};

class RecordatorioPago extends Mailable
{
    use Queueable, SerializesModels;

    private $estudiante;
    private $apoderado;
    private $datosPago;
    /**
     * Create a new message instance.
     * @var Array $datosPago -> contiene el mes, el valor del arancel, total de descuentos, total abonado y el total a pagar
     * @return void
     */
    public function __construct(Estudiante $estudiante, Apoderado $apoderado, $datosPago)
    {
        $this->estudiante = $estudiante;
        $this->apoderado = $apoderado;
        $this->datosPago = $datosPago;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: 'Recordatorio de pago mes de '. $this->datosPago['mes'],
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        return new Content(
            view: 'mails.recordatorioPago',
            with: [
                'estudiante' => $this->estudiante,
                'apoderado' => $this->apoderado,
                'datosPago' => $this->datosPago
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }
}
