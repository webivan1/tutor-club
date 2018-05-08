<?php

namespace App\Mail\Admin;

use App\Entity\Admin\TutorProfile;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class TutorProfileMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * @var TutorProfile
     */
    public $profile;

    /**
     * Create a new message instance.
     *
     * @param TutorProfile $profile
     */
    public function __construct(TutorProfile $profile)
    {
        $this->profile = $profile;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject('Изменение статуса вашего профиля')
            ->from(env('MAIL_USERNAME'), env('APP_NAME'))
            ->view('emails.admin.tutor');
    }
}
