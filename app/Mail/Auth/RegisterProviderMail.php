<?php

namespace App\Mail\Auth;

use App\Entity\UserProvider;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class RegisterProviderMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * @var UserProvider
     */
    public $user;

    /**
     * Create a new message instance.
     *
     * @param UserProvider $user
     */
    public function __construct(UserProvider $user)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject(t('Confirm email'))
            ->from(env('MAIL_USERNAME'), env('APP_NAME'))
            ->view('emails.auth.provider');
    }
}
