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

class ConfigController
{

    /**
     * SSMakeLP用設定画面
     *
     * @param Application $app
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Application $app, Request $request)
    {

        $form = $app['form.factory']->createBuilder('ssmakelp_config')->getForm();

        if ('POST' === $request->getMethod()) {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $data = $form->getData();

                // add code...
            }
        }

        return $app->render('SSMakeLP/Resource/template/admin/config.twig', array(
            'form' => $form->createView(),
        ));
    }

}
