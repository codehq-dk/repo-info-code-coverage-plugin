<?php

namespace Provider;

use CodeCoverageInformationFactory;
use CodeHqDk\RepositoryInformation\Factory\InformationFactoryProvider;
use CodeHqDk\RepositoryInformation\Service\ProviderDependencyService;

class CodeCoverageInformationFactoryProvider implements InformationFactoryProvider
{
    public function addFactory(ProviderDependencyService $provider_dependency_service): void
    {
        $provider_dependency_service->registerClassInDependencyContainer(CodeCoverageInformationFactory::class);
        $provider_dependency_service->addInformactionFactoryToRegistry(new CodeCoverageInformationFactory());
    }
}
