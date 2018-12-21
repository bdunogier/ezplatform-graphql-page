<?php
/**
 * This file is part of the ezplatform-graphql-page package.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */

namespace BD\EzPlatformGraphQLPage\GraphQL\Schema\Worker;

use EzSystems\EzPlatformGraphQL\Schema\Builder;
use EzSystems\EzPlatformGraphQL\Schema\Worker;
use EzSystems\EzPlatformPageFieldType\FieldType\Page\Block\Definition\BlockDefinition;

class BlockViewsValue extends BaseWorker implements Worker
{
    public function work(Builder $schema, array $args)
    {
        $schema->addValueToEnum(
            $this->viewsType($args),
            new Builder\Input\EnumValue(
                $args['BlockView']['identifier'],
                [
                    'value' => $args['BlockView']['template'],
                    'description' => $args['BlockView']['name'],
                ]
            )
        );
    }

    public function canWork(Builder $schema, array $args)
    {
        return isset($args['BlockDefinition'])
            && $args['BlockDefinition'] instanceof BlockDefinition
            && isset($args['BlockView']);
    }

    private function viewsType($args)
    {
        return $this->getNameHelper()->viewsType($args['BlockDefinition']);
    }
}
