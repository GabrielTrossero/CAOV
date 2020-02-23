<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendMail extends Mailable
{
    use Queueable, SerializesModels;

    //cuerpo del mail
    public $data;

    //vista en la que se carga el cuerpo del mail
    public $view;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data, $view)
    {
        $this->data = $data;
        $this->view = $view;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if ($this->view == 'cuota') {
            $subject = "Pago de Cuota";
        }
        else{
            $subject = "Pago de Alquiler de ".ucfirst($this->view);
        }

        return $this->from('user@gmail.com', 'User')
                    ->subject($subject)
                    ->view('emails.'.$this->view)
                    ->with('data', $this->data);
    }
}
