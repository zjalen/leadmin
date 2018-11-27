<?php

namespace Jalen\Leadmin\Scaffold;

class RequestCreator
{
    /**
     * Controllers full name.
     *
     * @var string
     */
    protected $name;

    /**
     * The filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;

    /**
     * ControllerCreator constructor.
     *
     * @param string $name
     * @param null   $files
     */
    public function __construct($name, $files = null)
    {
        $this->name = $name;

        $this->files = $files ?: app('files');
    }

    /**
     * Create a controller.
     *
     * @param array $table
     *
     * @throws \Exception
     *
     * @return string
     */
    public function create($table)
    {
        $path = $this->getpath($this->name);

        if ($this->files->exists($path)) {
            throw new \Exception("Request [$this->name] already exists!");
        }

        $stub = $this->files->get($this->getStub());

        $this->files->put($path, $this->replace($stub, $this->name, $table));

        return $path;
    }

    /**
     * @param string $stub
     * @param string $name
     * @param array $table
     *
     * @return string
     */
    protected function replace($stub, $name, $table)
    {
        $stub = $this->replaceClass($stub, $name);
        $rules = '';
        $messages = '';
        foreach ($table as $tb){
            $name = $tb['name'];
            $length = $tb['length'];
            if ($length) {
                $rules .= "'{$name}'=> 'max:{$length}',\n";
                $messages = "'{$name}.max' => '字符长度不超过{$length}',\n";
            }
        }
        return str_replace(
            ['//DummyRules', '//DummyMessages'],
            [ $rules, $messages ],
            $stub
        );
    }

    /**
     * Get controller namespace from giving name.
     *
     * @param string $name
     *
     * @return string
     */
    protected function getNamespace($name)
    {
        return trim(implode('\\', array_slice(explode('\\', $name), 0, -1)), '\\');
    }

    /**
     * Replace the class name for the given stub.
     *
     * @param string $stub
     * @param string $name
     *
     * @return string
     */
    protected function replaceClass($stub, $name)
    {
        $class = str_replace($this->getNamespace($name).'\\', '', $name);

        return str_replace(['DummyClass', 'DummyNamespace'], [$class, $this->getNamespace($name)], $stub);
    }

    /**
     * Get file path from giving controller name.
     *
     * @param $name
     *
     * @return string
     */
    public function getPath($name)
    {
        $segments = explode('\\', $name);

        array_shift($segments);

        return app_path(implode('/', $segments)).'.php';
    }

    /**
     * Get stub file path.
     *
     * @return string
     */
    public function getStub()
    {
        return __DIR__.'/stubs/request.stub';
    }
}
