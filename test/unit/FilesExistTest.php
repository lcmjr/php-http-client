<?php

namespace SendGrid\Test;

class FilesExistTest extends \PHPUnit_Framework_TestCase
{
    public function testFileArePresentInRepo()
    {
        $rootDir = __DIR__ . '/../..';

        $this->assertFileExists("$rootDir/.gitignore");
        $this->assertFileExists("$rootDir/.env_sample");
        $this->assertFileExists("$rootDir/.travis.yml");
        $this->assertFileExists("$rootDir/.codeclimate.yml");
        $this->assertFileExists("$rootDir/CHANGELOG.md");
        $this->assertFileExists("$rootDir/CODE_OF_CONDUCT.md");
        $this->assertFileExists("$rootDir/CONTRIBUTING.md");
        $this->assertFileExists("$rootDir/.github/ISSUE_TEMPLATE");
        $this->assertFileExists("$rootDir/LICENSE.md");
        $this->assertFileExists("$rootDir/.github/PULL_REQUEST_TEMPLATE");
        $this->assertFileExists("$rootDir/README.md");
        $this->assertFileExists("$rootDir/TROUBLESHOOTING.md");
        $this->assertFileExists("$rootDir/USAGE.md");
        $this->assertFileExists("$rootDir/USE_CASES.md");

        $composeExists = file_exists('./docker-compose.yml') || file_exists('./docker/docker-compose.yml');
        $this->assertTrue($composeExists);

        $dockerExists = file_exists('./Docker') || file_exists('./docker/Docker');
        $this->assertTrue($dockerExists);
    }
}

