<?php

namespace App\DataFixtures;

use App\Entity\Ingredient;
use App\Entity\Quantity;
use App\Entity\Recipe;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\String\Slugger\AsciiSlugger;

class AppFixtures extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher,
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        $slugger = new AsciiSlugger();

        // Créer des utilisateurs
        $users = [];

        // Admin user
        $admin = new User();
        $admin->setEmail('admin@cook-chef.com');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword($this->passwordHasher->hashPassword($admin, 'admin123'));
        $admin->setIsVerified(true);
        $manager->persist($admin);
        $users[] = $admin;

        // Users normaux
        for ($i = 0; $i < 5; ++$i) {
            $user = new User();
            $user->setEmail($faker->unique()->email());
            $user->setRoles(['ROLE_USER']);
            $user->setPassword($this->passwordHasher->hashPassword($user, 'password123'));
            $user->setIsVerified($faker->boolean(80)); // 80% de chance d'être vérifié
            $manager->persist($user);
            $users[] = $user;
        }

        // Créer des ingrédients
        $ingredients = [];
        $ingredientNames = [
            'Tomate', 'Oignon', 'Ail', 'Carotte', 'Pomme de terre', 'Courgette',
            'Aubergine', 'Poivron rouge', 'Poivron vert', 'Brocoli', 'Épinard',
            'Salade', 'Concombre', 'Radis', 'Champignon', 'Avocat',
            'Poulet', 'Bœuf', 'Porc', 'Saumon', 'Thon', 'Crevettes',
            'Œuf', 'Lait', 'Crème fraîche', 'Beurre', 'Fromage', 'Yaourt',
            'Farine', 'Sucre', 'Sel', 'Poivre', 'Huile d\'olive', 'Vinaigre',
            'Basilic', 'Persil', 'Thym', 'Origan', 'Paprika', 'Cumin',
            'Pâtes', 'Riz', 'Quinoa', 'Pain', 'Miel', 'Citron', 'Orange',
        ];

        foreach ($ingredientNames as $name) {
            $ingredient = new Ingredient();
            $ingredient->setName($name);
            $ingredient->setSlug(strtolower($slugger->slug($name)->toString()));
            $manager->persist($ingredient);
            $ingredients[] = $ingredient;
        }

        // Créer des recettes
        $recipes = [];
        $recipeData = [
            [
                'title' => 'Spaghetti à la Bolognaise',
                'content' => "1. Faire revenir l'oignon et l'ail dans l'huile d'olive.\n2. Ajouter la viande hachée et faire cuire.\n3. Incorporer les tomates et laisser mijoter 30 minutes.\n4. Cuire les pâtes selon les instructions.\n5. Servir avec du parmesan râpé.",
                'duration' => 45,
            ],
            [
                'title' => 'Salade César',
                'content' => "1. Laver et couper la salade.\n2. Griller le pain pour faire des croûtons.\n3. Préparer la sauce Caesar.\n4. Mélanger tous les ingrédients.\n5. Servir immédiatement.",
                'duration' => 15,
            ],
            [
                'title' => 'Ratatouille Provençale',
                'content' => "1. Couper tous les légumes en dés.\n2. Faire revenir l'oignon et l'ail.\n3. Ajouter les légumes un par un.\n4. Assaisonner avec les herbes de Provence.\n5. Laisser mijoter 45 minutes.",
                'duration' => 60,
            ],
            [
                'title' => 'Quiche Lorraine',
                'content' => "1. Préparer la pâte brisée.\n2. Faire revenir les lardons.\n3. Battre les œufs avec la crème.\n4. Assembler dans le moule.\n5. Cuire au four 35 minutes.",
                'duration' => 50,
            ],
            [
                'title' => 'Poulet au Curry',
                'content' => "1. Couper le poulet en morceaux.\n2. Faire revenir avec l'oignon.\n3. Ajouter le curry et le lait de coco.\n4. Laisser mijoter 25 minutes.\n5. Servir avec du riz basmati.",
                'duration' => 40,
            ],
            [
                'title' => 'Tarte aux Pommes',
                'content' => "1. Préparer la pâte sablée.\n2. Éplucher et couper les pommes.\n3. Disposer sur la pâte.\n4. Saupoudrer de sucre et cannelle.\n5. Cuire au four 40 minutes.",
                'duration' => 55,
            ],
            [
                'title' => 'Soupe de Légumes',
                'content' => "1. Éplucher et couper tous les légumes.\n2. Les faire revenir dans une casserole.\n3. Ajouter l'eau et le bouillon.\n4. Laisser cuire 30 minutes.\n5. Mixer et assaisonner.",
                'duration' => 35,
            ],
            [
                'title' => 'Risotto aux Champignons',
                'content' => "1. Faire revenir l'oignon dans l'huile.\n2. Ajouter le riz et nacrer.\n3. Incorporer le bouillon louche par louche.\n4. Ajouter les champignons.\n5. Terminer avec du parmesan.",
                'duration' => 35,
            ],
        ];

        foreach ($recipeData as $data) {
            $recipe = new Recipe();
            $recipe->setTitle($data['title']);
            $recipe->setSlug(strtolower($slugger->slug($data['title'])->toString()));
            $recipe->setContent($data['content']);
            $recipe->setDuration($data['duration']);
            $recipe->setCreatedAt(new \DateTimeImmutable($faker->dateTimeBetween('-6 months')->format('Y-m-d H:i:s')));
            $recipe->setUpdatedAt(new \DateTimeImmutable());
            $recipe->setAuthor($faker->randomElement($users));

            $manager->persist($recipe);
            $recipes[] = $recipe;
        }

        // Créer des recettes supplémentaires générées automatiquement
        for ($i = 0; $i < 10; ++$i) {
            $recipe = new Recipe();
            $recipe->setTitle($faker->sentence(3));
            $recipe->setSlug(strtolower($slugger->slug($recipe->getTitle())->toString()));
            $recipe->setContent($this->generateRecipeContent($faker));
            $recipe->setDuration($faker->numberBetween(10, 120));
            $recipe->setCreatedAt(new \DateTimeImmutable($faker->dateTimeBetween('-1 year')->format('Y-m-d H:i:s')));
            $recipe->setUpdatedAt(new \DateTimeImmutable());
            $recipe->setAuthor($faker->randomElement($users));

            $manager->persist($recipe);
            $recipes[] = $recipe;
        }

        // Créer des quantités (associations recette-ingrédient)
        $units = ['g', 'kg', 'ml', 'l', 'cuillère à soupe', 'cuillère à café', 'pièce(s)', 'tranche(s)', 'gousse(s)', 'pincée'];

        foreach ($recipes as $recipe) {
            // Chaque recette aura entre 3 et 8 ingrédients
            $nbIngredients = $faker->numberBetween(3, 8);
            $recipeIngredients = $faker->randomElements($ingredients, $nbIngredients);

            foreach ($recipeIngredients as $ingredient) {
                $quantity = new Quantity();
                $quantity->setRecipe($recipe);
                $quantity->setIngredient($ingredient);
                $quantity->setQuantity($faker->randomFloat(1, 0.5, 500));
                $quantity->setUnit($faker->randomElement($units));

                $manager->persist($quantity);
            }
        }

        $manager->flush();
    }

    private function generateRecipeContent($faker): string
    {
        $steps = [];
        $nbSteps = $faker->numberBetween(3, 7);

        $actions = [
            'Faire revenir', 'Couper', 'Mélanger', 'Cuire', 'Ajouter', 'Incorporer',
            'Laisser mijoter', 'Assaisonner', 'Servir', 'Préparer', 'Chauffer',
            'Réduire', 'Mixer', 'Fouetter', 'Émincer', 'Hacher',
        ];

        for ($i = 1; $i <= $nbSteps; ++$i) {
            $action = $faker->randomElement($actions);
            $complement = $faker->sentence(4);
            $steps[] = "{$i}. {$action} {$complement}";
        }

        return implode("\n", $steps);
    }
}
