<?php
namespace BD\EzPlatformGraphQLPage\GraphQL\Schema;

use BD\EzPlatformGraphQLBundle\Schema\Builder;
use BD\EzPlatformGraphQLBundle\Schema\Domain\Iterator;
use EzSystems\EzPlatformPageFieldType\FieldType\Page\Block\Definition\BlockDefinitionFactory;
use Generator;

class DomainIterator implements Iterator
{
    /**
     * @var BlockDefinitionFactory
     */
    private $blockDefinitionFactory;

    public function __construct(BlockDefinitionFactory $blockDefinitionFactory)
    {
        $this->blockDefinitionFactory = $blockDefinitionFactory;
    }

    public function init(Builder $schema)
    {
    }

    public function iterate(): Generator
    {
        foreach ($this->blockDefinitionFactory->getBlockIdentifiers() as $blockIdentifier) {
            $blockDefinition = $this->blockDefinitionFactory->getBlockDefinition($blockIdentifier);
            $args = ['BlockDefinition' => $blockDefinition];
            yield $args;

            foreach ($blockDefinition->getAttributes() as $attributeDefinition) {
                yield $args + ['BlockAttributeDefinition' => $attributeDefinition];
            }

            foreach ($blockDefinition->getViews() as $viewIdentifier => $view) {
                yield $args + ['BlockView' => ['identifier' => $viewIdentifier] + $view];
            }
        }
    }
}