<?php

namespace AppTest;

class RouterTest extends \PHPUnit_Framework_TestCase
{
    private $middlewaresDir = __DIR__ . '/../../src/App/Middlewares/';
    private $mockUpFile;

    public function testResolver()
    {
        $mock = $this->mockMiddleware();

        $resolver = \App\Router::resolver('/' . $mock);
        $this->clean();

        $this->assertTrue(is_array($resolver));
        $this->assertTrue(array_key_exists('name', $resolver));
        $this->assertTrue(array_key_exists('path', $resolver));
        $this->assertTrue(array_key_exists('middleware', $resolver));
        $this->assertTrue(array_key_exists('allowed_methods', $resolver));
        $this->assertEquals($mock, $resolver['name']);
        $this->assertEquals('/' . $mock, $resolver['path']);
        $this->assertTrue(is_a($resolver['middleware'], "App\\Middlewares\\{$mock}"));

    }

    private function mockMiddleware()
    {
        while (true) {
            $mockUpName = 'MX' . hash('sha256', mt_rand() . time());

            if (!file_exists($this->middlewaresDir . $mockUpName . '.php')) {
                $this->mockUpFile = $this->middlewaresDir . $mockUpName . '.php';

                $fileContents = "<?php\nnamespace App\\Middlewares;\n\n";
                $fileContents .= "class {$mockUpName}\n{\n";
                $fileContents .= "    public function getAllowedMethods() { return ['OPTIONS']; }\n}";
                file_put_contents($this->mockUpFile, $fileContents);

                break;
            }
        }

        return $mockUpName;
    }

    private function clean()
    {
        if (file_exists($this->mockUpFile)) {
            unlink($this->mockUpFile);
        }
    }
}
