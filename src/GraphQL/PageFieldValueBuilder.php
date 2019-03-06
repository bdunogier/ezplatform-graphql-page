<?php
namespace BD\EzPlatformGraphQLPage\GraphQL;

use eZ\Publish\API\Repository\Values\ContentType\FieldDefinition;
use EzSystems\EzPlatformGraphQL\Schema\Domain\Content\FieldValueBuilder\FieldValueBuilder;

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