<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\RecipeRepository;
use App\Form\RecipeType;
use App\Entity\Recipe;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

#[Route('/admin/recettes', name: 'admin.recipe.')]
final class RecipeController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(RecipeRepository $recipeRepository): Response
    {
        $recipes = $recipeRepository->findAll();
        $totalDuration = $recipeRepository->findTotalDuration();

        return $this->render('admin/recipe/index.html.twig', [
            'recipes' => $recipes,
            'totalDuration' => $totalDuration,
        ]);
    }

    #[Route('/{id}', name: 'show', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(Recipe $recipe): Response
    {
        return $this->render('admin/recipe/show.html.twig', [
            'recipe' => $recipe,
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function edit(Recipe $recipe, Request $request, EntityManagerInterface $em): Response
    {
       $form = $this->createForm(RecipeType::class, $recipe);

       $form->handleRequest($request);

       if($form->isSubmitted() && $form->isValid()) {

           /** @var UploadedFile $thumbnailFile */
           $thumbnailFile = $form->get('thumbnailFile')->getData();
           $fileName = $recipe->getId() . '.' . $thumbnailFile->getClientOriginalExtension();
           $thumbnailFile->move($this->getParameter('kernel.project_dir').'/public/upload/recettes/images', $fileName);
           $recipe->setThumbnail($fileName);
           $em->flush();

           $this->addFlash('success', 'Recette modifiée avec succès');

           return $this->redirectToRoute('admin.recipe.show', [
               'id' => $recipe->getId()
           ]);
        }

        return $this->render('admin/recipe/edit.html.twig', [
            'recipe' => $recipe,
            'form' => $form
        ]);
    }

    #[Route('/create', name: 'create')]
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $recipe = new Recipe();
        $form = $this->createForm(RecipeType::class, $recipe);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($recipe);
            $em->flush();

            $this->addFlash('success', 'Recette ajoutée avec succès');

            return $this->redirectToRoute('admin.recipe.index');
        }

        return $this->render('admin/recipe/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'], requirements: ['id' => '\d+'])]
    public function delete(Recipe $recipe, EntityManagerInterface $em): Response
    {
        $em->remove($recipe);
        $em->flush();

        $this->addFlash('success', 'Recette supprimée avec succès');
        return $this->redirectToRoute('admin.recipe.index');
    }
}