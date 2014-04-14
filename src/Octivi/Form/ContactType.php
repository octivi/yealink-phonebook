<?php
/*
 * Copyright 2014 IMAGIN Sp. z o.o. - imagin.pl
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Octivi\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ContactType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('recordName', 'text')
                ->add('phoneNumber', 'collection', array(
                'type' => 'text')
            );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'contactRecord';
    }
}