<?php

namespace Modules\SettingManager\Mail;

use App\User;
use App\EmailTemplate;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\PurchaseManager\Entities\Quotation;

class QueryRegisterMailToUser extends Mailable {

    use Queueable,
        SerializesModels;

   public $company;
    public $query_res;
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
    public function __construct($company,$query_res) {
    
    $this->company =$company;
    $this->query_res =$query_res;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build() {
       
        $template = EmailTemplate::where('status', 1)->where('type', 'queryregister_mail_to_user')->first();

        if ($template) {


            $subject = $template->subject;
            $body = $template->template;
            $body = str_replace('##NAME##',  $this->company, $body);
            $body = str_replace('##id##',  "ICQ".sprintf("%04d", $this->query_res), $body);
           

            $this->subject($subject)
                    ->view('emails.email')
                    ->with(['body' => $body]);
        }
    }

}
