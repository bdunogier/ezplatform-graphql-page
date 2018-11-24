<?php
/**
 * Created by PhpStorm.
 * User: bdunogier
 * Date: 24/11/2018
 * Time: 12:10
 */

namespace BD\EzPlatformGraphQLPage\GraphQL\Schema\Worker;


use EzSystems\EzPlatformPageFieldType\FieldType\Page\Block\Definition\BlockAttributeDefinition;
use EzSystems\EzPlatformPageFieldType\FieldType\Page\Block\Definition\BlockDefinition;
use PhpSpec\ObjectBehavior;
use PhpSpec\Wrapper\Collaborator;
use spec\BD\EzPlatformGraphQLPage\Tools\Arg;

abstract class BlockWorkerBehavior extends ObjectBehavior
{
    const BLOCK_IDENTIFIER = 'test';
    const BLOCK_NAME = 'Test block';
    const BLOCK_TYPE = 'TestPageBlock';

    const ATTRIBUTE_IDENTIFIER = 'test_attribute';
    const ATTRIBUTE_FIELD = 'testAttribute';

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

        return ['BlockDefinitionAttribute' => $attribute];
    }

    /**
     * @param Collaborator|NameHelper $nameHelper
     */
    protected function configureNameHelper(Collaborator $nameHelper)
    {
        $nameHelper
            ->blockType(Arg\Block::withIdentifier(self::BLOCK_IDENTIFIER))
            ->willReturn(self::BLOCK_TYPE);
    }
}