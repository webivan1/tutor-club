<?php

namespace App\Notifications\Advert;

use App\Entity\Advert\Advert;
use App\Entity\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class AdvertIsActive extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * @var Advert
     */
    private $advert;

    /**
     * @var User
     */
    private $user;

    /**
     * Create a new notification instance.
     *
     * @param Advert $advert
     * @param User $user
     */
    public function __construct(Advert $advert, User $user)
    {
        $this->advert = $advert;
        $this->user = $user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->greeting(t('Hello :name', ['name' => $this->user->name]))
            ->line(t('Your ad #:id was moderated', ['id' => $this->advert->id]))
            ->line(t('Thank you for using our application'));
    }
}
