<?php

/**
 * QuoteSubmitController.php - created 13 Nov 2016 08:56:35
 *
 * @copyright Copyright (c) Mathias Schilling <m@matchilling>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */
namespace Tronald\App\Web\Controller;

use Silex;
use Symfony\Component\Form;
use Symfony\Component\HttpFoundation;
use Symfony\Component\Routing;
use Tronald\Lib\Broker;
use Tronald\Lib\Form\Type as FormType;
use Tronald\Lib\Entity;
use Tronald\Lib\Exception;

/**
 *
 * QuoteSubmitController
 *
 * @package Tronald\App\Web
 */
class QuoteSubmitController
{
    /**
     *
     * @param  Silex\Application $app
     * @param  HttpFoundation\Request $request
     * @throws Exception\NotFoundException
     * @return string
     */
    public function getAction(Silex\Application $app, HttpFoundation\Request $request)
    {
        /* @var Broker\AuthorBroker $authorBroker */
        $authorBroker = $app['broker']['author'];
        $author       = $authorBroker->findBySlug(
            $slug = 'donald-trump'
        );

        if (! $author instanceof Entity\Author) {
            throw new Exception\NotFoundException(
                sprintf('Could not find author with slug "%s".', $slug)
            );
        }

        /* @var Entity\Quote $quote */
        $quote = Entity\Factory::fromArray(Entity\Quote::class, [
            'author'      => Entity\Factory::toArray($author),
            'value'       => 'Hypocrite! Hillary Clinton claims she needs a "public and a private stance" in discussions with Wall Street banks.',
            'tags'        => [
                'Hillary Clinton'
            ],
            'source'      => [
                'url' => 'https://twitter.com/realDonaldTrump/status/785296480672382979'
            ]
        ]);

        return $app['twig']->render('quote/submit.twig.html', [
            'form' => $this->getForm($app['form.factory'], $quote)->createView()
        ]);
    }

    /**
     *
     * @param Form\FormFactory $formFactory
     * @param Entity\Quote $quote
     * return Form\FormInterface
     */
    private function getForm(Form\FormFactory $formFactory, Entity\Quote $quote)
    {
        /** @var Form\FormInterface $form */
        $form = $formFactory->createBuilder(
            FormType\QuoteType::class,
            $quote
        )->getForm();

        $form->get('author')->remove('bio');
        $form->get('source')->remove('filename');
        $form->add('submit', Form\Extension\Core\Type\SubmitType::class);

        return $form;
    }

    /**
     *
     * @param  Silex\Application $app
     * @param  HttpFoundation\Request $request
     * @return string
     */
    public function postAction(Silex\Application $app, HttpFoundation\Request $request)
    {
        $form = $this->getForm($app['form.factory'], new Entity\Quote());
        $form->handleRequest($request);

        if (! ($form->isSubmitted() && $form->isValid())) {
            return $app['twig']->render('quote/submit.twig.html', [
                'form' => $form->createView()
            ]);
        }

        /* @var Entity\Quote $quote */
        $quote = $form->getData();

        /* @var Broker\QuoteSourceBroker $quoteSourceBroker */
        $quoteSourceBroker = $app['broker']['quote_source'];
        $quoteSource = $quoteSourceBroker->create($quote->getSource());
        $quote->setSource($quoteSource);

        /* @var Broker\QuoteBroker $quoteBroker */
        $quoteBroker = $app['broker']['quote'];
        $quote = $quoteBroker->create($quote);

        /** @var Routing\Generator\UrlGenerator $urlGenerator */
        $urlGenerator = $app['url_generator'];

        return $app->redirect(
            $urlGenerator->generate('web.get_quote', ['id' => $quote->getId()])
        );
    }
}