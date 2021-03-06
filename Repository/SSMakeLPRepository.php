<?php
/*
* This file is part of EC-CUBE
*
* Copyright(c) 2000-2015 LOCKON CO.,LTD. All Rights Reserved.
* http://www.lockon.co.jp/
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Plugin\SSMakeLP\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;

/**
 * SSMakeLP
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class SSMakeLPRepository extends EntityRepository
{
    /**
     * find all
     *
     * @return type
     */
    public function findAll()
    {

        $query = $this
            ->getEntityManager()
            ->createQuery('SELECT m FROM Plugin\SSMakeLP\Entity\SSMakeLP m ORDER BY m.rank DESC');
        $result = $query
            ->getResult(Query::HYDRATE_ARRAY);

        return $result;
    }

    /**
     * @param  \Plugin\SSMakeLP\Entity\SSMakeLP $SSMakeLP
     * @return bool
     */
    public function save(\Plugin\SSMakeLP\Entity\SSMakeLP $SSMakeLP)
    {
        $em = $this->getEntityManager();
        $em->getConnection()->beginTransaction();
        try {
            if (!$SSMakeLP->getId()) {
                $SSMakeLP->setDelFlg(0);

                $em->createQueryBuilder()
                    ->update('Plugin\SSMakeLP\Entity\SSMakeLP', 'm')
                    ->set('m.rank', 'm.rank + 1')
                    ->where('m.rank > :rank')
                    ->setParameter('rank', $rank)
                    ->getQuery()
                    ->execute();
            }

            $em->persist($SSMakeLP);
            $em->flush();

            $em->getConnection()->commit();
        } catch (\Exception $e) {
            $em->getConnection()->rollback();

            return false;
        }
        return true;
    }

    /**
     * @param  \Plugin\SSMakeLP\Entity\SSMakeLP $SSMakeLP
     * @return bool
     */
    public function delete(\Plugin\SSMakeLP\Entity\SSMakeLP $SSMakeLP)
    {
        $em = $this->getEntityManager();
        $em->getConnection()->beginTransaction();
        try {
            $SSMakeLP->setDelFlg(1);
            $em->persist($SSMakeLP);
            $em->flush();

            $em->getConnection()->commit();
        } catch (\Exception $e) {
            $em->getConnection()->rollback();

            return false;
        }

        return true;
    }

}
