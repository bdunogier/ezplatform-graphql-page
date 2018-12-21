<?php
/**
 * This file is part of the ezplatform-graphql-page package.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace spec\BD\EzPlatformGraphQLPage\GraphQL\Schema\Worker;

use EzSystems\EzPlatformGraphQL\Schema\Builder\Input;
use EzSystems\EzPlatformGraphQL\Schema\Builder\SchemaBuilder;
use EzSystems\EzPlatformGraphQL\Schema\Worker;
use BD\EzPlatformGraphQLPage\GraphQL\Schema\BlockAttributeBuilder;
use BD\EzPlatformGraphQLPage\GraphQL\Schema\Worker\BlockAttributeField;
use BD\EzPlatformGraphQLPage\GraphQL\Schema\Worker\NameHelper;
use EzSystems\EzPlatformPageFieldType\FieldType\Page\Block\Definition\BlockAttributeDefinition;
use Prophecy\Argument;

class BlockAttributeFieldSpec extends PageWorkerBehavior
{
    function let(NameHelper $nameHelper, BlockAttributeBuilder $blockAttributeBuilder)
    {
        $this->beConstructedWith([self::ATTRIBUTE_TYPE => $blockAttributeBuilder]);
        $this->configureNameHelper($nameHelper);
        $this->setNameHelper($nameHelper);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(BlockAttributeField::class);
        $this->shouldHaveType(Worker::class);
    }

    function it_can_not_work_if_args_do_not_have_a_BlockDefinition(SchemaBuilder $schema)
    {
        $this->canWork($schema, [])->shouldBe(false);
    }

    function it_can_not_work_if_args_do_not_have_a_BlockAttributeDefinition(SchemaBuilder $schema)
    {
        $args = $this->args();
        unset($args['BlockAttributeDefinition']);
        $this->canWork($schema, $args)->shouldBe(false);
    }

    function it_can_not_work_if_the_BlockType_is_not_defined(SchemaBuilder $schema)
    {
        $schema->hasType(self::BLOCK_TYPE)->willReturn(false);
        $this->canWork($schema, $this->args())->shouldBe(false);
    }

    function it_can_not_work_if_the_BlockAttribute_field_is_already_defined(SchemaBuilder $schema)
    {
        $schema->hasType(self::BLOCK_TYPE)->willReturn(true);
        $schema->hasTypeWithField(self::BLOCK_TYPE, self::ATTRIBUTE_FIELD)->willReturn(true);
        $this->canWork($schema, $this->args())->shouldBe(false);
    }

    function it_can_not_work_if_there_is_no_builder_for_the_BlockAttributeDefinition_type(
        SchemaBuilder $schema,
        BlockAttributeBuilder $unusedBuilder
    )
    {
        $schema->hasType(self::BLOCK_TYPE)->willReturn(false);
        $schema->hasTypeWithField(self::BLOCK_TYPE, self::ATTRIBUTE_FIELD)->willReturn(false);

        $this->canWork($schema, $this->args('type_without_builder'))->shouldBe(false);
    }

    function it_adds_a_field_to_the_BlockType_for_the_given_BlockAttributeDefinition(
        SchemaBuilder $schema,
        BlockAttributeBuilder $blockAttributeBuilder
    )
    {
        $fieldInput = new Input\Field(self::ATTRIBUTE_FIELD, 'String');
        $blockAttributeBuilder
            ->buildInput(Argument::type(BlockAttributeDefinition::class))
            ->willReturn($fieldInput);
        $schema
            ->addFieldToType(self::BLOCK_TYPE, $fieldInput)
            ->shouldBeCalled();
        $this->work($schema, $this->args());
    }

    private function args($attributeType = null)
    {
        return $this->blockDefinitionArg() + $this->blockAttributeDefinitionArg($attributeType);
    }
}
