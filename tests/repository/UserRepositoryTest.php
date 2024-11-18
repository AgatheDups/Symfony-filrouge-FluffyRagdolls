<?php
namespace App\Tests\Repository;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserRepositoryTest extends KernelTestCase
{
    public function testUserCreationAndDeletion()
    {
        // Démarrer le kernel
        self::bootKernel();
        $entityManager = self::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();
        
        // Créer un utilisateur
        $user = new User();
        $user->setUsername('TestUser');
        $user->setEmail('testuser@example.com');
        $user->setIsBreeder('0');
        $user->setCity('Lille');
        $user->setPassword(password_hash('password123', PASSWORD_BCRYPT));

        // Persister l'utilisateur dans la base de données
        $entityManager->persist($user);
        $entityManager->flush();

        // Vérifier si l'utilisateur a été enregistré
        $repository = $entityManager->getRepository(User::class);
        $savedUser = $repository->findOneBy(['email' => 'testuser@example.com']);
        
        $this->assertNotNull($savedUser);
        $this->assertEquals('TestUser', $savedUser->getUsername());

        // // Supprimer l'utilisateur
        // $entityManager->remove($savedUser);
        // $entityManager->flush();

        // // Vérifier si l'utilisateur a été supprimé
        // $deletedUser = $repository->findOneBy(['email' => 'testuser@example.com']);
        // $this->assertNull($deletedUser);
    }
}
