<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Repository\RecipeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\SecurityBundle\Security;
use App\Security\Voter\RecipeVoter;

#[Route('/recettes', name: 'recipe.')]
#[IsGranted('ROLE_USER')]
final class RecipeController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(RecipeRepository $recipeRepository, Request $request, Security $security): Response
    {
        $page = $request->query->getInt('page', 1);
        /**
         * @var \App\Entity\User $user
         */
        $user = $security->getUser();
        $canListAll = $security->isGranted(RecipeVoter::LIST_ALL);
        $maxResults = 1;
        $recipes = $recipeRepository->paginateRecipes($page, $maxResults, $canListAll ? null : $user->getId());
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