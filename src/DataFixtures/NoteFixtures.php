<?php

namespace App\DataFixtures;

use App\Entity\Loan;
use App\Entity\Material;
use App\Entity\Note;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class NoteFixtures extends Fixture implements DependentFixtureInterface
{
    private array $notes = [
        [
            'text'       => 'We hebben hem afgelopen jaar nog geïmpregneerd. Dit is waar we hem hebben gekocht: www.winkel.nl/impregneermiddel',
            'created_by' => 'Marit de Materiaalmeester',
            'material'   => 'Groepstent',
            'loan'       => 'Zomerkamp 2022/Groepstent',
        ],
    ];

    /**
     * @throws \Exception
     */
    public function load(ObjectManager $manager)
    {
        foreach ($this->notes as $aNote) {
            $note = new Note();
            $note->setText($aNote['text']);
            /** @var User $userReference */
            $userReference = $this->getReference('user_' . $aNote['created_by']);
            $note->setCreatedBy($userReference);
            /** @var Material $materialReference */
            $materialReference = $this->getReference('material_' . $aNote['material']);
            $note->setMaterial($materialReference);
            /** @var Loan $loanReference */
            $loanReference = $this->getReference('loan_' . $aNote['loan']);
            $note->setLoan($loanReference);

            $manager->persist($note);
            $manager->flush();

            $this->addReference('note_' . $note->getId(), $note);
        }

        $manager->flush();
    }

    public function getDependencies(): iterable
    {
        return [
            UserFixtures::class,
            MaterialFixtures::class,
            LoanFixtures::class,
        ];
    }
}
