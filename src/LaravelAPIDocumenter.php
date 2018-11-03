<?php

namespace DogeDev\LaravelAPIDocumenter;

use App\Http\Controllers\PostController;
use Illuminate\Routing\Route;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route as RouteFacade;

class LaravelAPIDocumenter
{
    protected $middleware;
    protected $prefix;
    protected $descriptions;
    protected $view;

    /**
     * LaravelAPIDocumenter constructor.
     * @param array $middleware
     * @param array $prefix
     * @param string $descriptions
     * @param string $view
     */
    public function __construct(
        array $middleware = [],
        array $prefix = [],
        $descriptions = 'laravel-api-documenter::validation',
        $view = 'laravel-api-documenter::index'
    ) {
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
     * Gets a collection documented routes
     *
     * @return Collection
     */
    public function getRoutes()
    {
        $result = [];

        foreach (RouteFacade::getRoutes()->getRoutes() as $route) {

            $result[] = (new RouteParser($route))->getObject();
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
     * Gets the string contents of the View
     *
     * @return string
     * @throws \Throwable
     */
    public function getHTML()
    {
        $routes = $this->getRoutes();

        return $this->renderView()->render();
    }

    /**
     * Saves the API documentation to a file
     *
     * @param string $path
     * @throws \Throwable
     */
    public function saveHTMLToFile($path)
    {
        file_put_contents($path, $this->getHTML());
    }

    /**
     * Get the evaluated view contents for the API documentation view.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function renderView()
    {
        $routes = $this->getRoutes();

        return view($this->getView(), ['routes' => $routes]);
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
}