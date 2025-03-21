<?php

namespace App\EventListener;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Http\SecurityEvents;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;

final class UserLastConnectionListener
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    #[AsEventListener(event: 'security.interactive_login')]
    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event): void
    {
        //获取用户
        $user = $event->getAuthenticationToken()->getUser();
        if ($user instanceof User) {
            //更新最后登录时间
            $user->setLastLoginAt(new \DateTimeImmutable());
            
            $this->entityManager->flush();
        }

    }
}
