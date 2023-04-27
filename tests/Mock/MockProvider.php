<?php

namespace CodeHqDk\RepositoryInformation\Tests\Mock;

use CodeHqDk\RepositoryInformation\Factory\InformationFactory;
use CodeHqDk\RepositoryInformation\Service\ProviderDependencyService;

class MockProvider implements ProviderDependencyService
{
    public bool $add_informaction_factory_to_registry_called = false;
    public bool $register_in_dependency_injection_container_called = false;

    public function registerClassInDependencyContainer(string $fully_qualified_class_name): void
    {
        $this->register_in_dependency_injection_container_called = true;
    }

    public function registerObjectInDependencyContainer($object): void
    {
        $this->register_in_dependency_injection_container_called = true;
    }

    public function addInformactionFactoryToRegistry(InformationFactory $information_factory): void
    {
        $this->add_informaction_factory_to_registry_called = true;
    }
}