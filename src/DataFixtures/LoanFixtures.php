<?php

namespace App\DataFixtures;

use App\Entity\Loan;
use App\Entity\Material;
use App\Entity\Reservation;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class LoanFixtures extends Fixture implements DependentFixtureInterface
{
    private array $loans = [
        [
            'reservation'   => 'Zomerkamp 2022',
            'material'      => 'Groepstent',
            'date_returned' => '+7 days',
        ],
        [
            'reservation' => 'Zomerkamp 2022',
            'material'    => 'Gasfles',
        ],
        [
            'reservation' => 'Zomerkamp 2022',
            'material'    => 'Afwasteiltje',
        ],
    ];

    /**
     * @throws \Exception
     */
    public function load(ObjectManager $manager)
    {
        foreach ($this->loans as $aLoan) {
            $loan = new Loan();
            /** @var Reservation $reservationReference */
            $reservationReference = $this->getReference('reservation_' . $aLoan['reservation']);
            $loan->setReservation($reservationReference);
            /** @var Material $materialReference */
            $materialReference = $this->getReference('material_' . $aLoan['material']);
            $loan->setLoanedMaterial($materialReference);
            if (isset($aLoan['date_returned'])) {
                $loan->setDateReturned((new DateTime(date('Y/m/d', strtotime($aLoan['date_returned'])))));
            }

            $manager->persist($loan);
            $manager->flush();

            $this->addReference('loan_' . $loan->getReservation() . '/' . $loan->getLoanedMaterial(), $loan);
        }

        $manager->flush();
    }

    public function getDependencies(): iterable
    {
        return [
            ReservationFixtures::class,
            MaterialFixtures::class,
        ];
    }
}
