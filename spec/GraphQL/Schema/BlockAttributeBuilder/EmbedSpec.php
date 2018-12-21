<?php

namespace spec\BD\EzPlatformGraphQLPage\GraphQL\Schema\BlockAttributeBuilder;

use EzSystems\EzPlatformGraphQL\Schema\Builder\Input;
use BD\EzPlatformGraphQLPage\GraphQL\Schema\BlockAttributeBuilder;
use BD\EzPlatformGraphQLPage\GraphQL\Schema\BlockAttributeBuilder\Embed;
use BD\EzPlatformGraphQLPage\GraphQL\Schema\Worker\NameHelper;
use EzSystems\EzPlatformPageFieldType\FieldType\Page\Block\Definition\BlockAttributeDefinition;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class EmbedSpec extends ObjectBehavior
{
    const FIELD_IDENTIFIER = 'my_field';
    const FIELD_FIELD = 'myField';

    function let(NameHelper $nameHelper)
    {
        $nameHelper->attributeField(Argument::any())->willReturn(self::FIELD_FIELD);
        $this->beConstructedWith($nameHelper);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Embed::class);
        $this->shouldHaveType(BlockAttributeBuilder::class);
    }

    function it_returns_an_input_field()
    {
        $this->buildInput($this->attribute())->shouldBeAnInstanceOf(Input\Field::class);
    }

    function it_builds_with_the_DomainContent_type()
    {
        $this->buildInput($this->attribute())->shouldHaveGraphQLType('DomainContent');
    }

    function it_builds_with_the_ContentByIdResolver_with_the_attribute_value_as_the_id()
    {
        $this->buildInput($this->attribute())->shouldBeResolvedWith(
            'ContentById',
            'value.getAttribute("' . self::FIELD_IDENTIFIER . '").getValue()'
        );
    }

    private function attribute()
    {
        $attribute = new BlockAttributeDefinition();
        $attribute->setIdentifier(self::FIELD_IDENTIFIER);

        return $attribute;
    }

    public function getMatchers(): array
    {
        return [
            'haveGraphQLType' => function (Input\Field $field, $type) {
                return $field->type === $type;
            },
            'beResolvedWith' => function (Input\Field $field, $resolver, $argument) {
                return strpos($field->resolve, $resolver) !== false
                    && strpos($field->resolve, $argument) !== false;
            }
        ];
    }
}
