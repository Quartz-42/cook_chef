<?php

namespace App\EventListener;

use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Translation\LocaleSwitcher;

final class UserLocaleListener
{
    public function __construct(
        private readonly Security $security,
        private readonly LocaleSwitcher $localeSwitcher,
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
