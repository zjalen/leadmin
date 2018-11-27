<?php

namespace Zjalen\Leadmin\Scaffold;

class ControllerCreator
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
     * @param string $model
     * @param array $table
     * @param string $request
     * @throws \Exception
     *
     * @return string
     */
    public function create($model, $table, $request)
    {
        $path = $this->getpath($this->name);

        if ($this->files->exists($path)) {
            throw new \Exception("Controllers [$this->name] already exists!");
        }

        $stub = $this->files->get($this->getStub());

        $this->files->put($path, $this->replace($stub, $this->name, $model, $table, $request));

        return $path;
    }

    /**
     * @param string $stub
     * @param string $name
     * @param string $model
     * @param array $table
     * @param string $request
     *
     * @return string
     */
    protected function replace($stub, $name, $model, $table, $request)
    {
        $stub = $this->replaceClass($stub, $name);
        $headers = '';
        $form_body = '';
        $filters = '';
        foreach ($table as $tb){
            $name = $tb['name'];
            $comment = $tb['comment'];
//            $grids .= "\$grid->column('{$name}','{$comment}');\n";
            $headers .= "                    ['title'=> '{$comment}','name'=> '{$name}', 'width'=> 100],\n";
            $filters .= "                    '{$name}'=> array_key_exists('{$name}', \$filters) ? \$filters['{$name}']: null ,\n";
            $form_body .= "                    '{$name}'=> null,\n";
        }
        return str_replace(
            ['DummyModelNamespace', 'DummyModel', '//DummyHeader', '//DummyFilters', '//DummyFormBody', 'DummyRequestNamespace', 'DummyRequest'],
            [$model, class_basename($model), $headers, $filters, $form_body, $request, class_basename($request)],
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
        return __DIR__.'/stubs/controller.stub';
    }
}
