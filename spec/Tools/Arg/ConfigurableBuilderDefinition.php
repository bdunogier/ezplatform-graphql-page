<?php
namespace spec\BD\EzPlatformGraphQLPage\Tools\Arg;

use Prophecy\Argument\Token\CallbackToken;
use Symfony\Component\DependencyInjection\Definition;

class ConfigurableBuilderDefinition
{
    public static function withType($type)
    {
        return new CallbackToken(
            function (Definition $definition) use ($type) {
                return $definition->getArgument('$type') === $type;
            }
        );
    }

    public static function withResolve($resolve)
    {
        return new CallbackToken(
            function (Definition $definition) use ($resolve) {
                return $definition->getArgument('$resolver') === $resolve;
            }
        );
    }
}