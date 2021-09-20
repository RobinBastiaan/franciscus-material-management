<?php declare(strict_types=1);

namespace App\Repository;

use App\Controller\Admin\LoanCrudController;
use App\Controller\Admin\ReservationCrudController;
use App\Entity\Loan;
use App\Entity\Reservation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;

/**
 * @method Reservation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reservation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reservation[]    findAll()
 * @method Reservation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReservationRepository extends ServiceEntityRepository
{
    private $adminUrlGenerator;

    public function __construct(ManagerRegistry $registry, AdminUrlGenerator $adminUrlGenerator)
    {
        parent::__construct($registry, Reservation::class);

        $this->adminUrlGenerator = $adminUrlGenerator;
    }

    public function openReservations(): array
    {
        $query = $this->createQueryBuilder('r')
            ->select('r.id, r.name, r.dateEnd, COUNT(l.id) as notHandedInCount')
            ->addSelect('(SELECT COUNT(l2.id) FROM App\Entity\Loan l2 WHERE l2.reservation = r.id AND l2.deletedAt IS NULL) AS totalCount')
            ->addSelect('(SELECT a.name FROM App\Entity\AgeGroup a WHERE a.id = r.ageGroup AND a.deletedAt IS NULL) AS ageGroup')
            ->join(Loan::class, 'l', Join::WITH, 'r.id = l.reservation')
            ->andWhere('r.dateEnd <= CURRENT_DATE()')
            ->andWhere('r.deletedAt IS NULL')
            ->andwhere('l.deletedAt IS NULL')
            ->andwhere('l.returnedState IS NULL')
            ->groupBy('l.reservation')
            ->getQuery();
        $result = $query->getResult();

        foreach ($result as $key => $item) {
            $result[$key]['reservation_url'] = $this->adminUrlGenerator
                ->setController(ReservationCrudController::class)
                ->setAction(Action::DETAIL)
                ->setEntityId($item['id'])
                ->generateUrl();

            $result[$key]['material_url'] = $this->adminUrlGenerator
                ->setController(LoanCrudController::class)
                ->setAction(Action::INDEX)
                ->set('filters', [
                    'reservation' => [
                        'comparison' => '=',
                        'value'      => $item['id'],
                    ],
                    'deletedAt'   => [
                        'value' => 'null',
                    ],
                ])
                ->generateUrl();
        }

        return $result;
    }
}
