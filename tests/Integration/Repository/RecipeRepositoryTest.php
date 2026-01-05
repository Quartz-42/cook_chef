<?php

namespace App\Tests\Integration\Repository;

use App\Entity\Recipe;
use App\Entity\User;
use App\Repository\RecipeRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class RecipeRepositoryTest extends KernelTestCase
{
    public function testCountRecipes(): void
    {
        // 1. Démarrer le kernel Symfony
        self::bootKernel();

        // 2. Récupérer l'Entity Manager via le conteneur de services
        $container = static::getContainer();
        $em = $container->get('doctrine.orm.entity_manager');
              
        $user = new User();
        $user->setEmail('test-repo-' . uniqid() . '@example.com')
             ->setPassword('password')
             ->setRoles(['ROLE_USER']);
        $em->persist($user);

        $recipe = new Recipe();
        $recipe->setTitle('Recette Test')
               ->setSlug('recette-test' . uniqid())
               ->setContent('Contenu de la recette de test')
               ->setDuration(30)
               ->setCreatedAt(new \DateTimeImmutable())
               ->setUpdatedAt(new \DateTimeImmutable())
               ->setAuthor($user);
               
        $em->persist($recipe);
        $em->flush();

        // 4. Récupérer le repository et tester
        /** @var RecipeRepository $recipeRepository */
        $recipeRepository = $container->get(RecipeRepository::class);
        
        $count = $recipeRepository->count([]);
        
        $this->assertGreaterThanOrEqual(1, $count);
        
        // Vérifier qu'on peut retrouver notre recette
        $foundRecipe = $recipeRepository->findOneBy(['title' => 'Recette Test']);
        $this->assertNotNull($foundRecipe);
        $this->assertEquals('Contenu de la recette de test', $foundRecipe->getContent());
    }
}
