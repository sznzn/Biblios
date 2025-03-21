<?php

namespace App\Security\Voter;


use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use App\Entity\Book;
use App\Entity\User;



class BookCreatorVoter extends Voter
{
    protected function supports(string $attribute, mixed $subject): bool
    {
        return 'book.is_creator' === $attribute && $subject instanceof Book;
    }
    
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        
        if(!$user instanceof User){
            return false;
        }
        /** @var Book $subject */
    
        return $user === $subject->getCreatedBy();
    }
    
}