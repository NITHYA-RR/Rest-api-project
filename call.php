<?php

class Superhero
{
    private $name;

    public function __construct($name)
    {
        $this->name = $name;
    }

    public function __call($method, $args)
    {
        echo "Method called: $method\n";
        var_dump($args);

        $methods = get_class_methods($this);
        var_dump($methods);

        foreach ($methods as $m) {
            if ($m == $method) {
                echo "Calling the private function from __call(): $m\n";
                return $this->$m();
            }
        }

        $dir = __DIR__ . '/apis';
        $files = scandir($dir);

        foreach ($files as $file) {
            if ($file == "." || $file == "..") {
                continue;
            }

            $baseName = basename($file, '.php');
            if ($baseName == $method) {
                include_once($dir . '/' . $file);

                if (isset(${$baseName}) && is_callable(${$baseName})) {
                    $func = Closure::bind(${$baseName}, $this, get_class());
                    return call_user_func_array($func, $args);
                } else {
                    echo "Function not found in api: $baseName\n";
                }
            }
        }
    }

    public function getName()
    {
        return $this->name;
    }
}

$hero = new Superhero("Superman");
echo $hero->getName() . "\n";
echo $hero->getPowers() . "\n";
