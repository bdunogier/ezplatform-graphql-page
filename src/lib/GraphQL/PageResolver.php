<?php
namespace BD\PlatformPageGraphQL\GraphQL;

use ArrayObject;
use EzSystems\EzPlatformPageFieldType\FieldType\LandingPage\Model\Attribute as PageAttribute;
use EzSystems\EzPlatformPageFieldType\FieldType\LandingPage\Model\BlockValue;
use EzSystems\EzPlatformPageFieldType\FieldType\Page\Block\Definition\BlockDefinitionFactory;
use Overblog\GraphQLBundle\Resolver\TypeResolver;

class PageResolver
{
    /**
     * @var TypeResolver
     */
    private $typeResolver;
    /**
     * @var BlockDefinitionFactory
     */
    private $blockDefinitionFactory;

    private $attributesTypesMap = [];

    public function __construct(TypeResolver $typeResolver, BlockDefinitionFactory $blockDefinitionFactory)
    {
        $this->typeResolver = $typeResolver;
        $this->blockDefinitionFactory = $blockDefinitionFactory;
    }

    public function resolvePageBlockAttributes(BlockValue $blockValue, $context)
    {
        $context['blockDefinition'] = $this->blockDefinitionFactory->getBlockDefinition($blockValue->getType());

        return $blockValue->getAttributes();
    }

    public function resolvePageBlockAttributeType(PageAttribute $blockAttribute, $context)
    {

        $attributeDefinition = $this->getBlockAttributeDefinition($context, $blockAttribute->getName());
        $attributeType = $attributeDefinition->getType();

        return isset($this->attributesTypesMap[$attributeType])
            ? $this->typeResolver->resolve($this->attributesTypesMap[$attributeType])
            : $this->typeResolver->resolve('BasePageBlockAttribute');
    }

    /**
     * @param array $context
     * @param string $name
     * @return \EzSystems\EzPlatformPageFieldType\FieldType\Page\Block\Definition\BlockAttributeDefinition
     */
    private function getBlockAttributeDefinition(ArrayObject $context, $name)
    {
        return $context['blockDefinition']->getAttributes()[$name];
    }
}