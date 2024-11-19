<?php
namespace App\Tests\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PostControllerTest extends WebTestCase
{
    public function testCreatePost()
    {
        $client = static::createClient();

        // Simuler un utilisateur connecté
        $user = $this->createTestUser($client);
        $client->loginUser($user);

        $crawler = $client->request('GET', '/forum/post/new');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('form');

        $form = $crawler->selectButton('Créer le post')->form([
            'post[title]' => 'Titre de test',
        ]);

        $client->submit($form);

        $this->assertResponseRedirects('/forum');
        $client->followRedirect();

        // Vérification de l'affichage du titre dans la liste des posts
        $this->assertSelectorTextContains('.post-title', 'Titre de test');
    }

    private function createTestUser($client)
    {
        $entityManager = $client->getContainer()->get('doctrine')->getManager();

        $user = new User();
        $user->setUsername('TestUser');
        $user->setEmail('testuser@example.com');
        $user->setPassword(password_hash('password', PASSWORD_BCRYPT));
        $user->setIsBreeder('0');
        $user->setCity('Lille');

        $entityManager->persist($user);
        $entityManager->flush();

        return $user;
    }
}


// commande: php bin/phpunit tests/Controller/PostControllerTest.php