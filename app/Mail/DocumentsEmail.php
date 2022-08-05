<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DocumentsEmail extends Mailable
{
    use Queueable, SerializesModels;
        public $firstname;
        public $lastname;
        public $projectname;
        public $title;
        public $attachment_img;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($firstname,$lastname,$projectname,$title,$attachment_img)
    {

        $this->subject          = "New Document Created by $firstname $lastname";
        $this->firstname        = $firstname;
        $this->lastname         = $lastname;
        $this->projectname      = $projectname;
        $this->title            = $title;
        $this->attachment_img   = $attachment_img;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $email = $this->view('mails/document_email');
        if(count($this->attachment_img) > 0){
            foreach($this->attachment_img as $filePath){
                $email->attach($filePath);
            }
        }
        return $email;
    }
}
