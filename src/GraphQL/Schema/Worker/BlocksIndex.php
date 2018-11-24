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
use BD\EzPlatformGraphQLBundle\spec\Tools\Stubs\InitializableWorker;
use EzSystems\EzPlatformPageFieldType\FieldType\Page\Block\Definition\BlockDefinition;

class BlocksIndex extends BaseWorker implements Worker, InitializableWorker
{
    const ENUM_TYPE = 'PageBlocksList';

    public function work(Builder $schema, array $args)
    {
        $schema->addValueToEnum(
            self::ENUM_TYPE,
            new Builder\Input\EnumValue(
                $args['BlockDefinition']->getIdentifier(),
                ['value' => $this->blockType($args)]
            )
        );
    }

    public function canWork(Builder $schema, array $args)
    {
        return isset($args['BlockDefinition'])
            && $args['BlockDefinition'] instanceof BlockDefinition
            && !isset($args['BlockAttributeDefinition']);
    }

    public function init(Builder $schema)
    {
        $schema->addType(new Builder\Input\Type(self::ENUM_TYPE, 'enum'));
    }

    private function blockType($args)
    {
        return $this->getNameHelper()->blockType($args['BlockDefinition']);
    }
}
