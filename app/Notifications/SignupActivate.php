<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Account;

class SignupActivate extends Notification
{
    use Queueable;

    private $CARD;
    private $PIN;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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
     * creating rondom string .
     *
     */


    private function randomx_str($length, $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ')
    {
        $pieces = [];
        $max = mb_strlen($keyspace, '8bit') - 1;
        for ($i = 0; $i < $length; ++$i) {
            $pieces[] = $keyspace[random_int(0, $max)];
        }
        return implode('', $pieces);
    }
    /**
     * creating rondom string that is unique in database.
     *
     */



    private function CARD_N()
    {

        $Ib = (rand(100000000, 999999999)) . $this->randomx_str(4, 'ABCDEFGHIJKLMNOPRSTWVXYZ');
        $CARD = Account::where('CARD_NUMBER', $Ib)->first();

        return $CARD ? $this->CARD_N() : $Ib; // reccursive call to create a new Card in database as field need to be unique

    }





    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $pin = rand(1000, 9999);
        $account = new Account([

            'user_id' => $notifiable->id,
            'CARD_NUMBER' => $this->CARD_N(),
            'PIN' => bcrypt($pin),
            'amount' => 300
        ]);

        $account->save();// saving card information in datbase
        $this->PIN = $pin;


        $url = url('/api/auth/signup/activate/' . $notifiable->id . '/' . $notifiable->activation_token); // redirect to AuthController with user id and confirmation token
        return (new MailMessage)
            ->subject('Credit card information and account confirmation')
            ->line('Thanks for signup! Please before you begin, you must confirm your account.')
            ->line('Sensetive Information.')
            ->line('CARD CODE:' . $account->CARD_NUMBER)
            ->line('PIN:' . $this->PIN)
            ->action('Confirm Account', url($url)) // email button
            ->line('Thank you for using our application!');// email setup
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
