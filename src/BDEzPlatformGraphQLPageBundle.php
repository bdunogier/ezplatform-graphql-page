<?php
namespace BD\EzPlatformGraphQLPage;

use BD\EzPlatformGraphQLPage\DependencyInjection\Compiler;
use EzSystems\EzPlatformGraphQL\EzSystemsEzPlatformGraphQLBundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class BDEzPlatformGraphQLPageBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        
        $container->addCompilerPass(new Compiler\ConfigurableBlockAttributesBuildersPass());
        $container->addCompilerPass(new Compiler\BlockAttributesBuildersPass());
        $container->addCompilerPass(new Compiler\RegisterBlocksAttributesTypesPass(EzSystemsEzPlatformGraphQLBundle::EZPLATFORM_CONFIG_DIR));
        $container->addCompilerPass(new Compiler\RegisterBlocksTypesPass(EzSystemsEzPlatformGraphQLBundle::EZPLATFORM_CONFIG_DIR));
        $container->addCompilerPass(new Compiler\PageResolverBlocksTypesPass(EzSystemsEzPlatformGraphQLBundle::EZPLATFORM_CONFIG_DIR));
    }
}
