<?php
/**
 * This file is part of the ezplatform-graphql-page package.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */

namespace BD\EzPlatformGraphQLPage\GraphQL\Schema\BlockAttributeBuilder;

use BD\EzPlatformGraphQLBundle\Schema;
use BD\EzPlatformGraphQLPage\GraphQL\Schema\BlockAttributeBuilder;
use BD\EzPlatformGraphQLPage\GraphQL\Schema\Worker\NameHelper;
use EzSystems\EzPlatformPageFieldType\FieldType\Page\Block\Definition\BlockAttributeDefinition;

class Embed implements BlockAttributeBuilder
{
    /**
     * @var NameHelper
     */
    private $nameHelper;

    public function __construct(NameHelper $nameHelper)
    {
        $this->nameHelper = $nameHelper;
    }

    public function buildInput(BlockAttributeDefinition $blockAttributeDefinition)
    {
        $resolve = sprintf(
            '@=resolver("ContentById", [value.getAttribute("%s").getValue()])',
            $blockAttributeDefinition->getIdentifier()
        );

        return new Schema\Builder\Input\Field(
            $this->nameHelper->attributeField($blockAttributeDefinition),
            'DomainContent',
            ['resolve' => $resolve]
        );
    }
}
