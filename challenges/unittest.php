<?php
$current_path = realpath(dirname(__FILE__));
require_once($current_path . '/src.php');
class SrcTest extends PHPUnit_Framework_TestCase
{
    public function setUp(){ }
    public function tearDown(){ }
    public function testIsSanitized()
    {
        $connObj = new Src();
        $payload = "'onload='alert(2)";
        $result = $connObj->sanitize($payload);
        $this->assertNotContains($payload, $result);
    }
}