<?php declare(strict_types=1);

namespace App\Repository;

use App\Controller\Admin\MaterialCrudController;
use App\Entity\Material;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;

/**
 * @method Material|null find($id, $lockMode = null, $lockVersion = null)
 * @method Material|null findOneBy(array $criteria, array $orderBy = null)
 * @method Material[]    findAll()
 * @method Material[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MaterialRepository extends ServiceEntityRepository
{
    private AdminUrlGenerator $adminUrlGenerator;

    public function __construct(ManagerRegistry $registry, AdminUrlGenerator $adminUrlGenerator)
    {
        parent::__construct($registry, Material::class);

        $this->adminUrlGenerator = $adminUrlGenerator;
    }

    public function totals(): array
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT SUM(m.value)
            FROM App\Entity\Material m'
        );
        $value = $query->getSingleScalarResult();

        $query = $entityManager->createQuery(
            'SELECT SUM(GREATEST(0,
                GREATEST(0, m.value - m.residualValue) * GREATEST(0, 1 - (YEAR(CURRENT_DATE()) - YEAR(m.dateBought)) / m.depreciationYears) + m.residualValue
            ))
            FROM App\Entity\Material m
            WHERE m.depreciationYears IS NOT NULL'
        );
        $currentValue = $query->getSingleScalarResult();

        $query = $entityManager->createQuery(
            'SELECT SUM(GREATEST(0,
                GREATEST(0, m.value - m.residualValue) / m.depreciationYears / 4
            ))
            FROM App\Entity\Material m
            WHERE m.depreciationYears IS NOT NULL'
        );
        $depreciationPressure = $query->getSingleScalarResult();

        $expectedCapital = $value - $currentValue;

        return [
            'value'                 => $value,
            'current_value'         => $currentValue,
            'depreciation_pressure' => $depreciationPressure,
            'expected_capital'      => $expectedCapital,
        ];
    }

    public function status(): array
    {
        $query = $this->createQueryBuilder('m', 'm.state')
            ->select('COUNT(m.id) AS count, m.state')
            ->where('m.deletedAt IS NULL')
            ->groupBy('m.state')
            ->getQuery();
        $queryResult = $query->getResult();

        // define all result keys with the default value of 0
        $result = array_change_key_case(array_fill_keys(array_merge(Material::STATES, ['totaal']), 0), CASE_LOWER);

        foreach (Material::STATES as $state) {
            $result[strtolower($state) . '_url'] = $this->adminUrlGenerator
                ->setController(MaterialCrudController::class)
                ->setAction(Action::INDEX)
                ->set('filters', [
                    'state' => [
                        'comparison' => '=',
                        'value'      => $state,
                    ],
                ])
                ->generateUrl();

            foreach ($queryResult as $key => $item) {
                if ($key === $state) {
                    $result[strtolower($state)] = $item['count'];
                    $result['totaal'] += $item['count'];
                    break;
                }
            }
        }

        return $result;
    }
}
