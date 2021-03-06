<?php

namespace App\DataFixtures;

use App\Entity\AgeGroup;
use App\Entity\Reservation;
use App\Entity\User;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ReservationFixtures extends Fixture implements DependentFixtureInterface
{
    private array $reservations = [
        [
            'name'       => 'Zomerkamp 2022',
            'age_group'  => 'Scouts',
            'date_start' => '+0 days',
            'date_end'   => '+10 days',
            'created_by' => 'Alexander de Admin',
            'users'      => ['Marit de Materiaalmeester', 'Ursula de User'],
        ],
        [
            'name'       => 'Expeditie 2022',
            'age_group'  => 'Explorers',
            'date_start' => '+0 days',
            'date_end'   => '+7 days',
            'created_by' => 'Marit de Materiaalmeester',
            'users'      => ['Marit de Materiaalmeester', 'Ursula de User'],
        ],
        [
            'name'       => 'Weekendkamp',
            'age_group'  => 'Stam',
            'date_start' => '+0 days',
            'date_end'   => '+3 days',
            'created_by' => 'Ursula de User',
            'users'      => [],
        ],
    ];

    /**
     * @throws \Exception
     */
    public function load(ObjectManager $manager)
    {
        foreach ($this->reservations as $aReservation) {
            $reservation = new Reservation();
            $reservation->setName($aReservation['name']);
            /** @var AgeGroup $ageGroupReference */
            $ageGroupReference = $this->getReference('age_group_' . $aReservation['age_group']);
            $reservation->setAgeGroup($ageGroupReference);
            $reservation->setDateStart((new DateTime(date('Y/m/d', strtotime($aReservation['date_start'])))));
            $reservation->setDateEnd((new DateTime(date('Y/m/d', strtotime($aReservation['date_end'])))));
            /** @var User $userReference */
            $userReference = $this->getReference('user_' . $aReservation['created_by']);
            $reservation->setCreatedBy($userReference);
            if (!empty($aReservation['users'])) {
                foreach ($aReservation['users'] as $aUser) {
                    /** @var User $userReference */
                    $userReference = $this->getReference('user_' . $aUser);
                    $reservation->addUser($userReference);
                }
            }

            $manager->persist($reservation);
            $manager->flush();

            $this->addReference('reservation_' . $reservation->getName(), $reservation);
        }

        $manager->flush();
    }

    public function getDependencies(): iterable
    {
        return [
            UserFixtures::class,
        ];
    }
}
