<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Service\UserService;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;


class UserFixtures extends Fixture
{
    public function __construct(protected UserService $service)
    {

    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setPassword($this->service->hashPassword($user, 'Test@admin31'))
            ->setRoles([UserService::ROLE_ADMIN])
            ->setEmail('admin@example.com')
            ->setFirstName('Super')
            ->setLastName('Admin')
            ->setCreated(new \DateTime('now'))
            ->setUpdated(new \DateTime('now'));

        $manager->persist($user);

        $userPas = $this->service->hashPassword($user, 'Test@user31!');
        for ($i = 0; $i < 20; $i++) {
            $user = new User();
            $user->setPassword($userPas)
                ->setRoles([UserService::ROLE_USER])
                ->setEmail("user$i@example.com")
                ->setFirstName("User$i")
                ->setLastName("Test")
                ->setCreated(new \DateTime('now'))
                ->setUpdated(new \DateTime('now'));

            $manager->persist($user);
            echo "Added user {$user->getFirstName()} {$user->getLastName()} \r\n";
        }
        $manager->flush();
    }
}
