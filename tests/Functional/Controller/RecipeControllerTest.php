<?php

namespace App\Tests\Functional\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RecipeControllerTest extends WebTestCase
{
    public function testIndexPageIsRestricted(): void
    {
        $client = static::createClient();
        $client->request('GET', '/recettes/');

        // Doit rediriger vers le login car #[IsGranted('ROLE_USER')]
        $this->assertResponseRedirects('/login');
    }

    public function testIndexPageIsAccessibleForUser(): void
    {
        $client = static::createClient();
        
        // 1. Récupérer un utilisateur existant ou en créer un
        $userRepository = static::getContainer()->get(UserRepository::class);
        
        // On cherche un user de test, ou on en crée un s'il n'existe pas
        $testUser = $userRepository->findOneBy(['email' => 'user@demo.fr']);
        
        if (!$testUser) {
            // Fallback: créer un user temporaire si pas de fixtures
            $entityManager = static::getContainer()->get('doctrine.orm.entity_manager');
            $testUser = new User();
            $testUser->setEmail('functional_test_' . uniqid() . '@example.com')
                     ->setPassword('$2y$13$P')
                     ->setRoles(['ROLE_USER']);
            $entityManager->persist($testUser);
            $entityManager->flush();
        }

        // 2. Simuler le login
        $client->loginUser($testUser);

        // 3. Accéder à la page
        $crawler = $client->request('GET', '/recettes/');

        // 4. Vérifications
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('title', 'Toutes les recettes');
        $this->assertGreaterThan(0, $crawler->filter('table')->count(), 'Le tableau des recettes doit être présent');
    }
}
