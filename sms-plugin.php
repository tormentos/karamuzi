<?php
/*
Plugin Name: sms-plugin
*/

register_activation_hook(__FILE__, 'wi_create_daily_backup_schedule');
function wi_create_daily_backup_schedule()
{
    //Use wp_next_scheduled to check if the event is already scheduled
    $timestamp = wp_next_scheduled('wi_create_daily_backup');

    //If $timestamp == false schedule daily backups since it hasn't been done previously
    if ($timestamp == false)
    {
        //Schedule the event for right now, then to repeat daily using the hook 'wi_create_daily_backup'
        wp_schedule_event(time() , 'daily', 'wi_create_daily_backup');
    }
}

//Hook our function , wi_create_backup(), into the action wi_create_daily_backup
add_action('wi_create_daily_backup', 'wi_create_backup');

register_deactivation_hook(__FILE__, 'wi_remove_daily_backup_schedule');
function wi_remove_daily_backup_schedule()
{
    wp_clear_scheduled_hook('wi_create_daily_backup');
}
function wi_create_backup()
{

    $text = array();
    $date = array();
    $phone = array(
        "09302435568",
        "09919979109",
        "09133190710",
        "09039031212"
    );

    global $wpdb;
    $all_sms = $wpdb->get_results("SELECT * FROM wp_woocommerce_ir_sms_archive");
    foreach ($all_sms as $sms)
    {
        if (preg_match('/(وضعیت در حال انجام)/', $sms->message))
        {
            array_push($text, $sms->message);
            array_push($date, $sms->date);

        }

        $now = new DateTime("now");
        for ($i = 0;$i < sizeof($text);$i++)
        {

            $target = new DateTime($date[$i], new DateTimeZone('Asia/Tehran'));
            $interval = $now->diff($target);
            $days = $interval->format('%R%a');
            $hours = $interval->format('%H');
var_dump($days , $hours);
            if ($days >= 0)
            {
                if ($hours <= 1)
                {
                    $sms_text = "you have one order to check !   ";
                    $sms_text = $sms_text . $text[$i];

                    //send sms
                    for ($i = 0;$i <= 3;$i++)
                    {
                        $url = 'http://rest.payamak-panel.com/api/SendSMS/SendSMS';
                        $handler = curl_init($url);
                        curl_setopt($handler, CURLOPT_CUSTOMREQUEST, "POST");
                        curl_setopt($handler, CURLOPT_POSTFIELDS, http_build_query(array(
                            'username' => '09134747479',
                            'password' => '2063',
                            'to' => $phone[$i],
                            'from' => '30008666747479',
                            'text' => $sms_text,
                        )));
                        curl_setopt($handler, CURLOPT_RETURNTRANSFER, true);
                        $response = curl_exec($handler);
                        echo $response;
                    }
                }

            }
        }
    }

}

