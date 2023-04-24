<?php

use CodeHqDk\RepositoryInformation\Factory\InformationFactory;
use CodeHqDk\RepositoryInformation\Model\RepositoryRequirements;
use InformationBlocks\CodeCoverageInformationBlock;

class CodeCoverageInformationFactory implements InformationFactory
{
    public function __construct(string $code_coverage_output_path)
    {
    }

    public function createBlocks(
        string $local_path_to_code,
        array $information_block_types_to_create = self::DEFAULT_ENABLED_BLOCKS
    ): array {
        if (!in_array(CodeCoverageInformationBlock::class, $information_block_types_to_create)) {
            return [];
        }

        return [
            new CodeCoverageInformationBlock(
                'Code Coverage information (using PhpUnit)',
                'Code coverage',
                '100 %',
                time(),
                'Further code coverage details here',
                'This is information from the Code Coverage Information Factory',
            )
        ];
    }

    public function getRepositoryRequirements(): RepositoryRequirements
    {
        return new RepositoryRequirements(false, false, false, false);
    }

    public function listAvailableInformationBlocks(): array
    {
        return [
            CodeCoverageInformationBlock::class
        ];
    }
}
