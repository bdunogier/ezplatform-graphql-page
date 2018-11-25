<?php
/**
 * This file is part of the ezplatform-graphql-page package.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace spec\BD\EzPlatformGraphQLPage\GraphQL\Schema\Worker;

use BD\EzPlatformGraphQLPage\GraphQL\Schema\Worker\NameHelper;
use EzSystems\EzPlatformPageFieldType\FieldType\Page\Block\Definition\BlockAttributeDefinition;
use EzSystems\EzPlatformPageFieldType\FieldType\Page\Block\Definition\BlockDefinition;
use PhpSpec\ObjectBehavior;
use PhpSpec\Wrapper\Collaborator;
use spec\BD\EzPlatformGraphQLPage\Tools\Arg;

abstract class PageWorkerBehavior extends ObjectBehavior
{
    const BLOCK_IDENTIFIER = 'test_block';
    const BLOCK_NAME = 'Test block';
    const BLOCK_TYPE = 'TestBlockPageBlock';
    const BLOCK_VIEWS_TYPE = 'TestBlockPageBlockViews';

    const ATTRIBUTE_IDENTIFIER = 'test_attribute';
    const ATTRIBUTE_FIELD = 'testAttribute';
    const ATTRIBUTE_TYPE = 'test';

    const VIEW_NAME = 'default';
    const VIEW_TEMPLATE = 'path/to/block_view/default.html.twig';

    protected function blockDefinitionArg()
    {
        $blockDefinition = new BlockDefinition();
        $blockDefinition->setIdentifier(self::BLOCK_IDENTIFIER);
        $blockDefinition->setName(self::BLOCK_NAME);

        return ['BlockDefinition' => $blockDefinition];
    }

    protected function blockAttributeDefinitionArg($attributType = null)
    {
        $attribute = new BlockAttributeDefinition();
        $attribute->setIdentifier(self::ATTRIBUTE_IDENTIFIER);
        $attribute->setType($attributType ?? self::ATTRIBUTE_TYPE);

        return ['BlockAttributeDefinition' => $attribute];
    }

    protected function blockViewArg()
    {
        return [
            'BlockView' => [
                'name' => self::VIEW_NAME,
                'template' => self::VIEW_TEMPLATE
            ]
        ];
    }

    /**
     * @param Collaborator|NameHelper $nameHelper
     */
    protected function configureNameHelper(Collaborator $nameHelper)
    {
        $nameHelper
            ->blockType(Arg\Block::withIdentifier(self::BLOCK_IDENTIFIER))
            ->willReturn(self::BLOCK_TYPE);

        $nameHelper
            ->attributeField(Arg\Attribute::withIdentifier(self::ATTRIBUTE_IDENTIFIER))
            ->willReturn(self::ATTRIBUTE_FIELD);

        $nameHelper
            ->viewsType(Arg\Block::withIdentifier(self::BLOCK_IDENTIFIER))
            ->willReturn(self::BLOCK_VIEWS_TYPE);
    }
}