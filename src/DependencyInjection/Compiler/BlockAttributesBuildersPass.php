<?php
namespace BD\EzPlatformGraphQLPage\DependencyInjection\Compiler;

use BD\EzPlatformGraphQLPage\GraphQL\Schema\Worker\BlockAttributeField;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class BlockAttributesBuildersPass implements CompilerPassInterface
{
    const TAG = 'ezplatform_graphql_page.block_attribute_builder';

    public function process(ContainerBuilder $container)
    {
        if (!$container->has(BlockAttributeField::class)) {
            return;
        }

        $definition = $container->findDefinition(BlockAttributeField::class);
        $taggedServices = $container->findTaggedServiceIds(self::TAG);

        $builders = [];
        foreach ($taggedServices as $id => $tags) {
            foreach ($tags as $tag) {
                if (!isset($tag['type'])) {
                    throw new \InvalidArgumentException(
                        "The ezplatform_graphql.field_value_builder tag requires a 'type' property set to the Field Type's identifier"
                    );
                }

                $builders[$tag['type']] = new Reference($id);
            }
        }

        $definition->setArgument('$attributesBuilders', $builders);
    }
}