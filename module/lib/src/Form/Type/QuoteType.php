<?php

/**
 * QuoteType.php - created 13 Nov 2016 10:50:05
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
 * QuoteType
 *
 * @package Tronald\Lib
 */
class QuoteType extends Form\AbstractType
{

    /**
     *
     * {@inheritDoc}
     * @see \Symfony\Component\Form\AbstractType::configureOptions()
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Entity\Quote::class
        ]);
    }

    /**
     *
     * {@inheritDoc}
     * @see \Symfony\Component\Form\AbstractType::buildForm()
     */
    public function buildForm(Form\FormBuilderInterface $builder, array $options)
    {
        $quote  = $options['data'] instanceof Entity\Quote ? $options['data'] : new Entity\Quote();
        $source = $quote->getSource() instanceof Entity\QuoteSource ? $quote->getSource() : new Entity\QuoteSource();
        $author = $quote->getAuthor() instanceof Entity\Author ? $quote->getAuthor() : new Entity\Author();

        $builder->add('author', AuthorType::class, [
            'data' => $author
        ]);

        $builder
            ->add('value', FormType\TextareaType::class, [
                'attr'        => [
                    'rows'    => 10
                ],
                'constraints' => [
                    new Assert\Length(['min' => 10])
                ],
                'data'        => $quote->getValue(),
                'label'       => 'Quote',
                'required'    => true
            ])
            ->add('tags', FormType\TextType::class, [
                'data'     => $quote->getTags(),
                'label'    => 'Tags',
                'required' => false
            ])
            ->add('appearedAt', FormType\DateType::class, [
                'constraints' => [
                    new Assert\DateTime()
                ],
                'data'     => $quote->getAppearedAt(),
                'label'    => 'When first heard',
                'widget'   => 'single_text',
            ]);

        $builder->add('source', SourceType::class, [
            'data' => $source
        ]);

        $builder->get('tags')
            ->addModelTransformer(new Form\CallbackTransformer(
                function ($array) {
                    return is_array($array) ? implode(', ', $array) : null;
                },
                function ($string) {
                    return is_string($string) ? explode(',', $string) : null;
                }
            ))
        ;
    }
}
