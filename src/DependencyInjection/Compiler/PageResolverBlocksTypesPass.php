<?php
namespace BD\EzPlatformGraphQLPage\DependencyInjection\Compiler;

use BD\EzPlatformGraphQLPage\GraphQL\PageResolver;
use EzSystems\EzPlatformGraphQL\DependencyInjection\EzSystemsEzPlatformGraphQLExtension;
use EzSystems\EzPlatformGraphQL\EzSystemsEzPlatformGraphQLBundle;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Yaml\Yaml;

/**
 * Sets the registered PageBlock types to the PageResolver.
 */
class PageResolverBlocksTypesPass implements CompilerPassInterface
{
    const RESOLVER_ID = PageResolver::class;

    /**
     * Path of the file where the PageBlocksList type is defined.
     * @var string
     */
    private $pageBlocksIndexFile;

    public function __construct($schemaDir)
    {
        $this->pageBlocksIndexFile = $schemaDir . '/PageBlocksList.types.yml';
    }

    public function process(ContainerBuilder $container)
    {
        if (!$container->has(self::RESOLVER_ID)) {
            return;
        }

        if (!file_exists($this->pageBlocksIndexFile)) {
            return;
        }

        $typesMap = $this->getTypesMap($this->pageBlocksIndexFile);
        
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