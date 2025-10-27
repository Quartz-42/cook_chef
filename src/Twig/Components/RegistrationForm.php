<?php

namespace App\Twig\Components;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\Component\Form\FormView;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\Component\Form\FormInterface;
use App\Entity\User;
use App\Form\RegistrationFormType;
use Symfony\UX\LiveComponent\Attribute\LiveProp;

#[AsLiveComponent]
class RegistrationForm extends AbstractController
{
    use DefaultActionTrait;
    use ComponentWithFormTrait;

    #[LiveProp]
    public ?User $initialformdata = null;

    protected function instantiateForm(): FormInterface
    {
        $user = $this->initialformdata ?? new User();

        return $this->createForm(RegistrationFormType::class, $user);
    }
}