<?php

namespace App\Controller;

use App\Repository\RecipeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(RecipeRepository $recipeRepository): Response
    {
        $recipeNumber = $recipeRepository->count([]);

        return $this->render('magic.html.twig', [
            'recipeNumber' => $recipeNumber,
        ]);
    }
}
