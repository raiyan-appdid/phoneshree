<?php

namespace App\Mail\Admin;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Error extends Mailable
{
    use Queueable, SerializesModels;

    public $details;
    public $file;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($details, $file)
    {
        $this->details = $details;
        $this->file = $file;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Error in way to sport')->view('emails.admin.error')
            ->attachFromStorage($this->file, 'error-log.json', [
                'mime' => 'application/json',
            ]);
    }
}
