<?php

namespace App\Repository;

use App\Entity\Pitch;
use App\Entity\Slot;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpKernel\Exception\PreconditionFailedHttpException;

/**
 * @method Slot|null find($id, $lockMode = null, $lockVersion = null)
 * @method Slot|null findOneBy(array $criteria, array $orderBy = null)
 * @method Slot[]    findAll()
 * @method Slot[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SlotRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Slot::class);
    }

    public function isAvailableSlot(Pitch $pitch, Slot $slot): void
    {
        $slotInUse = $this->createQueryBuilder('p')
            ->select('COUNT(p.id)')
            ->where('(p.starts > :starts AND p.starts < :ends) OR (p.ends > :starts AND p.ends < :ends)')
            // ->where('(p.starts BETWEEN :starts AND :ends) OR (p.ends BETWEEN :starts AND :ends)')
            ->setParameter('starts', $slot->getStarts())
            ->setParameter('ends', $slot->getEnds())
            ->getQuery()->getSingleScalarResult()
        ;

        if ($slotInUse) {
            throw new PreconditionFailedHttpException(
                "The slot from {$slot->getStarts()->format('H:i:s')} to {$slot->getEnds()->format('H:i:s')} is unavailable.",
                null, 
                412
            );
        }
        
    }

    // /**
    //  * @return Slot[] Returns an array of Slot objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Slot
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
