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
    const BLOCK_IDENTIFIER = 'test';
    const BLOCK_NAME = 'Test block';
    const BLOCK_TYPE = 'TestPageBlock';
    const BLOCK_VIEWS_TYPE = 'TestPageBlockViews';

    const ATTRIBUTE_IDENTIFIER = 'test_attribute';
    const ATTRIBUTE_FIELD = 'testAttribute';

    const VIEW_IDENTIFIER = 'default';

    protected function blockDefinitionArg()
    {
        $blockDefinition = new BlockDefinition();
        $blockDefinition->setIdentifier(self::BLOCK_IDENTIFIER);
        $blockDefinition->setName(self::BLOCK_NAME);

        return ['BlockDefinition' => $blockDefinition];
    }

    protected function blockAttributeDefinitionArg()
    {
        $attribute = new BlockAttributeDefinition();

        return ['BlockAttributeDefinition' => $attribute];
    }

    protected function blockViewArg()
    {
        return ['BlockView' => self::VIEW_IDENTIFIER];
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