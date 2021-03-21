<?php
/*
class MyMemcachedSessionHandler extends SessionHandler {
    public function read($id)
    {
        $data = parent::read($id);
        if(empty($data)) {
            return '';
        } else {
            return $data;
        }
    } 
}
$myMemcachedSessionHandler = new MyMemcachedSessionHandler();
session_set_save_handler($myMemcachedSessionHandler);
*/
session_start();
