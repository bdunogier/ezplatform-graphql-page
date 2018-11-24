<?php
namespace spec\BD\EzPlatformGraphQLPage\Tools\Arg;

use EzSystems\EzPlatformPageFieldType\FieldType\Page\Block\Definition\BlockDefinition;
use Prophecy\Argument\Token\CallbackToken;

class Block
{
    /**
     * @param $identifier
     * @return CallbackToken|BlockDefinition
     */
    public static function withIdentifier($identifier)
    {
        return new CallbackToken(
            function (BlockDefinition $block) use ($identifier) {
                return $block->getIdentifier() === $identifier;
            }
        );
    }
}