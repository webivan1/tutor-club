<?php

namespace App\Mail\Profile;

use App\Entity\EmailReset;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class EmailResetMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * @var EmailReset
     */
    public $model;

    /**
     * Create a new message instance.
     *
     * @param EmailReset $model
     */
    public function __construct(EmailReset $model)
    {
        $this->model = $model;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject('Изменение почты')
            ->from(env('MAIL_USERNAME'), env('APP_NAME'))
            ->view('emails.profile.reset_email');
    }
}
