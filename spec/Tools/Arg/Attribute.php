<?php
namespace spec\BD\EzPlatformGraphQLPage\Tools\Argument;

use EzSystems\EzPlatformPageFieldType\FieldType\Page\Block\Definition\BlockAttributeDefinition;
use Prophecy\Argument\Token\CallbackToken;

class AttributeArgument
{
    /**
     * @param $identifier
     * @return CallbackToken|BlockAttributeDefinition
     */
    public static function withIdentifier($identifier)
    {
        return new CallbackToken(
            function (BlockAttributeDefinition $block) use ($identifier) {
                return $block->getIdentifier() === $identifier;
            }
        );
    }
}