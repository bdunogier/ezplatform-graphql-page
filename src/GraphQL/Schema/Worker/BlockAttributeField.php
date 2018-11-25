<?php
/**
 * This file is part of the ezplatform-graphql-page package.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */

namespace BD\EzPlatformGraphQLPage\GraphQL\Schema\Worker;

use BD\EzPlatformGraphQLBundle\Schema\Builder;
use BD\EzPlatformGraphQLBundle\Schema\Worker;
use BD\EzPlatformGraphQLPage\GraphQL\Schema\BlockAttributeBuilder;
use EzSystems\EzPlatformPageFieldType\FieldType\Page\Block\Definition\BlockAttributeDefinition;
use EzSystems\EzPlatformPageFieldType\FieldType\Page\Block\Definition\BlockDefinition;
use Generator;

class BlockAttributeField extends BaseWorker implements Worker
{
    /**
     * @var BlockAttributeBuilder[]
     */
    private $attributesBuilders;

    /**
     * BlockAttributeField constructor.
     * @param BlockAttributeBuilder[] $attributesBuilders
     */
    public function __construct(array $attributesBuilders)
    {
        $this->attributesBuilders = $attributesBuilders;
    }

    public function work(Builder $schema, array $args)
    {
        $attributeBuilder = $this->attributesBuilders[$args['BlockAttributeDefinition']->getType()];
        $fieldInput = $attributeBuilder->buildInput($args['BlockAttributeDefinition']);

        $schema->addFieldToType($this->blockName($args), $fieldInput);
    }

    public function canWork(Builder $schema, array $args)
    {
        return isset($args['BlockDefinition'])
            && $args['BlockDefinition'] instanceof BlockDefinition
            && isset($args['BlockAttributeDefinition'])
            && $args['BlockAttributeDefinition'] instanceof BlockAttributeDefinition
            && $schema->hasType($this->blockName($args))
            && !$schema->hasTypeWithField($this->blockName($args), $this->attributeField($args))
            && isset($this->attributesBuilders[$args['BlockAttributeDefinition']->getType()]);

    }

    private function blockName($args)
    {
        return $this->getNameHelper()->blockType($args['BlockDefinition']);
    }

    private function attributeField($args)
    {
        return $this->getNameHelper()->attributeField($args['BlockAttributeDefinition']);
    }
}
