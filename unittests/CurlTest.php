<?php

namespace UnitTests;

use PHPUnit\Framework\TestCase;
use Controllers\CurlController;

/**
 * Test api methods from here
 */
class CurlTest extends TestCase
{
    public function testAPI()
    {
        $testURL = "https://api.npoint.io/6f81bbc4b547399e70ea";
        $curl = new CurlController($testURL);
        $curlResponse = $curl->makeCurlRequest();
        $this->assertIsArray(json_decode(json_decode($curlResponse)));
        $this->assertGreaterThan(0,sizeof(json_decode(json_decode($curlResponse))));
    }
}
