<?php
namespace BD\EzPlatformGraphQLPage\GraphQL;

use EzSystems\EzPlatformGraphQL\DomainContent\FieldValueBuilder\FieldValueBuilder;
use eZ\Publish\API\Repository\Values\ContentType\FieldDefinition;

class PageFieldValueBuilder implements FieldValueBuilder
{
    /**
     * @param FieldDefinition $fieldDefinition
     * @return array GraphQL definition array for the Field Value
     */
    public function buildDefinition(FieldDefinition $fieldDefinition)
    {
        return [
            'type' => 'Page',
            'resolve' => sprintf(
                '@=resolver("DomainFieldValue", [value, "%s"]).value.getPage()',
                $fieldDefinition->identifier
            ),
        ];
    }
}