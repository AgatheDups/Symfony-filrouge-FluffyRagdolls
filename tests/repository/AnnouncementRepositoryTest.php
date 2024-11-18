<?php
namespace App\Tests\Repository;

use App\Entity\Announcement;
use App\Entity\CatGender;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class AnnouncementRepositoryTest extends KernelTestCase
{
    public function testCreateAndRetrieveAnnouncement()
    {
        // Démarrer le kernel Symfony
        self::bootKernel();
        $entityManager = self::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        // Création d'un utilisateur pour l'annonce
        $user = new User();
        $user->setUsername('TestUser1');
        $user->setEmail('testuser@example.com');
        $user->setIsBreeder('1');
        $user->setSiret('12345678901234');
        $user->setCity('Lille');
        $user->setPhoneNumber('0678956287');
        $user->setPassword(password_hash('password123', PASSWORD_BCRYPT));
        $entityManager->persist($user);

        // Création d'un genre pour le chat
        $catGender = new CatGender();
        $catGender->setGender('Male');
        $entityManager->persist($catGender);

        // Création de l'annonce
        $announcement = new Announcement();
        $announcement->setDescription('A beautiful Ragdoll cat ready for adoption!');
        $announcement->setCatName('Fluffy');
        $announcement->setCatBirth(new \DateTime('2022-01-01'));
        $announcement->setCatLoof(true);
        $announcement->setCatGender($catGender);
        $announcement->setUser($user);

        $entityManager->persist($announcement);
        $entityManager->flush();

        // Vérification de l'enregistrement dans la base
        $repository = $entityManager->getRepository(Announcement::class);
        $savedAnnouncement = $repository->findOneBy(['cat_name' => 'Fluffy']);

        $this->assertNotNull($savedAnnouncement);
        $this->assertEquals('Fluffy', $savedAnnouncement->getCatName());
        $this->assertEquals('A beautiful Ragdoll cat ready for adoption!', $savedAnnouncement->getDescription());
        $this->assertTrue($savedAnnouncement->isCatLoof());
        $this->assertEquals($catGender->getId(), $savedAnnouncement->getCatGender()->getId());
        $this->assertEquals($user->getId(), $savedAnnouncement->getUser()->getId());

        // // Nettoyage après le test
        // $entityManager->remove($savedAnnouncement);
        // $entityManager->remove($catGender);
        // $entityManager->remove($user);
        // $entityManager->flush();
    }
}



// commande : php bin/phpunit --filter AnnouncementRepositoryTest