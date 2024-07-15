<?php

namespace App\Front\Repository;

use App\Front\Entity\Categorie;
use App\Front\Entity\Produit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Produit>
 *
 * @method Produit|null find($id, $lockMode = null, $lockVersion = null)
 * @method Produit|null findOneBy(array $criteria, array $orderBy = null)
 * @method Produit[]    findAll()
 * @method Produit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProduitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Produit::class);
    }

    public function save(Produit $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Produit $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findAllCarousel(): array
    {
        return $this->createQueryBuilder('p')
            ->setMaxResults(3)
            ->andWhere('p.carousel = :val')
            ->setParameter('val', true)
            ->orderBy('p.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findProductByCategorie(?Categorie $categorie)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.categorie = :val')
            ->setParameter('val', $categorie)
            ->orderBy('p.prioriter', 'ASC')
            ->addOrderBy('p.stock','DESC')
            ->getQuery()
            ->getResult();
    }

    public function findProductByHighlander()
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.highlander = :val')
            ->setParameter('val', 1)
            ->orderBy('p.prioriter', 'ASC')
            ->addOrderBy('p.stock','DESC')
            ->getQuery()
            ->getResult();
    }

}
