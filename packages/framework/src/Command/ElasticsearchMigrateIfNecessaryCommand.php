<?php

declare(strict_types=1);

namespace Shopsys\FrameworkBundle\Command;

use Shopsys\FrameworkBundle\Component\Elasticsearch\ElasticsearchStructureFacade;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ElasticsearchMigrateIfNecessaryCommand extends Command
{
    /**
     * @var string
     */
    protected static $defaultName = 'shopsys:elasticsearch:migrate-if-necessary';

    /**
     * @var \Shopsys\FrameworkBundle\Component\Elasticsearch\ElasticsearchStructureFacade
     */
    protected $elasticsearchStructureFacade;

    /**
     * @param \Shopsys\FrameworkBundle\Component\Elasticsearch\ElasticsearchStructureFacade $elasticsearchStructureFacade
     */
    public function __construct(ElasticsearchStructureFacade $elasticsearchStructureFacade)
    {
        $this->elasticsearchStructureFacade = $elasticsearchStructureFacade;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Creates new structure, reindexes it from old one, deletes old structure and adds alias to new structure');
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $symfonyStyleIo = new SymfonyStyle($input, $output);
        $output->writeln('Migrating indexes');
        $this->elasticsearchStructureFacade->migrateIndexesIfNecessary($output);
        $symfonyStyleIo->success('Indexes migrated successfully!');
    }
}
