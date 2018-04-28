<?php
use PHPUnit\Framework\TestCase;
$current_path = realpath(dirname(__FILE__));
require_once($current_path . '/src.php');

class TemplateTest extends TestCase
{
    
    public function setUp(){ }
    public function tearDown(){ }
    
    ################################ Functionality tests ################################

    public function testLoadData()
    {
        $string = md5(bin2hex(random_bytes(10)));
        $data = serialize(array('name' => $string));
        $connObj = New Template($data);
        $result = $connObj->loadData($data);
        $this->assertEquals($result['name'], $string);
    }

    public function testRender()
    {
        $string = md5(bin2hex(random_bytes(10)));
        $data = array('name' => $string);
        $connObj = New Template();
        $result = $connObj->render($data);
        $this->assertContains($string, $result);
    }

    public function testCreateCache()
    {
        $filename = '/tmp/' . md5(bin2hex(random_bytes(10)));
        $data = md5(bin2hex(random_bytes(10)));
        $connObj = New Template();
        $result = $connObj->createCache($filename, $data);
        $this->assertFileExists($filename);
    }

    ################################## Security tests ###################################

    public function testUnSerialization()
    {
        $filename = '/tmp/' . md5(bin2hex(random_bytes(10))) . '.phpss';
        $connObj = New Template();
        $connObj->cacheFile = $filename;
        $payload = serialize(array($connObj, 'name' => $filename));
        $connObj2 = New Template($payload);
        $this->assertFalse(file_exists($filename)); 
    }
}
