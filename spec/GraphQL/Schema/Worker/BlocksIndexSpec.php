<?php
/**
 * This file is part of the ezplatform-graphql-page package.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace spec\BD\EzPlatformGraphQLPage\GraphQL\Schema\Worker;

use EzSystems\EzPlatformGraphQL\Schema\Builder\SchemaBuilder;
use EzSystems\EzPlatformGraphQL\Schema\Worker;
use EzSystems\EzPlatformGraphQL\Schema\Initializer;
use spec\EzSystems\EzPlatformGraphQL\Tools\EnumValueArgument;
use spec\EzSystems\EzPlatformGraphQL\Tools\TypeArgument;
use BD\EzPlatformGraphQLPage\GraphQL\Schema\Worker\BlocksIndex;
use BD\EzPlatformGraphQLPage\GraphQL\Schema\Worker\NameHelper;
use Prophecy\Argument;

class BlocksIndexSpec extends PageWorkerBehavior
{
    const ENUM_TYPE = 'PageBlocksList';

    function let(NameHelper $nameHelper)
    {
        $this->configureNameHelper($nameHelper);
        $this->setNameHelper($nameHelper);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(BlocksIndex::class);
        $this->shouldHaveType(Worker::class);
        $this->shouldHaveType(Initializer::class);
    }

    function it_initializes_the_BlocksIndex_enum(SchemaBuilder $schema)
    {
        $schema
            ->addType(
                Argument::allOf(
                    TypeArgument::hasType('enum'),
                    TypeArgument::isNamed(self::ENUM_TYPE)
                )
            )
            ->shouldBeCalled();

        $this->init($schema);
    }

    function it_can_not_work_if_there_is_no_BlockDefinition_argument(SchemaBuilder $schema)
    {
        $this->canWork($schema, [])->shouldBe(false);
    }

    function it_can_not_work_if_there_is_a_BlockAttributeDefinition_argument(SchemaBuilder $schema)
    {
        $args = $this->blockDefinitionArg() + $this->blockAttributeDefinitionArg();
        $this->canWork($schema, $args)->shouldBe(false);
    }

    function it_adds_the_BlockDefinition_to_the_PageBlocksList_enum(SchemaBuilder $schema)
    {
        $schema
            ->addValueToEnum(
                self::ENUM_TYPE,
                Argument::allOf(
                    EnumValueArgument::withName(self::BLOCK_IDENTIFIER),
                    EnumValueArgument::withValue(self::BLOCK_TYPE)
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
