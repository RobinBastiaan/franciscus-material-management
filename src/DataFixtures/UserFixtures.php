<?php

namespace App\DataFixtures;

use App\Entity\AgeGroup;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private array $users = [
        [
            'name'           => 'Alexander de Admin',
            'email'          => 'aad@min.com',
            'plain_password' => 'aadmin',
            'roles'          => [User::ROLE_ADMIN],
            'age_groups'     => ['Overig'],
        ],
        [
            'name'           => 'Marit de Materiaalmeester',
            'email'          => 'materiaal@meester.com',
            'plain_password' => 'materiaal',
            'roles'          => [User::ROLE_MATERIAL_MASTER],
            'age_groups'     => ['Overig', 'Scouts'],
        ],
        [
            'name'           => 'Ursula de User',
            'email'          => 'user@user.com',
            'plain_password' => 'ursula',
            'roles'          => [User::ROLE_USER],
            'age_groups'     => ['Overig', 'Parcival'],
        ],
        [
            'name'           => 'Robin Bastiaan',
            'email'          => 'robinbastiaan@gmail.com',
            'plain_password' => 'robinrobin',
            'roles'          => [User::ROLE_USER],
            'age_groups'     => ['Scouts'],
        ],
        [
            'name'           => 'Mark Meuleman',
            'email'          => 'mmeuleman@hotmail.com',
            'plain_password' => 'markmark',
            'roles'          => [User::ROLE_ADMIN],
            'age_groups'     => ['Overig'],
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
            if (!empty($aUser['age_groups'])) {
                foreach ($aUser['age_groups'] as $anAgeGroup) {
                    /** @var AgeGroup $ageGroupReference */
                    $ageGroupReference = $this->getReference('age_group_' . $anAgeGroup);
                    $user->addAgeGroup($ageGroupReference);
                }
            }

            $manager->persist($user);
            $manager->flush();

            $this->addReference('user_' . $user->getName(), $user);
        }

        $manager->flush();
    }
}
