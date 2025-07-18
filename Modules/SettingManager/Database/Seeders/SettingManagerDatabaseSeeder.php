<?php

namespace Modules\SettingManager\Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SettingManagerDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $data = [
            [
                'title' => 'Website Name',
                'slug' => 'SYSTEM_APPLICATION_NAME',
                'config_value' => 'Dotsquares',
                'manager' => 'general',
                'field_type' => 'text',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Admin Email',
                'slug' => 'ADMIN_EMAIL',
                'config_value' => 'hanumanprasad.yadav@dotsquares.com',
                'manager' => 'general',
                'field_type' => 'text',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'From Email',
                'slug' => 'FROM_EMAIL',
                'config_value' => 'hanumanprasad.yadav@dotsquares.com',
                'manager' => 'general',
                'field_type' => 'text',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Owner Name',
                'slug' => 'WEBSITE_OWNER',
                'config_value' => 'Hanuman Yadav',
                'manager' => 'general',
                'field_type' => 'text',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Telephone',
                'slug' => 'TELEPHONE',
                'config_value' => '+91-7665880635',
                'manager' => 'general',
                'field_type' => 'text',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Admin Page Limit',
                'slug' => 'ADMIN_PAGE_LIMIT',
                'config_value' => '20',
                'manager' => 'general',
                'field_type' => 'text',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Front Page Limit',
                'slug' => 'FRONT_PAGE_LIMIT',
                'config_value' => '20',
                'manager' => 'general',
                'field_type' => 'text',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Admin Date Format',
                'slug' => 'ADMIN_DATE_FORMAT',
                'config_value' => 'd F, Y',
                'manager' => 'general',
                'field_type' => 'text',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Admin Date Time Format',
                'slug' => 'ADMIN_DATE_TIME_FORMAT',
                'config_value' => 'd F, Y H:i A',
                'manager' => 'general',
                'field_type' => 'text',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Front Date Format',
                'slug' => 'FRONT_DATE_FORMAT',
                'config_value' => 'd F, Y',
                'manager' => 'general',
                'field_type' => 'text',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Front Date Time Format',
                'slug' => 'FRONT_DATE_TIME_FORMAT',
                'config_value' => 'd F, Y',
                'manager' => 'general',
                'field_type' => 'text',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Reset URL expired in hours',
                'slug' => 'RESET_URL_EXPIRED',
                'config_value' => '24',
                'manager' => 'general',
                'field_type' => 'text',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Development Mode',
                'slug' => 'DEVELOPMENT_MODE',
                'config_value' => '1',
                'manager' => 'general',
                'field_type' => 'checkbox',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Default currency',
                'slug' => 'DEFAULT_CURRENCY',
                'config_value' => 'USD',
                'manager' => 'general',
                'field_type' => 'text',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Contact us text',
                'slug' => 'CONTACT_US_TEXT',
                'config_value' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.',
                'manager' => 'general',
                'field_type' => 'text',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Google Map Api Key',
                'slug' => 'GOOGLE_MAP_KEY',
                'config_value' => 'AIzaSyD9pg6_fzfgDHJFSW0wkrIcuCOw_V9qOfM',
                'manager' => 'general',
                'field_type' => 'text',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Office Address',
                'slug' => 'ADDRESS',
                'config_value' => '6-Kha-9, Jawahar Nagar, <br> Jaipur, Rajasthan - 302004, India',
                'manager' => 'general',
                'field_type' => 'text',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Main Logo',
                'slug' => 'MAIN_LOGO',
                'config_value' => 'ds.jpg',
                'manager' => 'theme_images',
                'field_type' => 'text',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Main Favicon',
                'slug' => 'MAIN_FAVICON',
                'config_value' => 'dots-logon.png',
                'manager' => 'theme_images',
                'field_type' => 'text',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'SMTP Allow',
                'slug' => 'SMTP_ALLOW',
                'config_value' => '1',
                'manager' => 'smtp',
                'field_type' => 'checkbox',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'SMTP Email Host',
                'slug' => 'SMTP_EMAIL_HOST',
                'config_value' => 'mail.dotsquares.com',
                'manager' => 'smtp',
                'field_type' => 'text',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'SMTP Username',
                'slug' => 'SMTP_USERNAME',
                'config_value' => 'wwwsmtp@dotsquares.com',
                'manager' => 'smtp',
                'field_type' => 'text',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'SMTP Password',
                'slug' => 'SMTP_PASSWORD',
                'config_value' => 'dsmtp909#',
                'manager' => 'smtp',
                'field_type' => 'text',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'SMTP Port',
                'slug' => 'SMTP_PORT',
                'config_value' => '25',
                'manager' => 'smtp',
                'field_type' => 'text',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'SMTP Tls',
                'slug' => 'SMTP_TLS',
                'config_value' => '0',
                'manager' => 'smtp',
                'field_type' => 'checkbox',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],


        ];

        DB::table('settings')->insert($data);

        // $this->call("OthersTableSeeder");
    }
}
