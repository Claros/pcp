<?php

namespace JE\FinancesBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use JE\StatBundle\Entity\DateRange;

/**
 * CustomerInvoiceRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CustomerInvoiceRepository extends EntityRepository
{
    public function queryAll()
    {
        return $this->createQueryBuilder('i')
            ->orderBy('i.dueDate');
    }

    public function yearRange()
    {
        $qb = $this->createQueryBuilder('i')
            ->select('MAX(YEAR(i.issuedAt)) maxIssuedAt, MIN(YEAR(i.issuedAt)) minIssuedAt, '.
                     'MAX(YEAR(i.paidAt)) maxPaidAt, MIN(YEAR(i.paidAt)) minPaidAt, '.
                     'MAX(YEAR(i.dueDate)) maxDueDate, MIN(YEAR(i.dueDate)) minDueDate');

        $result = $qb->getQuery()->getSingleResult(Query::HYDRATE_ARRAY);

        return array(
            'max' => max($result['maxIssuedAt'], $result['maxPaidAt'], $result['maxDueDate']),
            'min' => min($result['minIssuedAt'], $result['minPaidAt'], $result['minDueDate']),
        );
    }

    public function findFromMonth($month, $year)
    {
        $qb = $this->createQueryBuilder('i')
            ->where('MONTH(i.paidAt) = :month')
            ->setParameter('month', $month)
            ->andWhere('YEAR(i.paidAt) = :year')
            ->setParameter('year', $year)
            ->andWhere('i.paid = true')
            ->orderBy('i.paidAt');

        return $qb->getQuery()->getResult();
    }

    public function sumFromMonth($month, $year)
    {
        $qb = $this->createQueryBuilder('i')
            ->select('SUM(i.amount)/100 amount, SUM(i.amount * i.taxes / 1000000) taxesAmount')
            ->where('MONTH(i.paidAt) = :month')
            ->setParameter('month', $month)
            ->andWhere('YEAR(i.paidAt) = :year')
            ->setParameter('year', $year)
            ->andWhere('i.paid = true');

        $result = $qb->getQuery()->getSingleResult();
        $result['totalAmount'] = $result['amount'] + $result['taxesAmount'];

        return $result;
    }

    public function sumFromRange(DateRange $range)
    {
        $qb = $this->createQueryBuilder('i')
            ->select('SUM(i.amount)/100 amount, SUM(i.amount * i.taxes / 1000000) taxesAmount, MONTH(i.paidAt) month, YEAR(i.paidAt) year')
            ->where('i.paidAt >= :from')
            ->setParameter('from', $range->getFrom())
            ->andWhere('i.paidAt <= :to')
            ->setParameter('to', $range->getTo())
            ->andWhere('i.paid = true')
            ->groupBy('year, month')
            ->orderBy('year, month')
        ;

        $data = array_map(function(){
            return array(
                'amount' => 0,
                'taxesAmount' => 0,
                'totalAmount' => 0,
            );
        },$range->getMonthsArray());

        $results = $qb->getQuery()->getResult();
        foreach($results as $result){
            $data[$result['year'].$result['month']] = array(
                'amount' => $result['amount'],
                'taxesAmount' => $result['taxesAmount'],
                'totalAmount' => $result['amount'] + $result['taxesAmount'],
            );
        }

        return $data;
    }
}
