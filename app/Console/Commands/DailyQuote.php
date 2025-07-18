<?php

namespace App\Console\Commands;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMail;
use App\User;
use Modules\SettingManager\Mail\PlanExpireMailToUser;
class DailyQuote extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'quote:daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Respectively send an exclusive quote to everyone daily via email.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }  

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        
        
            $Today=date('Y-m-d');
            $NewDate=Date('Y-m-d', strtotime('+3 days'));    
            
            $list_to_send_mail = \DB::table('users')->select('*')->where([['end_date', '=', $NewDate],['status', '=', 1] ])->get();
            if(count($list_to_send_mail)){
                foreach($list_to_send_mail as $user_data){
                    \Mail::to($user_data->email)->send(new PlanExpireMailToUser($user_data->full_name));
                    
                }
                 
            }
          
      
        $this->info('Successfully sent daily quote to everyone.');
    }
}