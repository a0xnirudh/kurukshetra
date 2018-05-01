---
title: Writing a challenge
sidebar: mydoc_sidebar
permalink: write_challenge.html
summary: Writing a challenge and adding into kurukshetra requires writing both code and unit test in a correct format.
---

In order to write the challenge and add the same into Kurukshetra, the code and unittest has to be written and should be inserted into the framework via admin dashboard. Let's discuss how to write a challenge and breakdown the unittest running behind the scenes for validating the same.

### Writing challenges

All the challenges should be written in **`class`** format along with all the necessary functions written under the particular class. Let's take an example challenge from [RIPS Security calendar 2017](https://www.ripstech.com/php-security-calendar-2017/){:target="_blank"} (level 6 - Frost pattern) and add it into kurukshetra:

```php
<?php
class TokenStorage {
    public function performAction($action, $data) {
        switch ($action) {
            case 'create':
                $this->createToken($data);
                break;
            case 'delete':
                $this->clearToken($data);
                break;
            default:
                throw new Exception('Unknown action');
        }
    }

    public function createToken($seed) {
        $token = md5($seed);
        file_put_contents('/tmp/tokens/' . $token, '...data');
    }

    public function clearToken($token) {
        $file = preg_replace("/[^a-z.-_]/", "", $token);
        unlink('/tmp/tokens/' . $file);
    }
}
```

Only the class and functions defined within the class is required. Class **`object definitions should not be added`** into the source code.


### Writing unittests

All the defined function should have an appropriate unit test case to validate the functionality of the code. The vulnerable function should have an additional unittests along with the functionality tests to validate if the vulnerabilities has been patched or not. So in short:

* Every function should have atleast 1 unittest to **`validate`** if the function is working correctly or not.
* The vulnerable functions should have additional unittest cases to validate if the function is **`vulnerable or not`**.
 
 Let's write a sample unittest code to using **`phpunit`** to validate the functionality of each of the function along with additional test cases to validate if the vulnerability is patched or not.
 
 {% include note.html content="
 As of now the framework supports only PHP based challenges where unittests should be written using **`phpunit`** (version 7.1.4 used at the time of writing this documentation). Additional language and more unittest framework support is in the roadmap for kurukshetra and will be implemented in the future.
 "%}

When writing unittests, always starts with the following (you can copy paste the below code and start building on it):

```php
<?php
use PHPUnit\Framework\TestCase;
$current_path = realpath(dirname(__FILE__));
require_once($current_path . '/src.php');

```
Here we use phpunit and $current_path has the current path value and it loads the src.php file from the current path or from the same directory in which unittest is present inside the docker. This is automatically taken care by the framework but make sure to always use the filename `src.php` while writing the unittest (or you can copy-paste the above code and start writing). 

{% include important.html content="
**`require_once`** should always try to include the file **`src.php`** and the name should not be changed while writing the unittests. If changed, the unittest will fail to work properly.
"%}

Now lets define the test class and write the test cases for each functions. The name of the test class should be the original class name followed by the string **Test**. For example, the original class name which we are testing here is `TokenStorage` so the test class name should be `TokenStorageTest` (these are the rules of writing unittests using phpunit and not the limitation of the framework itself).
```php
class TokenStorageTest extends TestCase {}
```

Now we can write all the test cases inside this particular class. But before writing the test cases, you can actually set up the testing environment with **`setUp()`** function (which will be run once before every unittest) and **`tearDown()`** to revert all the changes once the unittests are done.

{% include note.html content="
Cleaning up the setup via **`tearDown()`** after the tests is not really required here in most of the cases since the tests are being run inside the container and it will be killed immediately after the completion of the test.
"%}

For example, in the above challenge, we need to have a directory named `/tmp/tokens` for the challenge to work properly so we can write the code to create this directory if it doesnot exist before running the tests.

```php
public function setUp()
    {
        if (!is_dir('/tmp/tokens/')) {
            mkdir('/tmp/tokens/', 0777, true);
        }
    }
    public function tearDown(){ }

```

Now once the setup is ready, we can write the actual test cases to check the functionality of each function. In the above example, we have 3 functions out of which the most important ones are `createToken()` and `clearToken()`. The name of the unittest case function should always start with `test` (these are the rules of writing unittests using phpunit and not the limitation of the framework itself).

```php
public function testcreateToken()
    {
        $connObj = new TokenStorage();
        $string = bin2hex(random_bytes(10));
        $result = $connObj->createToken($string);
        $this->assertFileExists('/tmp/tokens/' . md5($string), 'INFO: File creation failed. createToken() function is not working as expected !');
    }
```
Here we defined a test case where we created a random string which is then passed to the actual function createToken() which is supposed to create a file on `/tmp/tokens/<random_string>`. Once the function execution is complete, we will check if the file is actually created or not using assertions. The 3rd argument to `assertFileExists()` is very important because if the assertion failed, this will be shown to the user. The string must start with **INFO:** followed by the message to be printed on to the user who is trying to solve the challenge.

{% include important.html content="
All the assertions should contain the 3rd argument, a string which starts with the keyword **`INFO: `** followed by the message which will be shown to the user incase the test case failed. <br/><br/>

If none of the test cases returns any INFO messages, framework assumes that all the test cases has been validated and the user has successfully completed the challenge (the message will be returned only if the assertion fails).
"%}

Similarly, we can write unittest case for the function `clearToken()` as well:

```php
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
```

Now this function will first call the createToken() to create a random token and then calls the `clearToken()` to delete the token. So once the `testclearToken()` completes execution, the token should be first created (when we call the createToken()) and then destroyed.

So now we have completed checking the functionality of both the functions so if both test cases succeeds, then we are sure that the functions are working properly. Now lets focus on the vulnerability part. Quoting from the [RIPS Security calendar 2017](https://www.ripstech.com/php-security-calendar-2017/){:target="_blank"} regarding the challenge solution:

{% include callout.html content="This challenge contains a file delete vulnerability. The bug causing this issue is a non-escaped hyphen character (`-`) in the regular expression that is used in the `preg_replace()` call in line 21. If the hyphen is not escaped, it is used as a range indicator, leading to a replacement of any character that is not a-z or an ASCII character in the range between dot (46) and underscore (95). Thus dot and slash can be used for directory traversal and (almost) arbitrary files can be deleted, for example with the query parameters `action=delete&data=../../config.php`." type="primary"%}

So the vulnerability present here is Arbitrary file delete due to lack of validation in the user controlled input named `$data`. So one of the ways in which we can test if the vulnerability is present or not is to create a file outside of the directory `/tmp/token` and try to delete the same file using directory traversal:

```php
public function testdeleteToken()
    {
        $connObj = new TokenStorage();
        $string = md5(bin2hex(random_bytes(10)));
        touch('/tmp/' . $string);
        if(file_exists('/tmp/'. $string))
        {
            $connObj->clearToken('../' . $string);
            $this->assertTrue(file_exists('/tmp/' . $string), 'INFO: Arbitrary files got deleted. clearToken() is deleting random files !');
```

Here we created a file outside of `/tmp/tokens` and we are passed the file path to `unlink()` via clearToken() function argument and if the file gets deleted, the vulnerability is still present.

```php
public function testdeleteToken()
    {
        $connObj = new TokenStorage();
        $string = md5(bin2hex(random_bytes(10)));
        touch('/tmp/' . $string);
        if(file_exists('/tmp/'. $string))
        {
            $connObj->clearToken('../' . $string);
            $this->assertTrue(file_exists('/tmp/' . $string), 'INFO: Arbitrary files got deleted. clearToken() is deleting random files !');
```

So as a whole, here is the final unittest script which can be run against the original source code to check if all the functions are working correctly and see if the function is vulnerable or not.  

```php
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
            $this->assertTrue(file_exists('/tmp/' . $string), 'INFO: Arbitrary files got deleted. clearToken() is deleting random files !');
        }
    }
}
```

This is how we write a valid unittest file which contains all the test cases to check if the challenge has been solved or not. Similarly we can include more challenges by writing valid test cases for the same.
