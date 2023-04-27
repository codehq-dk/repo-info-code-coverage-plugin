<?php

namespace CodeHqDk\RepositoryInformation\Tests\Unit\Factory;

use CodeHqDk\RepositoryInformation\Factory\CodeCoverageInformationFactory;
use CodeHqDk\RepositoryInformation\InformationBlocks\CodeCoverageInformationBlock;
use CodeHqDk\RepositoryInformation\Model\RepositoryRequirements;
use Lcobucci\Clock\FrozenClock;
use PHPUnit\Framework\TestCase;

class CodeCoverageInformationFactoryTest extends TestCase
{
    public function testListAvailable(): void
    {
        $factory = new CodeCoverageInformationFactory();
        $expected = [CodeCoverageInformationBlock::class];
        $this->assertEquals($expected, $factory->listAvailableInformationBlocks());
    }

    public function testGetRepositoryRequirements(): void
    {
        $factory = new CodeCoverageInformationFactory();
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
  Classes: 66.67% (2/3)  
  Methods: 80.00% (4/5)  
  Lines:   96.15% (25/26)',
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
