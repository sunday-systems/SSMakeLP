<?php

/*
 * This file is part of the SSMakeLP
 *
 * Copyright (C) 2016 yuh
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plugin\SSMakeLP\Controller;

use Eccube\Application;
use Symfony\Component\HttpFoundation\Request;
use Eccube\Controller\AbstractController;
use Eccube\Entity\Master\DeviceType;

use Doctrine\ORM\Query\ResultSetMapping;
class SSMakeLPController
{

    public function index(Application $app, Request $request, $id)
    {


        $repos = $app['eccube.plugin.makelp.repository.makelp'];

        $TargetMakeLP = new \Plugin\SSMakeLP\Entity\SSMakeLP();

        if ($id) {
            $TargetMakeLP = $repos->find($id);
            if (!$TargetMakeLP) {
                throw new NotFoundHttpException();
            }
        }
        $form = $app['form.factory']
            ->createBuilder('admin_makelp', $TargetMakeLP)
            ->getForm();

        if ('POST' === $request->getMethod()) {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $status = $repos->save($TargetMakeLP);

                if ($status) {
                    $app->addSuccess('admin.makelp.save.complete', 'admin');
                    //------------------------------------------------------
                    if (!$id) {
                        $DeviceType = $app['eccube.repository.master.device_type']->find(DeviceType::DEVICE_TYPE_PC);
                        $PageLayout = $app['eccube.repository.page_layout']->findOrCreate(NULL, $DeviceType);
                        $rsm = new ResultSetMapping();
                        $rsm->addScalarResult('lp_id', 'lp_id');
                        $sql = '
                        SELECT
                            lp_id
                        FROM
                            plg_ssmakelp
                        ORDER BY
                            lp_id DESC
                        LIMIT 1
                        ';
                        $query = $app['orm.em']->createNativeQuery($sql, $rsm);
                        $result = $query->getArrayResult();
                        
                        $id = $result[0]['lp_id'];
                        //----------------------------------------
                        //pagelayoutの中にデータが存在するかチェック
                        $rsm = new ResultSetMapping();
                        $rsm->addScalarResult('page_id', 'page_id');
                        $sql = '
                        SELECT
                            page_id
                        FROM
                            dtb_page_layout
                        WHERE
                            url = ?
                        ';
                        $query = $app['orm.em']->createNativeQuery($sql, $rsm);
                        $query->setParameter(1, 'makelp'.$id);
                        $result = $query->getArrayResult();
                        $page_id = $result[0]['page_id'];
                        
                        if(empty($page_id)){
                            
                           $PageLayout
                                ->setUrl('makelp'.$id)
                                ->setEditFlg(2)
                                ->setFileName('makelp'.$id)
                                ->setName('makelp'.$id);
                            // DB登録
                            $app['orm.em']->persist($PageLayout);
                            $app['orm.em']->flush();
                        }
                    }
                    //------------------------------------------------------
                    return $app->redirect($app->url('admin_makelp'));
                } else {
                    $app->addError('admin.makelp.save.error', 'admin');
                }
            }
        }
        
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id', 'id');
        $rsm->addScalarResult('product_id', 'product_id');
        $rsm->addScalarResult('path', 'path');
        
        
        $sql = '
        SELECT
            lp_id as id,
            product_id,
            path
        FROM
            plg_ssmakelp
        WHERE
            del_flg = 0
        ORDER BY
            lp_id ASC
        ';
        $query = $app['orm.em']->createNativeQuery($sql, $rsm);
        $MakeLPs = $query->getArrayResult();
        if(count($MakeLPs) > 0){
            foreach($MakeLPs as $key => $value){
                if($value["product_id"] AND preg_match('/^[0-9]+$/',$value["product_id"])){
                    $MakeLPs[$key]['product_name'] = $this->getProduct_name($app,$value["product_id"]);
                }
            }
        }
        return $app->render('SSMakeLP/View/admin/makelp.twig', array(
            'form'           => $form->createView(),
            'MakeLPs'         => $MakeLPs,
            'TargetMakeLP'     => $TargetMakeLP,
        ));
    }
    function getProduct_name($app,$product_id = NULL){
        if(!is_null($product_id)){
            $Product = $app['eccube.repository.product']->get($product_id);
            return $Product->getName();
        }
        return "";
    }
    
    
    
    public function delete(Application $app, Request $request, $id)
    {
        $repos = $app['eccube.plugin.makelp.repository.makelp'];

        $TargetMakeLP = $repos->find($id);
        
        if (!$TargetMakeLP) {
            throw new NotFoundHttpException();
        }

        $form = $app['form.factory']
            ->createNamedBuilder('admin_makelp', 'form', null, array(
                'allow_extra_fields' => true,
            ))
            ->getForm();

        $status = false;
        if ('POST' === $request->getMethod()) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $status = $repos->delete($TargetMakeLP);
            }
        }

        if ($status === true) {
            $app->addSuccess('admin.makelp.delete.complete', 'admin');
        } else {
            $app->addError('admin.makelp.delete.error', 'admin');
        }

        return $app->redirect($app->url('admin_makelp'));
    }
}
