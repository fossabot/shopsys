<?php

declare(strict_types=1);

namespace Shopsys\FrameworkBundle\Component\Elasticsearch;

class ElasticsearchDefinitionFinder
{
    /**
     * @var string
     */
    protected $definitionDirectory;

    /**
     * @param string $definitionDirectory
     */
    public function __construct(string $definitionDirectory)
    {
        $this->definitionDirectory = $definitionDirectory;
    }

    public function findAllDefinedIndexes(): array
    {

    }
}
