<?php
namespace BD\PlatformPageGraphQL\GraphQL;

use BD\EzPlatformGraphQLBundle\DomainContent\FieldValueBuilder\FieldValueBuilder;
use BD\EzPlatformGraphQLBundle\DomainContent\NameHelper;
use eZ\Publish\API\Repository\ContentTypeService;
use eZ\Publish\API\Repository\Values\ContentType\FieldDefinition;
use eZ\Publish\Core\QueryType\QueryTypeRegistry;

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
            'resolve' => '@=resolver("DomainFieldValue", [value, "page"]).value.getPage()',
        ];
    }
}