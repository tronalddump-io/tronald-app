<?php

/**
 * AuthorType.php - created 13 Nov 2016 10:50:05
 *
 * @copyright Copyright (c) Mathias Schilling <m@matchilling>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */
namespace Tronald\Lib\Form\Type;

use Symfony\Component\Form;
use Symfony\Component\Form\Extension\Core\Type as FormType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Tronald\Lib\Entity;

/**
 *
 * AuthorType
 *
 * @package Tronald\Lib
 */
class AuthorType extends Form\AbstractType
{

    /**
     *
     * {@inheritDoc}
     * @see \Symfony\Component\Form\AbstractType::configureOptions()
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Entity\Author::class
        ]);
    }

    /**
     *
     * {@inheritDoc}
     * @see \Symfony\Component\Form\AbstractType::buildForm()
     */
    public function buildForm(Form\FormBuilderInterface $builder, array $options)
    {
        $author = $options['data'] instanceof Entity\Author ? $options['data'] : new Entity\Author();

        $builder
            ->add('name', FormType\TextType::class, [
                'constraints' => [
                    new Assert\Length(['min' => 3, 'max' => 255])
                ],
                'data'        => $author->getName(),
                'required'    => true
            ])
            ->add('bio', FormType\TextareaType::class, [
                'data'        => $author->getBio(),
                'label'       => 'Biography',
                'required'    => false
            ])
            ->add('slug', FormType\HiddenType::class, [
                'data'        => $author->getSlug(),
            ])
            ->add('id', FormType\HiddenType::class, [
                'data'        => $author->getId()
            ]);
    }
}
