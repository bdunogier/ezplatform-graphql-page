<?php
namespace BD\PlatformPageGraphQLBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Yaml\Yaml;

/**
 * Sets the registered PageBlock types to the PageResolver.
 */
class PageResolverBlocksTypesPass implements CompilerPassInterface
{
    const RESOLVER_ID = 'BD\PlatformPageGraphQL\GraphQL\PageResolver';

    public function process(ContainerBuilder $container)
    {
        if (!$container->has(self::RESOLVER_ID)) {
            return;
        }

        if (!$container->hasParameter('kernel.project_dir')) {
            return;
        }

        $pageBlocksIndexFile = $container->getParameter('kernel.project_dir') . '/src/AppBundle/Resources/config/graphql/PageBlocksList.types.yml';
        if (!file_exists($pageBlocksIndexFile)) {
            return;
        }

        $typesMap = $this->getTypesMap($pageBlocksIndexFile);
        
        $resolverDefinition = $container->getDefinition(self::RESOLVER_ID);
        $resolverDefinition->setArgument('$blocksTypesMap', $typesMap);
        $container->setDefinition(self::RESOLVER_ID, $resolverDefinition);
    }

    private function getTypesMap($pageBlocksIndexFile)
    {
        $definition = Yaml::parseFile($pageBlocksIndexFile);
        $types = [];
        foreach ($definition['PageBlocksList']['config']['values'] as $typeIdentifier => $enumValue) {
            $types[$typeIdentifier] = $enumValue['value'];
        }

        return $types;
    }
}