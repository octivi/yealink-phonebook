<?php
/*
 * Copyright 2014 IMAGIN Sp. z o.o. - imagin.pl
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Octivi\Form;

 use Symfony\Component\Validator\Constraints as Assert;

 class NewFileForm
 {
     private $form_factory;

     /**
      * @param $form_factory
      */
     public function __construct($form_factory)
     {
         $this->form_factory = $form_factory;
     }

     /**
      * @return mixed
      */
     public function createForm() {
         $builder = $this->form_factory;
         $builder = $builder->createBuilder('form')
             ->add('fileName', 'text' , array(
                    'required' => true,
                     'constraints' => new Assert\Regex(array('pattern' => '/[A-Za-z.0-9-_]+/')),
                 ))
             ->getForm();
         return $builder;
     }
 }