<?php

/**
 * SourceType.php - created 13 Nov 2016 10:50:05
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
 * SourceType
 *
 * @package Tronald\Lib
 */
class SourceType extends Form\AbstractType
{

    /**
     *
     * {@inheritDoc}
     * @see \Symfony\Component\Form\AbstractType::configureOptions()
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Entity\QuoteSource::class
        ]);
    }

    /**
     *
     * @SerializeType("string") @SerializeName("url")
     * @var string
     */
    protected $url;
    /**
     *
     * {@inheritDoc}
     * @see \Symfony\Component\Form\AbstractType::buildForm()
     */
    public function buildForm(Form\FormBuilderInterface $builder, array $options)
    {
        $source = $options['data'] instanceof Entity\QuoteSource ? $options['data'] : new Entity\QuoteSource();

        $builder
            ->add('id', FormType\HiddenType::class, [
                'data'        => $source->getId()
            ])
            ->add('url', FormType\UrlType::class, [
                'attr'        => [
                    'data-help' => 'An url where the quote was spotted.'
                ],
                'constraints' => new Assert\Url(),
                'data'        => $source->getUrl(),
                'label'       => 'Url',
                'required'    => false
            ])
            ->add('remarks', FormType\TextareaType::class, [
                'data'        => $source->getRemarks(),
                'label'       => 'Remarks',
                'required'    => false
            ])
            ->add('filename', FormType\FileType::class, [
                'data'        => $source->getFilename(),
                'required'    => false
            ]);
    }
}
