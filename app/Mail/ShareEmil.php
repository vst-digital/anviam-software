<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ShareEmil extends Mailable
{
    use Queueable, SerializesModels;
        public $file;
        public $id;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($file,$id)
    {        
        $this->file = $file;        
        $this->id = $id;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
       return $this->view('mails/share_email');
    }
}
