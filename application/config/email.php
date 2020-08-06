<?php
$config = array(
    'protocol' => 'smtp',
    'smtp_host' => 'ssl://smtp.gmail.com',
    'smtp_port' => 465,
    'smtp_user' => 'backend.uat@gmail.com',
    'smtp_pass' => 'backend.dev.23',
    'mailtype'  => 'html',
    'charset'   => 'utf-8',
    'newline' => "\r\n",

    'sender_name' => 'Backend Developer UAT',
    'sender_email' => 'noreply.tripetch@gmail.com',

    'email_forgot_password' => array(
        'link' => 'https://frontend.23perspective.com/forgot_password/',
        'subject' => 'Forgot password',
    ),
);
