<?php

namespace App\EventListener;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Translation\LocaleSwitcher;
use App\Entity\User;

final class UserLocaleListener
{
        public function __construct(
            private readonly Security $security,
            private readonly LocaleSwitcher $localeSwitcher
        ) {

     }

    #[AsEventListener(event: KernelEvents::REQUEST, priority: -20)]
    public function onKernelRequestEvent(): void
    {
        $user = $this->security->getUser();
        if ($user && $user instanceof User) {
            $this->localeSwitcher->setLocale($user->getLocale());
        }
    }
}
