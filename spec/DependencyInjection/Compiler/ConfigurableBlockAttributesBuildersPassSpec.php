<?php

namespace spec\BD\EzPlatformGraphQLPage\DependencyInjection\Compiler;

use BD\EzPlatformGraphQLPage\DependencyInjection\Compiler\ConfigurableBlockAttributesBuildersPass as Pass;
use BD\EzPlatformGraphQLPage\GraphQL\Schema\BlockAttributeBuilder\Configurable;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use spec\BD\EzPlatformGraphQLPage\Tools\Arg\ConfigurableBuilderDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\Yaml\Yaml;

class ConfigurableBlockAttributesBuildersPassSpec extends ObjectBehavior
{
    const CONFIGURATION_FILE = 'src/Resources/config/attributes_builders.yml';

    function it_is_initializable()
    {
        $this->shouldHaveType(Pass::class);
    }

    function it_defines_services_with_Configurable_BlockAttributeBuilder_for_configured_types(ContainerBuilder $container)
    {
        $configuration = [
            'type1' => 'String',
            'type2' => [
                'type' => 'Integer',
                'resolve' => '@resolve("SomeResolver")'
            ]
        ];

        $container->hasParameter(Pass::ATTRIBUTES_PARAMETER)->willReturn(true);
        $container->getParameter(Pass::ATTRIBUTES_PARAMETER)->willReturn($configuration);

        $container
            ->setDefinition(
                Configurable::class . '\\type1',
                Argument::type(Definition::class)
            )
            ->shouldBeCalled();

        $container
            ->setDefinition(
                Configurable::class . '\\type2',
                Argument::type(Definition::class)
            )
            ->shouldBeCalled();

        $this->process($container);
    }

    function it_tags_created_services(ContainerBuilder $container)
    {
        $configuration = ['type' => 'String'];

        $container->hasParameter(Pass::ATTRIBUTES_PARAMETER)->willReturn(true);
        $container->getParameter(Pass::ATTRIBUTES_PARAMETER)->willReturn($configuration);

        $container
            ->setDefinition(
                Argument::any(),
                Argument::that(
                    function (Definition $definition) {
                        if (!$definition->hasTag(Pass::TAG)) {
                            return false;
                        }

                        $tag = $definition->getTag(Pass::TAG)[0];

                        return isset($tag[Pass::TAG_ATTRIBUTE]) && $tag[Pass::TAG_ATTRIBUTE] === 'type';
                    }
                )
            )
            ->shouldBeCalled();

        $this->process($container);
    }

    function it_builds_string_BlockAttributeDefinitions_with_String_type(ContainerBuilder $container)
    {
        $this->configureContainerForType('string', $container);

        $container
            ->setDefinition(
                Argument::type('string'),
                ConfigurableBuilderDefinition::withType('String')
            )
            ->shouldBeCalled();

        $this->process($container);
    }

    function it_builds_integer_BlockAttributeDefinitions_with_Int_type(ContainerBuilder $container)
    {
        $this->configureContainerForType('integer', $container);

        $container
            ->setDefinition(
                Argument::type('string'),
                ConfigurableBuilderDefinition::withType('Int')
            )
            ->shouldBeCalled();

        $this->process($container);
    }

    function it_builds_url_BlockAttributeDefinitions_with_String_type(ContainerBuilder $container)
    {
        $this->configureContainerForType('url', $container);

        $container
            ->setDefinition(
                Argument::type('string'),
                ConfigurableBuilderDefinition::withType('String')
            )
            ->shouldBeCalled();

        $this->process($container);
    }

    function it_builds_text_BlockAttributeDefinitions_with_String_type(ContainerBuilder $container)
    {
        $this->configureContainerForType('text', $container);

        $container
            ->setDefinition(
                Argument::type('string'),
                ConfigurableBuilderDefinition::withType('String')
            )
            ->shouldBeCalled();

        $this->process($container);
    }

    /**
     * @param string $type
     * @param ContainerBuilder $container
     * @return void
     */
    private function configureContainerForType($type, ContainerBuilder $container)
    {
        $configuration = Yaml::parseFile(self::CONFIGURATION_FILE);
        $configuration = [
            $type => $configuration
                ['parameters']
                [Pass::ATTRIBUTES_PARAMETER]
                [$type]
        ];

        $container
            ->hasParameter(Pass::ATTRIBUTES_PARAMETER)
            ->willReturn(true);

        $container
            ->getParameter(Pass::ATTRIBUTES_PARAMETER)
            ->willReturn($configuration);

    }
}
