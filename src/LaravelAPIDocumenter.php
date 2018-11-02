<?php

namespace DogeDev\LaravelAPIDocumenter;

use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route as RouteFacade;

class LaravelAPIDocumenter
{
    protected $middleware;
    protected $prefix;
    protected $descriptions;
    protected $view;
    protected $errors;
    protected $warnings;
    protected $info;

    /**
     * LaravelAPIDocumenter constructor.
     * @param array  $middleware
     * @param array  $prefix
     * @param string $descriptions
     * @param string $view
     */
    public function __construct(
        array $middleware = [],
        array $prefix = [],
        $descriptions = 'laravel-api-documenter::validation',
        $view = 'laravel-api-documenter::index'
    ) {
        $this->errors   = [];
        $this->warnings = [];
        $this->info     = [];
        $this->setMiddleware($middleware)
            ->setPrefix($prefix)
            ->setDescriptions($descriptions)
            ->setView($view);
    }

    /**
     * Middleware setter
     *
     * @param array $middleware
     * @return $this
     */
    public function setMiddleware(array $middleware)
    {
        $this->middleware = $middleware;

        return $this;
    }

    /**
     * Prefix setter
     *
     * @param array $prefix
     * @return $this
     */
    public function setPrefix(array $prefix)
    {
        $this->prefix = $prefix;

        return $this;
    }

    /**
     * Language pack setter
     *
     * @param string $descriptions
     * @return $this
     */
    public function setDescriptions($descriptions)
    {
        $this->descriptions = $descriptions;

        return $this;
    }

    /**
     * Sets the name of the View
     *
     * @param string $view
     * @return $this
     */
    public function setView($view)
    {
        $this->view = $view;

        return $this;
    }

    /**
     * Gets the name of the view
     *
     * @return mixed
     */
    public function getView()
    {
        return $this->view;
    }

    /**
     * Errors getter
     *
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Warnings getter
     *
     * @return array
     */
    public function getWarnings()
    {
        return $this->warnings;
    }

    /**
     * Info getter
     *
     * @return array
     */
    public function getInfo()
    {
        return $this->info;
    }

    /**
     * Gets a collection documented routes
     *
     * @return Collection
     */
    public function getRoutes()
    {
        $this->errors   = [];
        $this->warnings = [];
        $this->info     = [];

        foreach (RouteFacade::getRoutes()->getRoutes() as $route) {

            if (!is_string($route->action['uses'])) {

                $this->error("[ERROR] Closure found on route: " . $route->uri);

                continue;
            }

            $result[] = $this->parseRoute($route);
        }

        $result = collect($result);

        if ($this->middleware) {

            $result = $this->filterOutByMiddleware($result, $this->middleware);
        }

        if ($this->prefix) {

            $result = $this->filterOutByPrefix($result, $this->prefix);
        }

        return $result;
    }

    /**
     * Parses the a Route
     *
     * @param Route $route
     * @return object
     */
    private function parseRoute(Route $route)
    {
        list($class, $function) = explode('@', $route->action['uses']);

        try {

            $method = (new \ReflectionClass($class))->getMethod($function);

            $rules = $this->getRules($method);

            $comments = $this->processComment($method->getDocComment());

        } catch (\Exception $e) {

            $this->error("[ERROR] Method does not exist: $class@$function");

            $rules = $comments = collect([]);
        }

        return (object)[
            'uri'        => $route->uri,
            'methods'    => collect($route->methods),
            'middleware' => collect($route->action['middleware']),
            'class'      => $class,
            'function'   => $function,
            'comment'    => $comments,
            'rules'      => $rules,
        ];
    }

    /**
     * Loads rules from a custom request
     *
     * @param \ReflectionMethod $method
     * @return array
     */
    private function getRules(\ReflectionMethod $method)
    {
        $result = [];

        $found = false;

        foreach ($method->getParameters() as $parameter) {

            if ($parameter->getClass() === null) {

                continue;
            }

            $class = $parameter->getClass()->name;

            if (is_subclass_of($class, Request::class)) {

                $found = true;

                try {

                    $rulesMethod = $parameter->getClass()->getMethod('rules');

                    $rules = $rulesMethod->invoke(new $class);

                    $result = $this->parseRules($rules);

                } catch (\Exception $e) {

                    $this->error("[ERROR] Failed invoking $class@rules");
                }
            }
        }

        if (!$found) {

            $this->warn("[WARN] No custom request found on route " . $method->class . "@" . $method->getName());
        }

        return collect($result);
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
        $text = __("$this->descriptions.$name");

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
     * Analyzes the comment
     *
     * @param string $string
     * @return object
     */
    private function processComment($string)
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
            'original' => $string,
        ];
    }

    /**
     * Filters out the collection by middleware
     *
     * @param Collection $result
     * @param array $middleware
     * @return Collection|static
     */
    private function filterOutByMiddleware(Collection &$result, array $middleware)
    {
        if (empty($middleware)) {

            return $result;
        }

        return $result->filter(function ($item) use ($middleware) {

            return is_array($item->middleware)
                ? !empty(array_intersect($item->middleware, $middleware))
                : in_array($item->middleware, $middleware);
        });
    }

    /**
     * Filters out the collection by prefix
     *
     * @param Collection $result
     * @param array $prefixes
     * @return Collection|static
     */
    private function filterOutByPrefix(Collection &$result, array $prefixes)
    {
        if (empty($prefixes)) {

            return $result;
        }

        return $result->filter(function ($item) use ($prefixes) {

            foreach ($prefixes as $prefix) {

                if (substr($item->uri, 0, strlen($prefix)) === $prefix) {

                    return true;
                }
            }

            return false;
        });
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

    /**
     * Logs an info
     *
     * @param $info
     */
    private function info($info)
    {
        $this->info[] = $info;
    }
}