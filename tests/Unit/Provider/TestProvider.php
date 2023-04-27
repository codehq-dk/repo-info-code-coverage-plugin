<?php

namespace CodeHqDk\RepositoryInformation\Tests\Unit\Provider;

use CodeHqDk\RepositoryInformation\Provider\CodeCoverageInformationFactoryProvider;
use CodeHqDk\RepositoryInformation\Tests\Mock\MockProvider;
use PHPUnit\Framework\TestCase;

class TestProvider extends TestCase
{
    public function testAddFactory(): void
    {
        $provider = new MockProvider();

        $composer_provider = new CodeCoverageInformationFactoryProvider();

        $this->assertFalse($provider->register_in_dependency_injection_container_called);
        $this->assertFalse($provider->add_informaction_factory_to_registry_called);

        $composer_provider->addFactory($provider);

        $this->assertTrue($provider->register_in_dependency_injection_container_called);
        $this->assertTrue($provider->add_informaction_factory_to_registry_called);
    }
}
