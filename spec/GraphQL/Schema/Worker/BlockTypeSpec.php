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
use BD\EzPlatformGraphQLPage\GraphQL\Schema\Worker\BlockType;
use BD\EzPlatformGraphQLPage\GraphQL\Schema\Worker\NameHelper;
use Prophecy\Argument;

class BlockTypeSpec extends PageWorkerBehavior
{
    const BASE_BLOCK_TYPE = 'BasePageBlock';
    const BLOCK_INTERFACE = 'PageBlock';

    function let(NameHelper $nameHelper)
    {
        $this->configureNameHelper($nameHelper);
        $this->setNameHelper($nameHelper);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(BlockType::class);
        $this->shouldHaveType(Worker::class);
    }

    function it_can_not_work_if_args_do_not_have_a_BlockDefinition(SchemaBuilder $schema)
    {
        $this->canWork($schema, [])->shouldBe(false);
    }

    function it_can_not_work_if_the_BlockType_is_already_defined(SchemaBuilder $schema)
    {
        $schema->hasType(self::BLOCK_TYPE)->willReturn(true);
        $this->canWork($schema, $this->args())->shouldBe(false);
    }

    function it_adds_the_BlockType_to_the_schema(SchemaBuilder $schema)
    {
        $schema
            ->addType(Argument::allOf(
                TypeArgument::hasType('object'),
                TypeArgument::isNamed(self::BLOCK_TYPE),
                TypeArgument::inherits(self::BASE_BLOCK_TYPE),
                TypeArgument::implements(self::BLOCK_INTERFACE)
            ))
            ->shouldBeCalled();
        $this->work($schema, $this->args());
    }

    private function args()
    {
        return $this->blockDefinitionArg();
    }
}
