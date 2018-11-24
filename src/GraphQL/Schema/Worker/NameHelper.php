<?php
/**
 * Created by PhpStorm.
 * User: bdunogier
 * Date: 24/11/2018
 * Time: 12:27
 */

namespace BD\EzPlatformGraphQLPage\GraphQL\Schema\Worker;


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

    private function toCamelCase($string)
    {
        return $this->caseConverter->denormalize($string);
    }
}