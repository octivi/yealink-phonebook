<?php
/*
 * Copyright 2014 IMAGIN Sp. z o.o. - imagin.pl
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Octivi\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PhoneBookType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'phoneRecords',
            'collection',
            array(
                'type' => new PhoneRecordType(),
                'allow_add' => true,
                'allow_delete' => true,
            )
        );

        $builder->add(
            'urlRecords',
            'collection',
            array(
                'type' => new UrlRecordType(),
                'allow_add' => true,
                'allow_delete' => true,
            )
        );
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Octivi\Entity\PhoneBook',
            )
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'phoneBook';
    }
}
