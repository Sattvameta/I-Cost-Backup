<?php

namespace Modules\SettingManager\Mail;

use App\User;
use App\EmailTemplate;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\PurchaseManager\Entities\Quotation;

class PlanExpireMailToUser extends Mailable {

    use Queueable,
        SerializesModels;

   public $user_data;
  
    /**
     * Create a new message instance.
     *
     * @return void
     */

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user_data) {
    
    $this->user_data =$user_data;
   
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build() {
       
        $template = EmailTemplate::where('status', 1)->where('type', 'plan_expire_mail_to_user')->first();

        if ($template) {


            $subject = $template->subject;
            $body = $template->template;
            $body = str_replace('##NAME##',  $this->user_data, $body);
          
           

            $this->subject($subject)
                    ->view('emails.email')
                    ->with(['body' => $body]);
        }
    }

}
