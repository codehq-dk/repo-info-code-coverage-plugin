<?php

namespace CodeHqDk\RepositoryInformation\CodeCoverage\Tests\Unit\Factory;

use CodeHqDk\RepositoryInformation\CodeCoverage\Factory\CodeCoverageInformationFactory;
use CodeHqDk\RepositoryInformation\CodeCoverage\InformationBlocks\CodeCoverageInformationBlock;
use CodeHqDk\RepositoryInformation\Model\RepositoryRequirements;
use Lcobucci\Clock\FrozenClock;
use PHPUnit\Framework\TestCase;

/**
 * @group whitelisted
 */
class CodeCoverageInformationFactoryTest extends TestCase
{
    private string $output_path;

    protected function setup(): void
    {
        $this->output_path = dirname(__FILE__, 2) . '/data';
    }

    public function testListAvailable(): void
    {
        $factory = new CodeCoverageInformationFactory($this->output_path);
        $expected = [CodeCoverageInformationBlock::class];
        $this->assertEquals($expected, $factory->listAvailableInformationBlocks());
    }

    public function testGetRepositoryRequirements(): void
    {
        $factory = new CodeCoverageInformationFactory($this->output_path);
        $this->assertInstanceOf(RepositoryRequirements::class, $factory->getRepositoryRequirements());
    }

    public function testCreateBlocks(): void
    {
        $factory = new CodeCoverageInformationFactory(
            '/Users/joej/Kodus2/repo-info-code-coverage-plugin/tests/data',
            FrozenClock::fromUTC()
        );

        $expected_block = new CodeCoverageInformationBlock(
            'Code Coverage information (using PhpUnit)',
            'Code coverage',
            'Summary:                
  Classes: 75.00% (3/4)  
  Methods: 88.89% (8/9)  
  Lines:   92.59% (50/54)',
            time(),
            'Cannot build code coverage information - .../bin/phpunit or phpunit.xml is missing',
            'This is information from the Code Coverage Information Factory',
        );

        $path_to_sample_repository = dirname(__FILE__, 3) . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'repo-info-example-plugin';
        $actual_blocks = $factory->createBlocks($path_to_sample_repository);
        /**
         * @var CodeCoverageInformationBlock $actual_block
         */
        $actual_block = array_pop($actual_blocks);

        $this->assertEquals($expected_block->getHeadline(), $actual_block->getHeadline());
        $this->assertEquals($expected_block->getLabel(), $actual_block->getLabel());
        $this->assertEquals($expected_block->getValue(), $actual_block->getValue());
        $this->assertEquals($expected_block->getModifiedTimestamp(), $actual_block->getModifiedTimestamp());
        $this->assertEquals($expected_block->getInformationOrigin(), $actual_block->getInformationOrigin());
    }
}
