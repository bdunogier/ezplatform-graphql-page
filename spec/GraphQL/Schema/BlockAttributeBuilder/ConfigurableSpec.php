<?php
namespace spec\BD\EzPlatformGraphQLPage\GraphQL\Schema\BlockAttributeBuilder;

use EzSystems\EzPlatformGraphQL\Schema\Builder\Input;
use BD\EzPlatformGraphQLPage\GraphQL\Schema\BlockAttributeBuilder;
use BD\EzPlatformGraphQLPage\GraphQL\Schema\Worker\NameHelper;
use EzSystems\EzPlatformPageFieldType\FieldType\Page\Block\Definition\BlockAttributeDefinition;
use PhpSpec\ObjectBehavior;
use spec\BD\EzPlatformGraphQLPage\GraphQL\Schema\Worker\PageWorkerBehavior;

class ConfigurableSpec extends PageWorkerBehavior
{
    const TYPE = 'String';
    const RESOLVE = "@resolve('MyResolver')";

    function let(NameHelper $nameHelper)
    {
        $this->beConstructedWith($nameHelper, self::TYPE);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(BlockAttributeBuilder\Configurable::class);
        $this->shouldHaveType(BlockAttributeBuilder::class);
    }

    function it_builds_an_input_field_with_the_configured_type()
    {
        $attribute = $this->attribute();
        $this->buildInput($attribute)->shouldBeAnInstanceOf(Input\Field::class);
        $this->buildInput($attribute)->shouldHaveGraphQLType(self::TYPE);
    }

    function it_sets_the_input_field_resolve_if_configured(NameHelper $nameHelper)
    {
        $attribute = $this->attribute();
        $this->beConstructedWith($nameHelper, self::TYPE, self::RESOLVE);
        $this->buildInput($attribute)->shouldBeAnInstanceOf(Input\Field::class);
        $this->buildInput($attribute)->shouldHaveResolve(self::RESOLVE);
    }

    private function attribute()
    {
        $attribute = new BlockAttributeDefinition();
        $attribute->setIdentifier(self::ATTRIBUTE_IDENTIFIER);

        return $attribute;
    }

    public function getMatchers(): array
    {
        return [
            'haveGraphQLType' => function ($value, $graphQLType) {
                return $value instanceof Input\Field
                    && $value->type === $graphQLType;
            },
            'haveResolve' => function ($value, $resolve) {
                return $value instanceof Input\Field
                    && $value->resolve === $resolve;
            },
        ];
    }


}