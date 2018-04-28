<?php
use PHPUnit\Framework\TestCase;
$current_path = realpath(dirname(__FILE__));
require_once($current_path . '/src.php');

class TokenStorageTest extends TestCase
{
    public function setUp()
    {
        if (!is_dir('/tmp/tokens/')) {
            mkdir('/tmp/tokens/', 0777, true);
        }
    }
    public function tearDown(){ }
    
    ################################ Functionality tests ################################

    public function testcreateToken()
    {
        $connObj = new TokenStorage();
        $string = bin2hex(random_bytes(10));
        $result = $connObj->createToken($string);
        $this->assertFileExists('/tmp/tokens/' . md5($string), 'INFO: File creation failed. createToken() function is not working as expected !');
    }

    public function testclearToken()
    {
        $connObj = new TokenStorage();
        $string = bin2hex(random_bytes(10));
        $result = $connObj->createToken($string);
        if(file_exists('/tmp/tokens/' . md5($string)))
        {
            $connObj->clearToken(md5($string));
            $this->assertFalse(file_exists('/tmp/tokens/' . md5($string)), 'INFO: File deletion failed. clearToken() is not working as expected !');
        }
    }

    ################################## Security tests ##################################

    public function testdeleteToken()
    {
        $connObj = new TokenStorage();
        $string = md5(bin2hex(random_bytes(10)));
        touch('/tmp/' . $string);
        if(file_exists('/tmp/'. $string))
        {
            $connObj->clearToken('../' . $string);
            $this->assertTrue(file_exists('/tmp/' . $string), 'INFO: Arbitrary files got deleted. deleteToken() is deleting arbitrary files !');
        }    
    }
}
