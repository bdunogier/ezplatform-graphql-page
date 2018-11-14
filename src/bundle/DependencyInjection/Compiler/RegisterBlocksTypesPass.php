<?php
namespace BD\EzPlatformGraphQLPageBundle\DependencyInjection\Compiler;

use BD\EzPlatformGraphQLBundle\DependencyInjection\BDEzPlatformGraphQLExtension;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Yaml\Yaml;

/**
 * Registers the BlockAttributes GraphQL types.
 *
 * Since they are only referenced by an interface's resolver, they're not added by default.
 */
class RegisterBlocksTypesPass implements CompilerPassInterface
{
    const TYPES_PATTERN = '[A-Z]*PageBlock.types.yml';

    public function process(ContainerBuilder $container)
    {
        if (!$container->has('overblog_graphql.request_executor')) {
            return;
        }

        $graphQLDefinitionsDirectory = BDEzPlatformGraphQLExtension::SCHEMA_DIR;
        if (!file_exists($graphQLDefinitionsDirectory) || !is_dir($graphQLDefinitionsDirectory)) {
            return;
        }

        $executorDefinition = $container->getDefinition('overblog_graphql.request_executor');
        foreach ($executorDefinition->getMethodCalls() as $methodCall) {
            // Blocks types are added to schemaBuilder::create's 4th argument
            if ($methodCall[0] === 'addSchema') {
                $schemaDefinition = $container->getDefinition($methodCall[1][1]);
                $types = array_merge(
                    $schemaDefinition->getArgument(4),
                    $this->getDefinedTypes($graphQLDefinitionsDirectory)
                );
                $schemaDefinition->setArgument(4, $types);
            }
        }
    }

    /**
     * @param String $directory path to the directory where GraphQL types are defined (as yaml).
     * @return string[]
     */
    private function getDefinedTypes($directory)
    {
        $finder = new Finder();
        $types = [];
        foreach ($finder->files()->name(self::TYPES_PATTERN)->in($directory) as $file) {
            $extraTypes = array_filter(
                array_keys(Yaml::parseFile($file)),
                function($typeName) {
                    return !preg_match('/BlockViews$/', $typeName);
                },
                ARRAY_FILTER_USE_BOTH
            );
            $types = array_merge($types, $extraTypes);
        }

        return $types;
    }
}