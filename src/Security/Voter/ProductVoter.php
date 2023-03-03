<?php

namespace App\Security\Voter;


use App\Entity\Products;
use phpDocumentor\Reflection\Types\True_;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class ProductVoter extends Voter
{
    const EDIT = 'PRODUCT_EDIT';
    const DELETE = 'PRODUCT_DELETE';
    const UPDATE = 'PRODUCT_UPDATE';

    private $security;

    protected  function __construct( Security $security)
    {
        $this->security = $security;
    }


    protected function supports( $attribute, $products): bool
    {
        // TODO: Implement supports() method
        if (!in_array($attribute, [self::EDIT,self::DELETE, self::UPDATE])){
            return false;
        }

        // on verifier si user a role Admin
        if ( !$products instanceof Products) {

            return  false;
        }

        return  true;
    }

    protected function voteOnAttribute(string $attribute, $products, TokenInterface $token): bool
    {
        // get user bx token
        $user = $token->getUser();

        if (!$user instanceof UserInterface) {
            return  false;
        }

        // on verifier si user a role Admin
        if($this->security->isGranted('ROLE_ADMIN')){
            return  true;
        }
        // on verifier les permissions
        switch ($attribute) {

            case self::EDIT:
                // on verifie si utlkisateur peut editer
                $this->canEdit();
                break;
            case  self::DELETE:
                // on verifie si il peut delete
                $this->canDelete();
                break;
        }

        return  true;
    }
    private function canEdit(){
        return $this->security->isGranted('ROLE_PRODUCTS_ADMIN');
    }

    private function canDelete(){
        return $this->security->isGranted('ROLE_PRODUCTS_ADMIN');
    }
}