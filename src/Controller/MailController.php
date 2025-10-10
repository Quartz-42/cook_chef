<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Form\ContactType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

final class MailController extends AbstractController
{
    #[Route('/mail', name: 'contact')]
    public function index(Request $request, MailerInterface $mailer): Response
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contactData = $form->getData();
                 $message = (new Email())
                ->from($contactData->getMail())
                ->to('recipient@example.com')
                ->subject('Nouveau message de contact de la part de ' . $contactData->getNom())
                ->text($contactData->getMessage());

            $mailer->send($message);
            
            return $this->redirectToRoute('contact');
        }

        return $this->render('mail/index.html.twig', [
            'form' => $form,
        ]);
    }
}
