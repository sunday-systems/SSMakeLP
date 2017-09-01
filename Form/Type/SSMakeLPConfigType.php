<?php

/*
 * This file is part of the SSMakeLP
 *
 * Copyright (C) 2016 yuh
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plugin\SSMakeLP\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class SSMakeLPConfigType extends AbstractType
{
    protected $app;

    public function __construct($app)
    {
        $this->app = $app;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('path', 'text', array(
                'label' => 'パス',
                'required' => true,
                'constraints' => array(
                    new Assert\NotBlank(array('message' => '※ パスが入力されていません。')),
                ),
            ))
            ->add('lp_id', 'hidden', array()
            )
            ->add('product_id', 'integer', array(
                'label' => 'prodcut_id',
                'required' => true,
                'constraints' => array(
                    new Assert\NotBlank(array('message' => '※　product_idが入力されていません。')),
                ),
            ))
            ->addEventSubscriber(new \Eccube\Event\FormEventSubscriber());
    }

    public function getName()
    {
        return 'admin_makelp';
    }
}
