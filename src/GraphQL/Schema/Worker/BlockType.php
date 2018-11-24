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
use EzSystems\EzPlatformPageFieldType\FieldType\Page\Block\Definition\BlockDefinition;

class BlockType extends BaseWorker implements Worker
{
    public function work(Builder $schema, array $args)
    {
        $schema->addType(
            new Builder\Input\Type($this->blockType($args), 'object',
                [
                'inherits' => 'BasePageBlock',
                'interfaces' => 'PageBlock',
                ]
            )
        );
    }

    public function canWork(Builder $schema, array $args)
    {
        return
            isset($args['BlockDefinition'])
            && $args['BlockDefinition'] instanceof BlockDefinition
            && !$schema->hasType($this->blockType($args));
    }

    private function blockType($args)
    {
        return $this->getNameHelper()->blockType($args['BlockDefinition']);
    }
}
