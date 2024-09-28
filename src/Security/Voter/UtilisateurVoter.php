<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\SecurityBundle\Security;

final class UtilisateurVoter extends Voter
{
    public const USER_DELETE = 'USER_DELETE';
    public const USER_EDIT = 'USER_EDIT';

    public function __construct(private Security $security)
    {
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::USER_EDIT, self::USER_DELETE])
            && $subject instanceof \App\Entity\Utilisateur;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof UserInterface) {
            return false;
        }

        $isUserConnectedAdmin = $this->security->isGranted('ROLE_ADMIN');
        $isSubjectAdmin = in_array("ROLE_ADMIN",$subject->getRoles());

        switch ($attribute) {
            case self::USER_DELETE:

                if($isSubjectAdmin && $user !== $subject) {
                    return false;
                }
                else if($isUserConnectedAdmin)
                {
                    return true;
                }
                else if($subject !== $user)
                {
                    return false;
                }

                return true;

            case self::USER_EDIT:
                if($subject !== $user)
                {
                    return false;
                }

                return true;
        }

        return false;
    }
}
