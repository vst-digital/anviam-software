<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MemoEmail extends Mailable
{
    use Queueable, SerializesModels;
        public $firstname;
        public $lastname;
        public $projectname;
        public $subjectname;
        public $project_number;
        public $correspondence_no;
        public $datetime;
        public $response;
        public $location;
        public $memo;
        public $attachment_img;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($firstname,$lastname,$projectname,$subjectname,$project_number,$correspondence_no,$datetime,$response,$location,$memo,$attachment_img)
    {

        $this->subject          = "Memo for $projectname";
        $this->firstname        = $firstname;
        $this->lastname         = $lastname;
        $this->projectname      = $projectname;
        $this->subjectname      = $subjectname;
        $this->project_number   = $project_number;
        $this->correspondence_no= $correspondence_no;
        $this->datetime         = $datetime;
        $this->response         = $response;
        $this->location         = $location;
        $this->memo             = $memo;
        $this->attachment_img   = $attachment_img;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $email = $this->view('mails/memo_email');
        if(count($this->attachment_img) > 0){
            foreach($this->attachment_img as $filePath){
                $email->attach($filePath);
            }
        }
        return $email;
    }
}
