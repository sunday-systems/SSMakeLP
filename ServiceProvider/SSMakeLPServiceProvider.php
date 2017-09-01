<?php

/*
 * This file is part of the SSMakeLP
 *
 * Copyright (C) 2016 yuh
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plugin\SSMakeLP\ServiceProvider;

use Eccube\Application;
use Monolog\Handler\FingersCrossed\ErrorLevelActivationStrategy;
use Monolog\Handler\FingersCrossedHandler;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;
use Plugin\SSMakeLP\Form\Type\SSMakeLPConfigType;
use Silex\Application as BaseApplication;
use Silex\ServiceProviderInterface;
use Symfony\Component\Yaml\Yaml;

use Doctrine\ORM\Query\ResultSetMapping;

class SSMakeLPServiceProvider implements ServiceProviderInterface
{
    public function register(BaseApplication $app)
    {
        // プラグイン用設定画面
        $app->match('/' . $app['config']['admin_route'] . '/plugin/SSMakeLP/config', 'Plugin\SSMakeLP\Controller\ConfigController::index')->bind('plugin_SSMakeLP_config');
        // メーカーテーブル用リポジトリ
        $app['eccube.plugin.makelp.repository.makelp'] = $app->share(function () use ($app) {
            return $app['orm.em']->getRepository('Plugin\SSMakeLP\Entity\SSMakeLP');
        });

        // 独自コントローラ
        $app->match('/plugin/[code_name]/hello', 'Plugin\SSMakeLP\Controller\SSMakeLPController::index')->bind('plugin_SSMakeLP_hello');

        //LP読み出し、設定

        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('lp_id', 'lp_id');
        $rsm->addScalarResult('product_id', 'product_id');
        $rsm->addScalarResult('path', 'path');
        $sql = '
        SELECT
            lp_id,
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
        $result = $query->getArrayResult();
        
        foreach($result as $key => $value){
            if($value['lp_id'] AND preg_match('/^[0-9]+$/',$value['lp_id'])){
                $app->match($value['path'], '\Eccube\Controller\ProductController::detail')->bind('makelp'.$value['lp_id'])->value('id', $value['product_id'])->assert('id', '\d+');
            }
        }


        // 一覧・登録・修正
        $app->match('/' . $app["config"]["admin_route"] . '/contents/makelp/{id}', '\\Plugin\\SSMakeLP\\Controller\\SSMakeLPController::index')
            ->value('id', null)->assert('id', '\d+|')
            ->bind('admin_makelp');

        // 削除
        $app->match('/' . $app["config"]["admin_route"] . '/product/makelp/{id}/delete', '\\Plugin\\SSMakeLP\\Controller\\SSMakeLPController::delete')
            ->value('id', null)->assert('id', '\d+|')
            ->bind('admin_makelp_delete');
        // Form
        $app['form.types'] = $app->share($app->extend('form.types', function ($types) use ($app) {
            $types[] = new SSMakeLPConfigType($app);
            return $types;
        }));
     // メッセージ登録
        $app['translator'] = $app->share($app->extend('translator', function ($translator, \Silex\Application $app) {
            $translator->addLoader('yaml', new \Symfony\Component\Translation\Loader\YamlFileLoader());

            $file = __DIR__ . '/../Resource/locale/message.' . $app['locale'] . '.yml';
            if (file_exists($file)) {
                $translator->addResource('yaml', $file, $app['locale']);
            }

            return $translator;
        }));
        // メニュー登録
        $app['config'] = $app->share($app->extend('config', function ($config) {
            $addNavi['id'] = "makelp";
            $addNavi['name'] = "LP管理";
            $addNavi['url'] = "admin_makelp";

            $nav = $config['nav'];
            foreach ($nav as $key => $val) {
                if ("content" == $val["id"]) {
                    $nav[$key]['child'][] = $addNavi;
                }
            }

            $config['nav'] = $nav;
            return $config;
        }));
        // ログファイル設定
        $app['monolog.SSMakeLP'] = $app->share(function ($app) {

            $logger = new $app['monolog.logger.class']('plugin.SSMakeLP');

            $file = $app['config']['root_dir'] . '/app/log/SSMakeLP.log';
            $RotateHandler = new RotatingFileHandler($file, $app['config']['log']['max_files'], Logger::INFO);
            $RotateHandler->setFilenameFormat(
                'SSMakeLP_{date}',
                'Y-m-d'
            );

            $logger->pushHandler(
                new FingersCrossedHandler(
                    $RotateHandler,
                    new ErrorLevelActivationStrategy(Logger::INFO)
                )
            );

            return $logger;
        });

    }

    public function boot(BaseApplication $app)
    {
    }
}
