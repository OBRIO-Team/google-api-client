<?php
declare(strict_types=1);

namespace ObrioTeam\GoogleApiClient;

/**
 * Class TestGAC
 * @package ObrioTeam\GoogleApiClient
 */
class TestGAC
{
    private string $test;

    /**
     * TestGAC constructor.
     * @param string $test
     */
    public function __construct(string $test = "test_value")
    {
        $this->test = $test;
    }

    /**
     * @return string
     */
    public function getTest(): string
    {
        return $this->test;
    }

    /**
     * @param string $test
     */
    public function setTest(string $test): void
    {
        $this->test = $test;
    }


}