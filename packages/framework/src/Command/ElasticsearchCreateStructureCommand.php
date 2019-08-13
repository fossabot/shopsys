<?php

namespace Shopsys\FrameworkBundle\Command;

use Shopsys\FrameworkBundle\Component\Elasticsearch\ElasticsearchStructureFacade;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ElasticsearchCreateStructureCommand extends Command
{
    /**
     * @var string
     */
    protected static $defaultName = 'shopsys:elasticsearch:create-structure';

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
            ->setDescription('Creates structure in Elasticsearch for searching');
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $symfonyStyleIo = new SymfonyStyle($input, $output);
        $output->writeln('Creating structure');
        $this->elasticsearchStructureFacade->createIndexes($output);
        $symfonyStyleIo->success('Structure created successfully!');
    }
}
