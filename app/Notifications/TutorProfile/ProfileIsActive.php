<?php

namespace App\Notifications\TutorProfile;

use App\Entity\TutorProfile;
use App\Entity\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ProfileIsActive extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * @var TutorProfile
     */
    private $profile;

    /**
     * @var User
     */
    private $user;

    /**
     * Create a new notification instance.
     *
     * @param TutorProfile $profile
     */
    public function __construct(TutorProfile $profile, User $user)
    {
        $this->profile = $profile;
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
            ->line(t('Your profile is active'))
            ->action(t('View profile'), route('profile.tutor.home'))
            ->line(t('Thank you for using our application'));
    }
}
