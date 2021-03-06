<?php

namespace DogeDev\LaravelAPIDocumenter;

use Illuminate\Routing\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route as RouteFacade;

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
        $parse = (object)[
            'uri'        => $this->route->uri,
            'name'       => @$this->route->action['as'],
            'methods'    => collect($this->route->methods),
            'middleware' => $this->loadMiddleware(),
            'controller' => $this->controllerReflection ? (object)[
                'name'    => $this->controllerReflection->name,
                'comment' => $this->getCommentFromString($this->controllerReflection->getDocComment()),
            ] : null,
            'function'   => $this->functionReflection ? (object)[
                'name'    => $this->functionReflection->name,
                'comment' => $this->getCommentFromString($this->functionReflection->getDocComment()),
                'request' => $this->requestReflection ? (object)[
                    'class'      => $this->requestReflection->name,
                    'parameters' => $this->getRules(),
                ] : null,
                'return'  => $this->getReturnParameter(),
            ] : null,
            'errors'     => collect($this->errors),
            'warnings'   => collect($this->warnings),
        ];

        return $this->getClassReplacement($parse);
    }

    private function loadMiddleware()
    {
        $middleware = [];

        $names   = collect($this->route->action['middleware'])
            ->merge(collect(RouteFacade::gatherRouteMiddleware($this->route)));

        foreach ($names as $name) {

            if (!\Lang::has("laravel-api-documenter::middleware." . $name)) {

                $this->warn("Middleware `$name` not found in middleware file");

                continue;
            }

            $item = __("laravel-api-documenter::middleware." . $name);

            $middleware[$item['name']] = $item['description'];
        }

        return (object) $middleware;
    }

    /**
     * Gets the example from the examples translation file
     *
     * @param StdClass $object
     * @param bool $array
     *
     * @return array|null|string
     */
    private function getClassReplacement($parse)
    {
        if (!$this->controllerReflection || !$this->functionReflection) {

            return $parse;
        }

        $name = $this->controllerReflection->name . "@" . $this->functionReflection->name;

        if (!\Lang::has("laravel-api-documenter::class-replacements." . $name)) {

            return $parse;
        }

        $replace = $this->arrayToObject(__("laravel-api-documenter::class-replacements." . $name));

        return $this->objectReplace($parse, $replace);
    }

    private function arrayToObject($array)
    {
        if (array_values($array) === $array) {

            $collection = [];

            foreach ($array as $value) {

                if (is_array($value)) {

                    $collection[] = $this->arrayToObject($value);

                } else {

                    $collection[] = $value;
                }
            }

            return collect($collection);
        }

        $object = new \StdClass;

        foreach ($array as $key => $value) {

            if (is_array($value)) {

                $object->{$key} = $this->arrayToObject($value);

            } else {

                $object->{$key} = $value;
            }
        }

        return $object;
    }

    /**
     * Replaces the original object wih values from the second one
     *
     * @param $original
     * @param $replace
     *
     * @return mixed
     */
    private function objectReplace($original, $replace)
    {
        foreach ($replace as $key => $value) {

            if (is_object($value) && get_class($value) !== Collection::class) {

                if (empty($original->{$key})) {

                    $original->{$key} = $replace->{$key};

                } else {

                    $this->objectReplace($original->{$key}, $replace->{$key});
                }

            } else {

                $original->{$key} = $replace->{$key};
            }
        }

        return $original;
    }

    /**
     * Gets the Controller ReflectionClass
     *
     * @param string $class
     *
     * @return null|\ReflectionClass
     */
    private function getControllerReflection($class)
    {
        if (!class_exists($class)) {

            $this->error("Controller `$class` does not exist");

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
     *
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
     *
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
                    'type'  => trim(substr($row, 0, $firstSpace)),
                    'value' => trim(substr($row, $firstSpace + 1)),
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
     *
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
     *
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
                    'description' => $this->getRuleDescription($name, $attribute, $args),
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
     * @param       $name
     * @param       $attribute
     * @param array $args
     *
     * @return array|null|string
     */
    private function getRuleDescription($name, $attribute, $args = [])
    {
        $text = trans(config("laravel-api-documenter.descriptions") . ".$name");

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

                if ($firstSpacePosition = strpos($tag->value, " ")) {

                    $text    = substr($tag->value, $firstSpacePosition);
                    $classes = substr($tag->value, 0, $firstSpacePosition);

                } else {

                    $text    = null;
                    $classes = $tag->value;
                }

                foreach (explode("|", $classes) as $class) {

                    if (substr($class, 0, 1) === '\\') {

                        $class = substr($class, 1);
                    }

                    $object  = null;
                    $example = null;

                    if (substr($class, -2) === '[]') {

                        $object  = $this->mockClass(substr($class, 0, -2), true);
                        $example = $this->getExample(substr($class, 0, -2), true);

                    } elseif (!in_array($class,
                        ['mixed', 'array', 'string', 'float', 'int', 'boolean', 'bool', 'null', 'void'])
                    ) {

                        $object  = $this->mockClass($class);
                        $example = $this->getExample($class);
                    }

                    $result[] = (object)[
                        'type'    => $class,
                        'object'  => $object,
                        'example' => $example,
                        'text'    => $text,
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

            $this->warn("Return class `$class` not found");

            return null;
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
     * Gets the example from the examples translation file
     *
     * @param string $class
     * @param bool $array
     *
     * @return array|null|string
     */
    private function getExample($class, $array = false)
    {
        if (!\Lang::has("laravel-api-documenter::examples." . $class)) {

            $this->warn("Example for `$class` does not exist");

            return null;
        }

        $example = __("laravel-api-documenter::examples." . $class);

        if ($array) {

            return [$example, $example];
        }

        return $example;
    }

    /**
     * Logs and error
     *
     * @param $error
     */
    private function error($error)
    {
        $this->errors[] = $error;
    }

    /**
     * Logs a warning
     *
     * @param $warning
     */
    private function warn($warning)
    {
        $this->warnings[] = $warning;
    }
}