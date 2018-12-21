<?php
namespace BD\EzPlatformGraphQLPage\GraphQL\Schema;

use EzSystems\EzPlatformGraphQL\Schema;
use EzSystems\EzPlatformPageFieldType\FieldType\Page\Block\Definition\BlockAttributeDefinition;

interface BlockAttributeBuilder
{
    /**
     * @param BlockAttributeDefinition $blockAttributeDefinition
     * @return Schema\Builder\Input\Field
     */
    public function buildInput(BlockAttributeDefinition $blockAttributeDefinition);
}