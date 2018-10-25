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

    private $attributesTypesMap = [
        'embed' => 'PageBlockAttributeEmbed',
    ];

    private $blocksTypesMap = [];

    public function __construct(
        TypeResolver $typeResolver,
        BlockDefinitionFactory $blockDefinitionFactory,
        array $blocksTypesMap = []
    ) {
        $this->typeResolver = $typeResolver;
        $this->blockDefinitionFactory = $blockDefinitionFactory;
        $this->blocksTypesMap = $blocksTypesMap;
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

    public function resolvePageBlockType(BlockValue $blockValue)
    {
        $blockType = $blockValue->getType();

        return isset($this->blocksTypesMap[$blockType])
            ? $this->typeResolver->resolve($this->blocksTypesMap[$blockType])
            : $this->typeResolver->resolve('BasePageBlock');
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