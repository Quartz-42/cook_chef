<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Repository\RecipeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/recettes', name: 'recipe.')]
#[IsGranted('ROLE_USER')]
final class RecipeController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(RecipeRepository $recipeRepository): Response
    {
        $recipes = $recipeRepository->findAll();
        $totalDuration = $recipeRepository->findTotalDuration();

        return $this->render('recipe/index.html.twig', [
            'recipes' => $recipes,
            'totalDuration' => $totalDuration,
        ]);
    }

    #[Route('/{slug}-{id}', name: 'show', requirements: ['id' => '\d+', 'slug' => '[a-z0-9\-]*'], methods: ['GET'])]
    public function show(Recipe $recipe, string $slug): Response
    {
        if ($recipe->getSlug() !== $slug) {
            return $this->redirectToRoute('recipe.show', [
                'slug' => $recipe->getSlug(),
                'id' => $recipe->getId()
            ], 301);
        }

        return $this->render('recipe/show.html.twig', [
            'recipe' => $recipe,
        ]);
    }
}