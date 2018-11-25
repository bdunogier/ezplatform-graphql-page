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
use BD\EzPlatformGraphQLBundle\spec\Tools\TypeArgument;
use BD\EzPlatformGraphQLPage\GraphQL\Schema\Worker\BlockViewsType;
use BD\EzPlatformGraphQLPage\GraphQL\Schema\Worker\NameHelper;
use Prophecy\Argument;

class BlockViewsTypeSpec extends PageWorkerBehavior
{
    function let(NameHelper $nameHelper)
    {
        $this->configureNameHelper($nameHelper);
        $this->setNameHelper($nameHelper);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(BlockViewsType::class);
        $this->shouldHaveType(Worker::class);
    }

    function it_can_not_work_if_args_do_not_have_a_BlockDefinition(SchemaBuilder $schema)
    {
        $this->canWork($schema, [])->shouldBe(false);
    }

    function it_can_not_work_if_the_BlockViews_type_is_already_defined(SchemaBuilder $schema)
    {
        $schema->hasType(self::BLOCK_VIEWS_TYPE)->willReturn(true);
        $this->canWork($schema, $this->args())->shouldbe(false);
    }

    function it_defines_the_BlockViews_enum(SchemaBuilder $schema)
    {
        $schema
            ->addType(
                Argument::allOf(
                    TypeArgument::isNamed(self::BLOCK_VIEWS_TYPE),
                    TypeArgument::hasType('enum')
                )
            )
            ->shouldBeCalled();
        $this->work($schema, $this->args());
    }

    private function args()
    {
        return $this->blockDefinitionArg();
    }
}
