<?php

namespace App\Tests;

use App\Entity\User;
use App\utils\Sender;
use PHPUnit\Framework\TestCase;

class SenderTest extends TestCase
{
    public function testSendNewUserNotificationToAdmin(): void
    {
        $user = new User();
        $user->setEmail("mail@test.com");
        $sender = new Sender();
        $sender->sendNewUserNotificactionToAdmin($user);
        $this->assertContains(file_get_contents("notif.txt"),["mail@test.com"], "File must contain mail@test.com !");
    }
}
