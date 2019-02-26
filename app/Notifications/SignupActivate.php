<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Account;

/**
 * A class responsible for sending verification email 
 * email preferences need to be setup on .env file on the root folder
 * creates card numbers and hashed pin associate them with the user 
 * saves in accounts table on database the information
 * this class is called by 
 * file: app/Http/Controller/AuthController.php 
 * class: AuthController
 * method: public function signup(Request $request) 
 * usage: $user->notify(new SignupActivate($user));
 * @param json // accepts a json as parameters
 *
 * @author Mohamed Snoussi
 *
 */

class SignupActivate extends Notification
{
    use Queueable;

    private $cardNumber;
    private $pin;

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
     * private function for returning the return random string
     * used for the card number
     * if parameters are not available in function call then default will be executed
     * @param  mixed $length the length of the string 
     * @param  mixed $keyspace the 
     *
     * @return mixed // return a string
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
     * Creating random string that is unique in database.
     * the method is a recursive function
     * if it find a record in the database it call it self
     */

    private function CARD_N()
    {
        $Ib = (rand(100000000, 999999999)) . $this->randomx_str(4, 'ABCDEFGHIJKLMNOPRSTWVXYZ');
        $cardNumber = Account::where('cardNumber', $Ib)->first();
        return $cardNumber ? $this->CARD_N() : $Ib;
    }
    /**
     * Sending the email.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        // saving the new card in formation in the data base
        $pin = rand(1000, 9999); // random string for the pin
        $account = new Account([

            'user_id' => $notifiable->id,
            'cardNumber' => $this->CARD_N(),
            'pin' => bcrypt($pin)
        ]); // account table

        $account->save();// saving card information in database
        $this->pin = $pin;

        $url = url('/api/auth/signup/activate/' . $notifiable->id . '/' . $notifiable->activationToken); // redirect to AuthController with user id and confirmation token
        return (new MailMessage)
            ->subject('Credit card information and account confirmation')
            ->line('Thanks for signup! Please before you begin, you must confirm your account.')
            ->line('Sensetive Information.')
            ->line('CARD CODE:' . $account->cardNumber)
            ->line('PIN:' . $this->pin)
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
