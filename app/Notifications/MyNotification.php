<?php

// namespace App\Notifications;

// use Illuminate\Bus\Queueable;
// use Illuminate\Contracts\Queue\ShouldQueue;
// use Illuminate\Notifications\Messages\MailMessage;
// use Illuminate\Notifications\Notification;

// class MyNotification extends Notification
// {
//     use Queueable;

//     private $message;

//     /**
//      * Create a new notification instance.
//      */
//     public function __construct()
//     {
//         $this->message = $message;
//     }

//     /**
//      * Get the notification's delivery channels.
//      *
//      * @return array<int, string>
//      */
//     public function via(object $notifiable): array
//     {
//         // return ['mail'];
//         return ['database'];

//     }

//     /**
//      * Get the mail representation of the notification.
//      */
//     public function toMail(object $notifiable): MailMessage
//     {
//         return (new MailMessage)
//                     ->line('The introduction to the notification.')
//                     ->action('Notification Action', url('/'))
//                     ->line('Thank you for using our application!');
//     }

//     /**
//      * Get the array representation of the notification.
//      *
//      * @return array<string, mixed>
//      */
//     // public function toArray(object $notifiable): array
//     // {
//     //     return [
//     //         //
//     //     ];
//     // }

//     public function toArray($notifiable)
//     {
//         return [
//             'message' => $this->message,
//         ];
//     }


// }

//<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Messaging;
use Kreait\Firebase\Factory;
use Illuminate\Notifications\Messages\DatabaseMessage;
use App\Services\FcmService;

class MyNotification extends Notification implements ShouldQueue
{
    use Queueable;

    private $title;
    private $body;

    /**
     * Create a new notification instance.
     *
     * @param string $message
     */
    public function __construct($title, $body)
    {
        $this->title = $title;
        $this->body = $body;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array<int, string>
     */
    public function via($notifiable): array
    {
        // return ['mail'];
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return MailMessage
     */
    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    // /**
    //  * Get the array representation of the notification.
    //  *
    //  * @param mixed $notifiable
    //  * @return array<string, mixed>
    //  */
    // public function toArray($notifiable): array
    // {
    //     return [
    //         'message' => $this->message,
    //     ];
    // }  
    

    public function toDatabase($notifiable)
    {
        return [
            'title' => $this->title,
            'body' => $this->body,
        ];
        // return response()->json([
        //     'message' => $this->message,
        // ]);
        
    }



    public function toFirebase($notifiable)
    {
        $deviceToken = $notifiable->deviceToken;

        if ($deviceToken) {

            try {
                $messaging = Firebase::messaging();

                $message = $this->message;

                $notification = FirebaseNotification::create("title")
                    ->withBody($message);

                $message = CloudMessage::withTarget('token', $deviceToken)
                    ->withNotification($notification);

                $messaging->send($message);
            }
            catch (\Throwable $e) {
                \Log::error('Failed to send Firebase notification: ' . $e->getMessage());
            }
        }
        else {
            \Log::warning('No device token found for user: ' . $notifiable->id);
        }
    }
}

