<?php

namespace App\utils;

use App\Entity\User;

class Sender
{
    public function sendNewUserNotificactionToAdmin(User $user){
        file_put_contents("notif.txt", $user->getEmail());
    }

}