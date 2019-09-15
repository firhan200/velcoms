<?php
namespace App\Services;

class AdminNotification
{
    private $model;

    public function __construct(){
        //init model
        $this->model = new \App\Models\Admin_Notification;
    }

    /*
    * @params = message, link, is_vue_link
     */
    public function create($message, $link, $is_vue_link = true)
    {
        //init model
        $notification =  $this->model;

        //fill body
        $notification->message = $message;
        $notification->link = $link;
        $notification->is_vue_link = $is_vue_link;

        //default attr
        $notification->is_active = true;
        $notification->is_deleted = false;

        //save
        $notification->save();
    }
}