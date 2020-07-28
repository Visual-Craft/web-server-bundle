<?php

namespace VisualCraft\Bundle\WebServerBundle\Tests\DependencyInjection;

use PHPUnit\Framework\TestCase;
use Symfony\Bridge\Monolog\Formatter\ConsoleFormatter;
use VisualCraft\Bundle\WebServerBundle\DependencyInjection\WebServerExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class WebServerExtensionTest extends TestCase
{
    /**
     * @group legacy
     */
    public function testLoad()
    {
        $container = new ContainerBuilder();
        $container->setParameter('kernel.project_dir', __DIR__);
        $container->setParameter('kernel.cache_dir', __DIR__.'/var/cache/test');
        $container->setParameter('kernel.environment', 'test');
        (new WebServerExtension())->load([], $container);

        $this->assertSame(
            __DIR__.'/test',
            $container->getDefinition('web_server.command.server_run')->getArgument(0)
        );
        $this->assertSame(
            __DIR__.'/test',
            $container->getDefinition('web_server.command.server_start')->getArgument(0)
        );

        $this->assertSame(
            __DIR__.'/var/cache',
            $container->getDefinition('web_server.command.server_run')->getArgument(2)
        );
        $this->assertSame(
            __DIR__.'/var/cache',
            $container->getDefinition('web_server.command.server_start')->getArgument(2)
        );
        $this->assertSame(
            __DIR__.'/var/cache',
            $container->getDefinition('web_server.command.server_stop')->getArgument(0)
        );
        $this->assertSame(
            __DIR__.'/var/cache',
            $container->getDefinition('web_server.command.server_status')->getArgument(0)
        );

        $this->assertTrue($container->hasDefinition('web_server.command.server_run'));
        $this->assertTrue($container->hasDefinition('web_server.command.server_start'));
        $this->assertTrue($container->hasDefinition('web_server.command.server_stop'));
        $this->assertTrue($container->hasDefinition('web_server.command.server_status'));
        $this->assertSame(class_exists(ConsoleFormatter::class), $container->hasDefinition('web_server.command.server_log'));
    }
}
