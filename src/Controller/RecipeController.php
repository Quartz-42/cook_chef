<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\RecipeRepository;
use App\Form\RecipeType;
use App\Entity\Recipe;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

final class RecipeController extends AbstractController
{
    #[Route('/recettes', name: 'recipe.index')]
    public function index(RecipeRepository $recipeRepository): Response
    {
        $recipes = $recipeRepository->findAll();
        $totalDuration = $recipeRepository->findTotalDuration();

        return $this->render('recipe/index.html.twig', [
            'recipes' => $recipes,
            'totalDuration' => $totalDuration,
        ]);
    }

    #[Route('/recettes/{slug}-{id}', name: 'recipe.show', requirements: ['slug' => '[a-z0-9\-]*', 'id' => '\d+'])]
    public function show(string $slug, int $id, RecipeRepository $recipeRepository): Response
    {
        $recipe = $recipeRepository->find($id);

        if ($recipe->getSlug() !== $slug) {
            return $this->redirectToRoute('recipe.show', ['slug' => $recipe->getSlug(), 'id' => $recipe->getId()]);
        }

        return $this->render('recipe/show.html.twig', [
            'recipe' => $recipe,
        ]);
    }

    #[Route('/recettes/{id}/edit', name: 'recipe.edit', requirements: ['id' => '\d+'])]
    public function edit(Recipe $recipe, Request $request, EntityManagerInterface $em): Response
    {
       $form = $this->createForm(RecipeType::class, $recipe);

       $form->handleRequest($request);

       if($form->isSubmitted() && $form->isValid()) {
           $em->flush();

           $this->addFlash('success', 'Recette modifiée avec succès');

           return $this->redirectToRoute('recipe.show', [
               'id' => $recipe->getId(),
               'slug' => $recipe->getSlug()
           ]);
        }

        return $this->render('recipe/edit.html.twig', [
            'recipe' => $recipe,
            'form' => $form
        ]);
    }

    #[Route('/recettes/{id}/delete', name: 'recipe.delete', requirements: ['id' => '\d+'])]
    public function delete(Recipe $recipe, EntityManagerInterface $em): Response
    {
        $em->remove($recipe);
        $em->flush();

        $this->addFlash('success', 'Recette supprimée avec succès');
        return $this->redirectToRoute('recipe.index');
    }
}