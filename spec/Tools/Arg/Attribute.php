<?php
namespace spec\BD\EzPlatformGraphQLPage\Tools\Arg;

use EzSystems\EzPlatformPageFieldType\FieldType\Page\Block\Definition\BlockAttributeDefinition;
use Prophecy\Argument\Token\CallbackToken;

class Attribute
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