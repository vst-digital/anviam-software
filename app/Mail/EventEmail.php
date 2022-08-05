<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EventEmail extends Mailable
{
    use Queueable, SerializesModels;
        public $firstname;
        public $lastname;
        public $meetingcreater;
        public $title;
        public $description;
        public $referenceto;
        public $reference;
        public $starts;
        public $ends;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($firstname,$lastname,$meetingcreater,$title,$description,$referenceto,$reference,$starts,$ends)
    {
        $referencearr        = array("None","Memo",'Project');
        $this->subject       = "Meeting Invitation";
        $this->meetingcreater= $meetingcreater;
        $this->firstname     = $firstname;
        $this->lastname      = $lastname;
        $this->title         = $title;
        $this->description   = $description;
        $this->referenceto   = $referencearr[$referenceto];
        $this->reference     = $reference;
        $this->starts        = $starts;
        $this->ends          = $ends;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $email = $this->view('mails/event_email');
        return $email;
    }
}
