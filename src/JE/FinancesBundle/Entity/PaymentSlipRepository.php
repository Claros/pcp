<?php

namespace JE\FinancesBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use JE\StatBundle\Entity\DateRange;

/**
 * PaymentSlipRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PaymentSlipRepository extends EntityRepository
{
    public function queryAll()
    {
        return $this->createQueryBuilder('s')
            ->orderBy('s.createdAt')
            ;
    }

    public function yearRange()
    {
        $qb = $this->createQueryBuilder('s')
            ->select('MAX(YEAR(s.createdAt)) maxCreatedAt, MIN(YEAR(s.createdAt)) minCreatedAt');

        $result = $qb->getQuery()->getSingleResult(Query::HYDRATE_ARRAY);

        return array(
            'max' => $result['maxCreatedAt'],
            'min' => $result['minCreatedAt'],
        );
    }

    public function findFromMonth($month, $year)
    {
        $qb = $this->createQueryBuilder('s')
            ->where('MONTH(s.createdAt) = :month')
            ->setParameter('month', $month)
            ->andWhere('YEAR(s.createdAt) = :year')
            ->setParameter('year', $year)
            ->orderBy('s.createdAt');

        return $qb->getQuery()->getResult();
    }

    public function sumFromMonth($month, $year)
    {
        $qb = $this->createQueryBuilder('s')
            ->select('SUM(s.amount) / 100 amount, SUM(s.numberOfDays * 4 * 9.43 * 0.156 + s.amount / 100 * 0.024) urssaf')
            ->where('MONTH(s.createdAt) = :month')
            ->setParameter('month', $month)
            ->andWhere('YEAR(s.createdAt) = :year')
            ->setParameter('year', $year);

        $result = $qb->getQuery()->getSingleResult();
        $result['totalAmount'] = $result['amount'] - $result['urssaf'];

        return $result;
    }

    public function sumFromRange(DateRange $range)
    {
        $qb = $this->createQueryBuilder('s')
            ->select('SUM(s.amount) / 100 amount, SUM(s.numberOfDays * 4 * 9.43 * 0.156 + s.amount / 100 * 0.024) urssaf, MONTH(s.createdAt) month, YEAR(s.createdAt) year')
            ->where('s.createdAt >= :from')
            ->setParameter('from', $range->getFrom())
            ->andWhere('s.createdAt <= :to')
            ->setParameter('to', $range->getTo())
            ->groupBy('year, month')
            ->orderBy('year, month')
        ;

        $data = array_map(function(){
            return array(
                'amount' => 0,
                'urssaf' => 0,
                'totalAmount' => 0,
            );
        },$range->getMonthsArray());

        $results = $qb->getQuery()->getResult();
        foreach($results as $result){
            $data[$result['year'].$result['month']] = array(
                'amount' => $result['amount'],
                'urssaf' => $result['urssaf'],
                'totalAmount' => $result['amount'] - $result['urssaf'],
            );
        }

        return $data;
    }
}
