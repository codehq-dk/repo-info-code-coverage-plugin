<?php

namespace CodeHqDk\RepositoryInformation\CodeCoverage\Factory;

use CodeHqDk\LinuxBashHelper\Bash;
use CodeHqDk\LinuxBashHelper\Environment;
use CodeHqDk\LinuxBashHelper\Exception\LinuxBashHelperException;
use CodeHqDk\RepositoryInformation\Exception\RepositoryInformationException;
use CodeHqDk\RepositoryInformation\Factory\InformationFactory;
use CodeHqDk\RepositoryInformation\Model\RepositoryRequirements;
use CodeHqDk\RepositoryInformation\CodeCoverage\InformationBlocks\CodeCoverageInformationBlock;
use Lcobucci\Clock\Clock;
use Lcobucci\Clock\SystemClock;

class CodeCoverageInformationFactory implements InformationFactory
{
    public const DEFAULT_ENABLED_BLOCKS = [
        CodeCoverageInformationBlock::class
    ];

    public function __construct(
        private readonly string $code_coverage_output_path,
        private ?Clock $clock = null
    ) {
        if ($this->clock === null) {
            $this->clock = SystemClock::fromSystemTimezone();
        }
    }

    public function createBlocks(
        string $local_path_to_code,
        array $information_block_types_to_create = self::DEFAULT_ENABLED_BLOCKS
    ): array {
        if (!in_array(CodeCoverageInformationBlock::class, $information_block_types_to_create)) {
            return [];
        }

        try {
            $code_coverage = $this->getCodeCoverage($local_path_to_code);
        } catch (RepositoryInformationException) {
            return
                [
                    $this->buildNoCodeCoverageBlock()
                ];
        }

        return [
            new CodeCoverageInformationBlock(
                'Code Coverage information (using PhpUnit)',
                'Code coverage',
                $code_coverage,
                $this->clock->now()->getTimestamp(),
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

    /**
     * @throws RepositoryInformationException
     * @throws LinuxBashHelperException
     */
    public function getCodeCoverage(string $local_path_to_code): string
    {
        $php = Environment::getPhpPath();
        $phpunit = $local_path_to_code . "/vendor/bin/phpunit";
        $path_to_test_code = $local_path_to_code . '/tests';

        if (!file_exists($phpunit) || !file_exists($path_to_test_code)) {
            throw new RepositoryInformationException('Cannot build code coverage information - .../bin/phpunit or /tests is missing');
        }

        $output_path = $this->code_coverage_output_path . "/code_coverage_report.txt";
        $coverage_filter = ' --coverage-filter /Users/joej/Kodus2/repo-info-code-coverage-plugin/tests/data/repo-info-example-plugin/tests';
        $command_to_run = "{$php} {$phpunit} {$path_to_test_code} --coverage-text={$output_path}" . $coverage_filter;

        Bash::runCommand($command_to_run);

        $initial_report = file_get_contents($output_path);
        $initial_report_array = explode(PHP_EOL, $initial_report);
        $final_report_array = array_slice($initial_report_array, 5, 4);

        return ltrim(implode(PHP_EOL, $final_report_array), ' ');
    }

    private function buildNoCodeCoverageBlock(): CodeCoverageInformationBlock
    {
        return new CodeCoverageInformationBlock(
            'Code Coverage information (using PhpUnit)',
            'Code coverage',
            'No code coverage information available',
            $this->clock->now()->getTimestamp(),
            'Cannot build code coverage information - .../bin/phpunit or /tests is missing',
            'This is information from the Code Coverage Information Factory',
        );
    }
}
