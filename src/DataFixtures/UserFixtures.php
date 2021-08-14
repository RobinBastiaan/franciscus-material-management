<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private array $users = [
        [
            'name'           => 'Admin',
            'email'          => 'aad@min.com',
            'plain_password' => 'admin',
            'roles'          => [User::ROLE_ROOT],
            'age_group'      => ['Overig'],
        ],
        [
            'name'           => 'Materiaalmeester',
            'email'          => 'materiaal@meester.com',
            'plain_password' => 'materiaal',
            'roles'          => [User::ROLE_MATERIAL_MASTER],
            'age_group'      => ['Overig'],
        ],
        [
            'name'           => 'User',
            'email'          => 'user@user.com',
            'plain_password' => 'user',
            'roles'          => [User::ROLE_USER],
            'age_group'      => ['Overig'],
        ],
    ];

    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager)
    {
        foreach ($this->users as $aUser) {
            $user = new User();
            $user->setName($aUser['name']);
            $user->setEmail($aUser['email']);
            $user->setPassword($this->passwordHasher->hashPassword($user, $aUser['plain_password']));
            $user->setRoles($aUser['roles']);
            $user->setAgeGroup($aUser['age_group']);

            $manager->persist($user);

            $this->addReference('user_' . $user->getName(), $user);

            $manager->flush();
        }

        $manager->flush();
    }
}
