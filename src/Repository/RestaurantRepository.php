<?php

namespace App\Repository;

use App\Entity\Restaurant;
use App\Restaurant\RestaurantDate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Restaurant|null find($id, $lockMode = null, $lockVersion = null)
 * @method Restaurant|null findOneBy(array $criteria, array $orderBy = null)
 * @method Restaurant[]    findAll()
 * @method Restaurant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RestaurantRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Restaurant::class);
    }

    public function search(string $searchTerm, RestaurantDate $date = null)
    {
        if (null == $date) {
            return $this->searchByName($searchTerm);
        }

        return $this->searchByNameAndOpeningHours($searchTerm, $date);
    }

    private function searchByName(string $searchTerm)
    {
        return $this->createQueryBuilder('r')
            ->where('r.title LIKE :term')
            ->orWhere('r.cuisine LIKE :term')
            ->orWhere('r.description LIKE :term')
            ->setParameter('term', '%'.$searchTerm.'%')
            ->setMaxResults(100)
            ->getQuery()
            ->getResult();
    }

    private function searchByNameAndOpeningHours(string $searchTerm, RestaurantDate $date)
    {
        return $this->createQueryBuilder('r')
            ->innerJoin('r.openingIntervals', 'i')
            ->where('r.title LIKE :term')
            ->orWhere('r.cuisine LIKE :term')
            ->orWhere('r.description LIKE :term')
            ->andWhere('i.dayInWeek = :day_number')
            ->andWhere('i.openAt <= :time')
            ->andWhere('i.closeAt >= :time')
            ->setParameter('term', '%'.$searchTerm.'%')
            ->setParameter('day_number', $date->getDayInWeek())
            ->setParameter('time', $date->getTime())
            ->setMaxResults(100)
            ->getQuery()
            ->getResult();
    }

    public function deleteAll()
    {
        $this->getEntityManager()->createQuery('DELETE '.Restaurant::class)->execute();
    }
}
