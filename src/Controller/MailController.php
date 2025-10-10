<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Form\ContactType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use App\DTO\ContactDTO;

final class MailController extends AbstractController
{
    #[Route('/contact', name: 'contact')]
    public function index(Request $request, MailerInterface $mailer): Response
    {
        $data = new ContactDTO();
        $form = $this->createForm(ContactType::class, $data);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contactData = $form->getData();
                 $message = (new Email())
                ->from($contactData->getMail())
                ->to('recipient@example.com')
                ->subject('Nouveau message de contact de la part de ' . $contactData->getName())
                ->text($contactData->getMessage());

                try {
                    $mailer->send($message);
                    $this->addFlash('success', 'Votre message a été envoyé avec succès !');
                } catch (\Exception $e) {
                    $this->addFlash('error', 'Une erreur est survenue lors de l\'envoi de votre message. Veuillez réessayer plus tard.');
                    return $this->redirectToRoute('contact');
                }
                

            return $this->redirectToRoute('contact');
        }

        return $this->render('mail/contact.html.twig', [
            'form' => $form,
        ]);
    }
}
