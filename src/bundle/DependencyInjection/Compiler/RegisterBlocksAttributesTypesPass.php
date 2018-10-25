<?php
namespace BD\PlatformPageGraphQLBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Yaml\Yaml;

/**
 * Registers the BlockAttributes GraphQL types.
 *
 * Since they are only referenced by an interface's resolver, they're not added by default.
 */
class RegisterBlocksAttributesTypesPass implements CompilerPassInterface
{
    const ATTRIBUTES_YAML = __DIR__ . '/../../Resources/config/graphql/PageBlockAttributes.types.yml';

    public function process(ContainerBuilder $container)
    {
        if (!$container->has('overblog_graphql.request_executor')) {
            return;
        }

        $executorDefinition = $container->getDefinition('overblog_graphql.request_executor');
        foreach ($executorDefinition->getMethodCalls() as $methodCall) {
            if ($methodCall[0] === 'addSchema') {
                $schemaDefinition = $container->getDefinition($methodCall[1][1]);
                $types = $schemaDefinition->getArgument(4);
                $attributesTypes = $this->getDefinedTypes();
                $schemaDefinition->setArgument(4, array_merge($types, $attributesTypes));

            }
        }
    }

    /**
     * @return string[]
     */
    private function getDefinedTypes()
    {
        return array_keys(Yaml::parseFile(self::ATTRIBUTES_YAML));
    }
}