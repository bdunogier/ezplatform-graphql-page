<?php
namespace BD\EzPlatformGraphQLPage\GraphQL;

use EzSystems\EzPlatformGraphQL\Schema\Builder;
use EzSystems\EzPlatformPageFieldType\FieldType\Page\Block\Definition\BlockAttributeDefinition;
use EzSystems\EzPlatformPageFieldType\FieldType\Page\Block\Definition\BlockDefinitionFactory;

class PageSchemaBuilder implements Builder
{
    /**
     * @var BlockDefinitionFactory
     */
    private $blockDefinitionFactory;

    private $attributesTypesMap = [
        'integer' => 'Int',
        'string' => 'String',
        'url' => 'String',
        'text' => 'String',
        'embed' => [
            'DomainContent',
            '@=resolver("ContentById", [value.getAttribute("%field%").getValue()])'
        ],
        'select' => null,
        'multiple' => null,
        'radio' => null,
        'contenttypelist' => null,
        'schedule' => null,
    ];

    public function __construct(BlockDefinitionFactory $blockDefinitionFactory)
    {
        $this->blockDefinitionFactory = $blockDefinitionFactory;
    }

    public function build(array &$schema)
    {
        $schema['PageBlocksList'] = [
            'type' => 'enum',
            'config' => [
                'values' => []
            ]
        ];
        foreach ($this->blockDefinitionFactory->getBlockIdentifiers() as $blockIdentifier) {
            $blockDefinition = $this->blockDefinitionFactory->getBlockDefinition($blockIdentifier);

            $fields = [];
            foreach ($blockDefinition->getAttributes() as $attributeDefinition) {
                $this->addAttributeFieldDefinition($fields, $attributeDefinition);
            }
            $blockTypeName = $this->makeBlockTypeName($blockIdentifier);

            $schema['PageBlocksList']['config']['values'][$blockIdentifier] = ['value' => $blockTypeName];
            $schema[$blockTypeName] = [
                'type' => 'object',
                'inherits' => ['BasePageBlock'],
                'config' => [
                    'description' => $blockDefinition->getName(),
                    'interfaces' => ['PageBlock'],
                    'fields' => $fields,
                ]
            ];

            $views = [];
            foreach ($blockDefinition->getViews() as $viewIdentifier => $view) {
                $views[$viewIdentifier] = [
                    'value' => $view['template'],
                    'description' => $view['name'],
                ];
            }
            $schema[$this->makeBlockViewsTypeName($blockIdentifier)] = [
                'type' => 'enum',
                'config' => [
                    'description' => 'Views for the ' . $blockTypeName . ' page block',
                    'values' => $views,
                ]
            ];
        }
    }

    private function makeBlockTypeName($blockIdentifier)
    {
        return ucfirst($blockIdentifier) . 'PageBlock';
    }

    private function makeBlockViewsTypeName($blockIdentifier)
    {
        return ucfirst($blockIdentifier) . 'PageBlockViews';
    }

    private function addAttributeFieldDefinition(array &$attributesSchema, BlockAttributeDefinition $attributeDefinition)
    {
        if (!isset($this->attributesTypesMap[$attributeDefinition->getType()])) {
            return;
        }

        $fieldIdentifier = $attributeDefinition->getIdentifier();
        $mapping = $this->attributesTypesMap[$attributeDefinition->getType()];

        if (is_array($mapping)) {
            if (count($mapping) !== 2) {
                throw new \InvalidArgumentException("Invalid mapping array, two elements expected");
            }
            list($type, $resolve) = $mapping;
        } else {
            $type = $mapping;
            $resolve = '@=value.getAttribute("%field%").getValue()';
        }

        $resolve = str_replace(['%field%'], [$fieldIdentifier], $resolve);

        $attributesSchema[$fieldIdentifier] = [
            'type' => $type,
            'resolve' => $resolve,
            'description' => $attributeDefinition->getName(),
        ];
    }
}