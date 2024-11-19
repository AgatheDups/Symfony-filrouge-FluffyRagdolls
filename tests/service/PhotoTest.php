<?php

namespace App\Tests\Entity;

use App\Entity\Photo;
use App\Entity\Announcement;
use PHPUnit\Framework\TestCase;

class PhotoTest extends TestCase
{
    public function testSetGetUrl()
    {
        $photo = new Photo();

        // Test de la méthode setUrl()
        $photo->setUrl('https://example.com/photo.jpg');
        $this->assertSame('https://example.com/photo.jpg', $photo->getUrl());
    }

    public function testSetGetAnnouncement()
    {
        $photo = new Photo();
        $announcement = new Announcement();

        // Test de la méthode setAnnouncement()
        $photo->setAnnouncement($announcement);
        $this->assertSame($announcement, $photo->getAnnouncement());
    }

    public function testGetId()
    {
        $photo = new Photo();

        // Test que l'ID est null avant l'enregistrement dans la base de données
        $this->assertNull($photo->getId());
    }

    public function testSetAnnouncement()
    {
        $photo = new Photo();
        $announcement = new Announcement();

        // Test de la méthode setAnnouncement()
        $photo->setAnnouncement($announcement);
        $this->assertSame($announcement, $photo->getAnnouncement());
    }
}
