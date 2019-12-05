<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Queue\InteractsWithQueue;
use JPush\Client;

class PushNotification implements ShouldQueue
{
    protected $client;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param DatabaseNotification $notification
     */
    public function handle(DatabaseNotification $notification)
    {
        if (app()->environment('production')) {
            $user = $notification->notifiable;
            if ($user->registration_id) {
                $this->client->push()
                            ->setPlatform('all')
                            ->addRegistrationId($user->registration_id)
                            ->setNotificationAlert(strip_tags($notification->data['reply_content']))
                            ->send();
            }
        }
    }
}
