<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RegisterEmail extends Mailable
{
    use Queueable, SerializesModels;
        public $firstname;
        public $lastname;
        public $email;
        public $password;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($firstname,$lastname,$email,$password)
    {

        $this->subject       = 'User Register Email';
        $this->firstname     = $firstname; 
        $this->lastname      = $lastname;
        $this->email         = $email;
        $this->password      = $password;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mails/registration_email');
    }
}
