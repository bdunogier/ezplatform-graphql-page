<?php
namespace BD\EzPlatformGraphQLPage\DependencyInjection\Compiler;

use BD\EzPlatformGraphQLPage\GraphQL\Schema\BlockAttributeBuilder;
use BD\EzPlatformGraphQLPage\GraphQL\Schema\Worker\NameHelper;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

class ConfigurableBlockAttributesBuildersPass implements CompilerPassInterface
{
    const ATTRIBUTES_PARAMETER = 'ezplatform_graphql_page.blocks_attributes';
    const TAG = 'ezplatform_graphql_page.block_attribute_builder';
    const TAG_ATTRIBUTE = 'type';

    public function process(ContainerBuilder $container)
    {
        if (!$container->hasParameter(self::ATTRIBUTES_PARAMETER)) {
            return;
        }

        $commonDefinition = new Definition();
        $commonDefinition->setClass(BlockAttributeBuilder\Configurable::class);
        $commonDefinition->setArgument('$nameHelper', new Reference(NameHelper::class));

        $configurationItems = $container->getParameter(self::ATTRIBUTES_PARAMETER);
        foreach ($configurationItems as $typeIdentifier => $configurationItem) {
            $definition = clone $commonDefinition;
            if (is_string($configurationItem)) {
                $definition->setArgument('$type', $configurationItem);
                $id = BlockAttributeBuilder\Configurable::class . '\\' . $typeIdentifier;
            } elseif (is_array($configurationItem)) {
                $definition->setArgument('$type', $configurationItem['type']);
                $definition->setArgument('$resolve', $configurationItem['resolve']);
                $id = BlockAttributeBuilder\Configurable::class . '\\' . $typeIdentifier;
            }

            if (!isset($id)) {
                continue;
            }

            $definition->addTag(self::TAG, [self::TAG_ATTRIBUTE => $typeIdentifier]);

            $container->setDefinition($id, $definition);
        }
    }
}