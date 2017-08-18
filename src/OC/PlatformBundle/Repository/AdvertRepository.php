<?php

namespace OC\PlatformBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * AdvertRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class AdvertRepository extends \Doctrine\ORM\EntityRepository
{
    public function getAdverts()
    {
        $query = $this->createQueryBuilder('a')
            //Jointure sur l'attribut image
            ->leftJoin('a.image', 'i')
            ->addSelect('i')
            //Jointure sur l'attribut categories
            ->leftJoin('a.categories', 'c')
            ->addSelect('c')
            ->orderBy('a.date', 'desc')
            ->getQuery();

        //On ne retourne pas le résultat ici mais juste la requête pour s'en reservir pour notre paginator (via knp-paginator)
        return $query;
    }

    /**
     * @param int $days Paramètre de notre requête
     * @return array
     */
    public function getAdvertsForPurge($days)
    {
        $query = $this->createQueryBuilder('a')
            //Méthode pour comparer 2 dates "date_diff(expr1, expr2)"
            ->where('date_diff(a.updatedAt, a.date) > :days')
            ->setParameter('days', $days)
            ->andWhere('a.applications IS EMPTY')
            ->getQuery();
        return $query->getResult();
    }

    public function findByIp($ip)
    {
        $query = $this->createQueryBuilder('a')
            ->where('a.ip = :ip')
            ->setParameter('ip', $ip)
            ->getQuery();

        return $query->getResult();
    }
}
