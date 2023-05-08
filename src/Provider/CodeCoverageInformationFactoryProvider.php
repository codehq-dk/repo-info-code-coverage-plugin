<?php

namespace CodeHqDk\RepositoryInformation\CodeCoverage\Provider;

use CodeHqDk\RepositoryInformation\CodeCoverage\Factory\CodeCoverageInformationFactory;
use CodeHqDk\RepositoryInformation\Factory\InformationFactoryProvider;
use CodeHqDk\RepositoryInformation\Service\ProviderDependencyService;

class CodeCoverageInformationFactoryProvider implements InformationFactoryProvider
{
    public function __construct(private readonly string $code_coverage_output_path)
    {
    }

    public function addFactory(ProviderDependencyService $provider_dependency_service): void
    {
        $provider_dependency_service->registerClassInDependencyContainer(CodeCoverageInformationFactory::class);
        $provider_dependency_service->addInformactionFactoryToRegistry(new CodeCoverageInformationFactory($this->code_coverage_output_path));
    }
}
