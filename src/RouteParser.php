<?php

namespace DogeDev\LaravelAPIDocumenter;

use Illuminate\Routing\Route;
use Illuminate\Http\Request;

class RouteParser
{
    /** @var array */
    protected $errors;
    /** @var array */
    protected $warnings;
    /** @var Route */
    protected $route;
    /** @var null|\ReflectionClass */
    protected $controllerReflection;
    /** @var null|\ReflectionMethod */
    protected $functionReflection;
    /** @var null|\ReflectionClass */
    protected $requestReflection;

    public function __construct(Route $route)
    {
        $this->errors   = [];
        $this->warnings = [];
        $this->route    = $route;

        if (!is_string($route->action['uses'])) {

            $this->error("Closure found");

            $this->controllerReflection = null;
            $this->functionReflection   = null;
            $this->requestReflection    = null;

        } else {

            list($class, $function) = explode('@', $route->action['uses']);

            $this->controllerReflection = $this->getControllerReflection($class);
            $this->functionReflection   = $this->getFunctionReflection($function);
            $this->requestReflection    = $this->getRequestReflection();
        }
    }

    /**
     * Parses the route and gets the object
     *
     * @return object
     */
    public function getObject()
    {
        return (object)[
            'uri'        => $this->route->uri,
            'methods'    => collect($this->route->methods),
            'middleware' => collect($this->route->action['middleware']),
            'controller' => $this->controllerReflection ? (object)[
                'name'     => $this->controllerReflection->name,
                'comment'  => $this->getCommentFromString($this->controllerReflection->getDocComment()),
            ] : null,
            'function' => $this->functionReflection ? (object)[
                'name'    => $this->functionReflection->name,
                'comment' => $this->getCommentFromString($this->functionReflection->getDocComment()),
                'request' => $this->requestReflection ? (object)[
                    'class'      => $this->requestReflection->name,
                    'parameters' => $this->getRules(),
                ] : null,
                'return'  => $this->getReturnParameter(),
            ] : null,
            'errors'     => $this->errors,
            'warnings'   => $this->warnings,
        ];
    }

    /**
     * Gets the Controller ReflectionClass
     *
     * @param string $class
     * @return null|\ReflectionClass
     */
    private function getControllerReflection($class)
    {
        if (!class_exists($class)) {

            $this->error("Controller Class `$class` does not exist");

            return null;
        }

        try {

            return (new \ReflectionClass($class));

        } catch (\Exception $e) {

            $this->error("Failed loading reflection class `$class`");
        }

        return null;
    }

    /**
     * Gets the function ReflectionMethod
     *
     * @param string $function
     * @return null|\ReflectionMethod
     */
    private function getFunctionReflection($function)
    {
        if (empty($this->controllerReflection)) {

            return null;
        }

        if (!method_exists($this->controllerReflection->name, $function)) {

            $this->error("Function `$function` no found in `" . $this->controllerReflection->name . "` class");
        }

        return $this->controllerReflection->getMethod($function);
    }

    /**
     * Gets the custom request ReflectionClass
     *
     * @return null|\ReflectionClass
     */
    private function getRequestReflection()
    {
        if (empty($this->functionReflection)) {

            return null;
        }

        /** @var \ReflectionParameter $parameter */
        foreach ($this->functionReflection->getParameters() as $parameter) {

            /** @var \ReflectionClass $class */
            if ($class = $parameter->getClass()) {

                if (is_subclass_of($class->name, Request::class)) {

                    return $class;
                }
            }
        }

        $this->warn("Custom request not found");

        return null;
    }

    /**
     * Analyzes the comment
     *
     * @param string $string
     * @return object
     */
    private function getCommentFromString($string)
    {
        $string = str_replace('/**', '', $string);
        $string = str_replace('*/', '', $string);

        $tags = [];
        $text = [];

        $rows = explode("\n", $string);

        foreach ($rows as $row) {

            $row = trim($row);

            if (substr($row, 0, 1) === '*') {

                $row = trim(substr($row, 1));
            }

            if (substr($row, 0, 1) === '@') {

                $firstSpace = strpos($row, ' ') ?: strlen($row);

                $tags[] = (object)[
                    'type'  => substr($row, 0, $firstSpace),
                    'value' => substr($row, $firstSpace + 1),
                ];

            } else {

                $text[] = $row;
            }
        }

        return (object)[
            'text'     => trim(implode("\n", $text)),
            'tags'     => collect($tags),
            'original' => "/**" . $string . "*/",
        ];
    }

    /**
     * Gets rules from a custom request
     *
     * @param \ReflectionMethod $method
     * @return array
     */
    private function getRules()
    {
        if (empty($this->requestReflection)) {

            return null;
        }

        try {

            $class = $this->requestReflection->name;

            $rules = $this->requestReflection->getMethod('rules')->invoke(new $class);

            return collect($this->parseRules($rules));

        } catch (\Exception $e) {

            $this->error("Failed invoking $class@rules");
        }

        return collect([]);
    }

    /**
     * Parses rules
     *
     * @param array $rules
     * @return array $result
     */
    private function parseRules(array $rules)
    {
        $result = [];

        foreach ($rules as $attribute => $validation) {

            $validations = [];

            $validation = explode("|", $validation);

            foreach ($validation as $rule) {

                if (strpos($rule, ':')) {

                    list($name, $args) = explode(":", $rule);

                    $args = explode(",", $args);

                } else {

                    $name = $rule;
                    $args = [];
                }

                $validations[] = (object)[
                    'text'        => $rule,
                    'name'        => $name,
                    'args'        => $args,
                    'description' => $this->getRuleDescription($name, $attribute, $args)
                ];
            }

            $result[] = (object)[
                'attribute'   => $attribute,
                'validations' => collect($validations),
            ];
        }

        return $result;
    }

    /**
     * Gets the rule text from language validation
     *
     * @param $name
     * @param $attribute
     * @param array $args
     * @return array|null|string
     */
    private function getRuleDescription($name, $attribute, $args = [])
    {
        // TODO: replace with env
        $text = trans("laravel-api-documenter::validation.$name");

        if (is_array($text)) {

            $text = array_shift($text);
        }

        $text = str_replace(":attribute", $attribute, $text);

        $attributeCount = substr_count($text, " :");

        if ($attributeCount === 0) {

            return $text;
        }

        $words = explode(" ", $text);

        if ($attributeCount === count($args)) {

            foreach ($words as $key => $word) {

                if (substr($word, 0, 1) === ":") {

                    $words[$key] = array_shift($args);
                }
            }

        } else {

            $args = implode(",", $args);

            foreach ($words as $key => $word) {

                if (substr($word, 0, 1) === ":") {

                    $words[$key] = $args;
                }
            }
        }

        return implode(" ", $words);
    }

    /**
     * Attempts to process the comments
     *
     * @return array
     */
    private function getReturnParameter()
    {
        $result = [];

        $comments = $this->getCommentFromString($this->functionReflection->getDocComment());

        foreach ($comments->tags as $tag) {

            if ($tag->type === '@return') {

                foreach (explode("|", $tag->value) as $class) {

                    $clss   = trim($class);
                    $object = null;

                    if (substr($class, -2) === '[]') {

                        $object = $this->mockClass(substr($class, 0, -2), true);

                    } elseif (!in_array($class, ['array', 'string', 'int', 'float', 'bool', 'boolean', 'mixed'])) {

                        $object = $this->mockClass($class);
                    }

                    $result[] = (object)[
                        'type'   => $class,
                        'object' => $object,
                    ];
                }
            }
        }

        return collect($result);
    }

    /**
     * Mocks a class using a seed factory
     *
     * @return mixed|null
     */
    private function mockClass($class, $array = false)
    {
        if (!class_exists($class)) {

            $this->error("Return class `$class` not found");

            return null;
        }

        if (substr($class, 0, 1) === '\\') {

            $class = substr($class, 1);
        }

        try {

            if ($array) {

                return factory($class, 2)->make();
            }

            return factory($class)->make();

        } catch (\Exception $e) {

            $this->warn("Failed mocking class `$class`");
        }

        return null;
    }

    /**
     * Logs and error
     *
     * @param $error
     */
    private function error($error)
    {
        $this->errors[] = $error . " @ [".$this->route->methods[0]." ".$this->route->uri."]";
    }

    /**
     * Logs a warning
     *
     * @param $warning
     */
    private function warn($warning)
    {
        $this->warnings[] = $warning . " @ [".$this->route->methods[0]." ".$this->route->uri."]";
    }
}