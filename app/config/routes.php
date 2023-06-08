<?php

/**  Этот файл просто содержит какие ссылки есть в проекте.
 *  И к каким классам и методам относятся.
*/
return 
[
    // Аккаунт
    'account' => [
        'controller' => 'account',
        'action' => 'account',
    ],
    '' => [
        'controller' => 'account',
        'action' => 'login',
    ],   
    'login' => [
        'controller' => 'account',
        'action' => 'login',
    ],

    'logout' => [
        'controller' => 'account',
        'action' => 'logout',
    ],

    'register' => [
        'controller' => 'account',
        'action' => 'register',
    ],
];