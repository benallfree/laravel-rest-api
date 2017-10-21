<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\User;

class AccountCreated extends Mailable
{
    use Queueable, SerializesModels;
    var $user;

    public function __construct(User $user)
    {
      $this->user = $user;
    }

    public function build()
    {
      return $this->markdown('emails.accounts.created')->with(['user'=>$this->user]);
    }
}
