<?php
namespace BD\EzPlatformGraphQLPage\GraphQL\Schema\Worker;

use EzSystems\EzPlatformPageFieldType\FieldType\Page\Block\Definition\BlockAttributeDefinition;
use EzSystems\EzPlatformPageFieldType\FieldType\Page\Block\Definition\BlockDefinition;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;

class NameHelper
{
    /**
     * @var \Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter
     */
    private $caseConverter;

    public function __construct()
    {
        $this->caseConverter = new CamelCaseToSnakeCaseNameConverter(null, false);
    }

    public function blockType(BlockDefinition $blockDefinition)
    {
        return ucfirst($this->toCamelCase($blockDefinition->getIdentifier())) . 'PageBlock';
    }

    public function viewsType(BlockDefinition $blockDefinition)
    {
        return ucfirst($this->toCamelCase($blockDefinition->getIdentifier())) . 'PageBlockViews';
    }

    public function attributeField(BlockAttributeDefinition $blockAttributeDefinition)
    {
        return lcfirst($this->toCamelCase($blockAttributeDefinition->getIdentifier()));
    }

    private function toCamelCase($string)
    {
        return $this->caseConverter->denormalize($string);
    }
}