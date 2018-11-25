<?php
/**
 * This file is part of the ezplatform-graphql-page package.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace spec\BD\EzPlatformGraphQLPage\GraphQL\Schema\Worker;

use BD\EzPlatformGraphQLBundle\Schema\Builder\SchemaBuilder;
use BD\EzPlatformGraphQLBundle\Schema\Worker;
use BD\EzPlatformGraphQLBundle\spec\Tools\EnumValueArgument;
use BD\EzPlatformGraphQLPage\GraphQL\Schema\Worker\BlockViewsValue;
use BD\EzPlatformGraphQLPage\GraphQL\Schema\Worker\NameHelper;
use Prophecy\Argument;

class BlockViewsValueSpec extends PageWorkerBehavior
{
    function let(NameHelper $nameHelper)
    {
        $this->configureNameHelper($nameHelper);
        $this->setNameHelper($nameHelper);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(BlockViewsValue::class);
        $this->shouldHaveType(Worker::class);
    }

    function it_can_not_work_if_args_do_not_have_a_BlockDefinition(SchemaBuilder $schema)
    {
        $this->canWork($schema, [])->shouldBe(false);
    }

    function it_can_not_work_if_args_do_not_have_a_BlockView(SchemaBuilder $schema)
    {
        $this->canWork($schema, $this->blockDefinitionArg())->shouldBe(false);
    }

    function it_adds_the_BlockView_to_the_BlockViews_enum(SchemaBuilder $schema)
    {
        $schema
            ->addValueToEnum(
                self::BLOCK_VIEWS_TYPE,
                Argument::allOf(
                    EnumValueArgument::withName(self::VIEW_NAME),
                    EnumValueArgument::withValue(self::VIEW_TEMPLATE)
                )
            )
            ->shouldBeCalled();
        $this->work($schema, $this->args());
    }

    private function args()
    {
        return $this->blockDefinitionArg() + $this->blockViewArg();
    }
}
