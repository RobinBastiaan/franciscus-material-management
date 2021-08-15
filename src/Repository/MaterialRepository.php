<?php declare(strict_types=1);

namespace App\Repository;

use App\Entity\Material;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Material|null find($id, $lockMode = null, $lockVersion = null)
 * @method Material|null findOneBy(array $criteria, array $orderBy = null)
 * @method Material[]    findAll()
 * @method Material[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MaterialRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Material::class);
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
                m.value * (1 - ((YEAR(CURRENT_DATE()) - YEAR(m.dateBought)) / m.depreciationYears))
            ))
            FROM App\Entity\Material m'
        );
        $currentValue = $query->getSingleScalarResult();

        return [
            'value'         => $value,
            'current_value' => $currentValue,
        ];
    }
}
