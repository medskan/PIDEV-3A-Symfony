<?php

namespace App\Repository;

use App\Entity\Produit;
use App\Entity\ProduitSearch;
use App\Entity\SearchData;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;


/**
 * @method Produit|null find($id, $lockMode = null, $lockVersion = null)
 * @method Produit|null findOneBy(array $criteria, array $orderBy = null)
 * @method Produit[]    findAll()
 * @method Produit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProduitRepository extends ServiceEntityRepository
{
    /**
     * @var PaginatorInterface
     */
    private $paginator;

    public function __construct(ManagerRegistry $registry , PaginatorInterface $paginator)
    {
        parent::__construct($registry, Produit::class);
        $this->paginator = $paginator;
    }


    // /**
    //  * @return Produit[] Returns an array of Produit objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Produit
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    /**
     * return Produit[]
     * @param SearchData $search
     * @return PaginationInterface
     */
    public function findSearch(SearchData $search): PaginationInterface
    {$query=$this->createQueryBuilder('p');


        if(!empty($search->q))
        {
            $query=$query->andWhere('p.nomProduit Like :q')
       ->setParameter('q',"%{$search->q}%");
        }
        if(!empty($search->max)){
            $query=$query->andWhere('p.prixProduit <= :max')
                ->setParameter('max',$search->max);
        }
        if(!empty($search->min)){
            $query=$query->andWhere('p.prixProduit >= :min')
                ->setParameter('min',$search->min);
        }
        if(!empty($search->categories)){
            $query=$query->andWhere('p.categorie In (:categories)')
                ->setParameter('categories',$search->categories);
        }


      $query= $query->getQuery();
           return $this->paginator->paginate(
               $query,
               $search->page,
               3

           );
    }
}
