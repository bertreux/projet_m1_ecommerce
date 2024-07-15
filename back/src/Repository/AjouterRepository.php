<?php

namespace App\Back\Repository;

use App\Back\Entity\Ajouter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Ajouter>
 *
 * @method Ajouter|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ajouter|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ajouter[]    findAll()
 * @method Ajouter[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AjouterRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ajouter::class);
    }

    public function save(Ajouter $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Ajouter $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


}
