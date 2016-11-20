<?php

/**
 * ImportQuoteCommand.php - created 19 Nov 2016 22:39:05
 *
 * @copyright Copyright (c) Mathias Schilling <m@matchilling>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */
namespace Tronald\App\Cli\Command;

use \DirectoryIterator;
use Psr\Log;
use Symfony\Component\Console;
use Tronald\Lib;
use Symfony\Component\Console\Exception;

/**
 *
 * ImportQuoteCommand.php
 *
 * @package package
 *
 */
class ImportQuoteCommand extends Console\Command\Command
{
    use Log\LoggerAwareTrait;

    /**
     *
     * @var Lib\Entity\Factory
     */
    private $entityFactory;

    /**
     *
     * @var Lib\Database
     */
    private $database;

    /**
     *
     * @var Lib\Broker\QuoteBroker
     */
    private $quoteBroker;

    /**
     *
     * @var Lib\Broker\QuoteSourceBroker
     */
    private $quoteSourceBroker;

    /**
     *
     * @param Lib\Entity\Factory           $entityFactory
     * @param Lib\Broker\QuoteBroker       $quoteBroker
     * @param Lib\Broker\QuoteSourceBroker $quoteSourceBroker
     */
    public function __construct(
        Lib\Entity\Factory           $entityFactory,
        Lib\Broker\QuoteBroker       $quoteBroker,
        Lib\Broker\QuoteSourceBroker $quoteSourceBroker
    ) {
        $this->entityFactory     = $entityFactory;
        $this->quoteBroker       = $quoteBroker;
        $this->quoteSourceBroker = $quoteSourceBroker;

        parent::__construct();
    }

    /**
     *
     * {@inheritDoc}
     * @see \Symfony\Component\Console\Command\Command::configure()
     */
    protected function configure()
    {
        $this->setName('quote:import');
        $this->setDescription('Import quotes from a directory containing serialized json quote files.');

        $this->addArgument(
            'path',
            Console\Input\InputArgument::REQUIRED,
            'Absolute path to the directory which contains the json importables.'
        );
    }

    /**
     *
     * {@inheritDoc}
     * @see \Symfony\Component\Console\Command\Command::execute()
     */
    protected function execute(Console\Input\InputInterface $in, Console\Output\OutputInterface $out)
    {
        /** @var Log\LoggerInterface $logger */
        $logger = $this->logger;

        if (! $path = $in->getArgument('path')) {
            throw new Exception\RuntimeException('Argument "path" must be a non-empty value.');
        }

        $counter = 0;
        foreach (new DirectoryIterator($path) as $fileInfo) {
            if ($fileInfo->isDot() || $fileInfo->isDir() || $fileInfo->isLink()) {
                continue;
            }

            $filename = $fileInfo->getRealPath();
            $contents = file_get_contents($filename);

            if (false === $contents) {
                throw new Exception\RuntimeException(
                    sprintf('Invalid content in file "%s".', $filename)
                );
            }

            /** @var Lib\Entity\Quote $quote */
            $quote = $this->entityFactory->fromJson(Lib\Entity\Quote::class, $contents);

            $result = $this->quoteBroker->search($quote->getValue());
            if (0 < $result['total']) {
                $logger->info(
                    sprintf('Found duplicated value for "%s".', $quote->getValue())
                );
                continue;
            }

            $quoteSource = $this->quoteSourceBroker->create($quote->getSource());
            $quote->setSource($quoteSource);

            $quote = $this->quoteBroker->create($quote);

            $counter++;
        }

        $logger->info(
            sprintf('Job finished. %d files processed.', $counter)
        );
    }
}
