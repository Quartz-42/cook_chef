<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use App\Entity\User;
use App\Entity\Recipe;

final class RecipeVoter extends Voter
{
    public const EDIT = 'RECIPE_EDIT';
    public const VIEW = 'RECIPE_VIEW';
    public const CREATE = 'RECIPE_CREATE';
    public const LIST = 'RECIPE_LIST';
    public const LIST_ALL = 'RECIPE_LIST_ALL';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::CREATE, self::LIST, self::LIST_ALL]) ||
        (
             in_array($attribute, [self::EDIT, self::VIEW, ])
        )
            && $subject instanceof \App\Entity\Recipe;
    }

    /**
     * @param Recipe|null $subject
     */
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        /**
         * @var \App\Entity\User $user
         */
        $user = $token->getUser();

            switch ($attribute) {
                case self::VIEW:
                    return true;
                    break;
        
                case self::CREATE:
                case self::LIST:
                    if (!$user instanceof User) {
                        return false;
                        break;
                    }
        
                case self::EDIT:
                    return $subject->getAuthor()->getId() === $user->getId();
                    break;
            }

            return false;
    }
}
