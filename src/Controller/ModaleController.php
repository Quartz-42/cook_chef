<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Recipe;
use App\Form\RecipeType;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/modale', name: 'modale.')]
final class ModaleController extends AbstractController
{
    #[Route('/index', name: 'index')]
    public function index(): Response
    {
        return $this->render('modale/modale_index.html.twig');
    }

    #[Route('/create', name: 'create')]
    public function createModale(Request $request, EntityManagerInterface $em): Response
    {
        $recipe = new Recipe();
        $form = $this->createForm(RecipeType::class, $recipe);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($recipe);
            $em->flush();

            $this->addFlash('success', 'Recette ajoutée avec succès');

            return $this->redirectToRoute('modale');
        }

        return $this->render('modale/create.html.twig', [
            'form' => $form,
        ]);
    }
}