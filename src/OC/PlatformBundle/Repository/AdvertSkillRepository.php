<?php

namespace OC\PlatformBundle\Repository;

/**
 * AdvertSkillRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class AdvertSkillRepository extends \Doctrine\ORM\EntityRepository
{
    public function getSkillsByAdvert($advertId)
    {
        $query = $this->createQueryBuilder('ads')
            //Jointure sur l'atribut advertSkills
            ->leftJoin('ads.advert', 'a')
            ->addSelect('a')
            ->leftJoin('ads.skill', 's')
            ->addSelect('s')
            ->where('a.id = :id')
            ->setParameter('id', $advertId)
            ->getQuery();

        return $query->getResult();
    }
}