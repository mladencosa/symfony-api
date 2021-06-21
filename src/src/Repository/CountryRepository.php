<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Country;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\ParameterType;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Country|null find($id, $lockMode = null, $lockVersion = null)
 * @method Country|null findOneBy(array $criteria, array $orderBy = null)
 * @method Country[]    findAll()
 * @method Country[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CountryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Country::class);
    }

    /**
     * @param string $iso
     * @return int|mixed|string|null
     * @throws NonUniqueResultException
     */
    public function findByIso(string $iso)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.iso2 = (:iso) OR c.iso3 = (:iso)')
            ->setParameter('iso', $iso, ParameterType::STRING)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
