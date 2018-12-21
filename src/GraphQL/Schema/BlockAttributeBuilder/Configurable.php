<?php
namespace BD\EzPlatformGraphQLPage\GraphQL\Schema\BlockAttributeBuilder;

use EzSystems\EzPlatformGraphQL\Schema\Builder\Input;
use BD\EzPlatformGraphQLPage\GraphQL\Schema\BlockAttributeBuilder;
use BD\EzPlatformGraphQLPage\GraphQL\Schema\Worker\NameHelper;
use EzSystems\EzPlatformPageFieldType\FieldType\Page\Block\Definition\BlockAttributeDefinition;

/**
 * A Block Attribute Builder that is configured through its constructor.
 * It requires a GraphQL type, and accepts an optional resolve string.
 */
class Configurable implements BlockAttributeBuilder
{
    /** @var string */
    private $type;

    /** @var string|null */
    private $resolve;

    /** @var NameHelper */
    private $nameHelper;

    public function __construct(NameHelper $nameHelper, $type, $resolve = null)
    {
        $this->type = $type;
        $this->resolve = $resolve;
        $this->nameHelper = $nameHelper;
    }

    public function buildInput(BlockAttributeDefinition $blockAttributeDefinition)
    {
        $input = new Input\Field(
            $this->nameHelper->attributeField($blockAttributeDefinition),
            $this->type
        );

        if (isset($this->resolve)) {
            $input->resolve = $this->resolve;
        }

        return $input;
    }
}