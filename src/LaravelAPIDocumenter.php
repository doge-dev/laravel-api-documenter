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
    protected $viewTemplate;

    /**
     * LaravelAPIDocumenter constructor.
     */
    public function __construct()
    {
        $this
            ->setMiddleware(config('laravel-api-documenter.middleware'))
            ->setPrefix(config('laravel-api-documenter.prefix'))
            ->setViewTemplate(config('laravel-api-documenter.view-template'));
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
     * Middleware getter
     *
     * @return array
     */
    public function getMiddleware()
    {
        return $this->middleware;
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
     * Prefix getter
     *
     * @return array
     */
    public function getPrefix()
    {
        return $this->prefix;
    }

    /**
     * Sets the View blade template
     *
     * @param string $viewTemplate
     * @return $this
     */
    public function setViewTemplate($viewTemplate)
    {
        $this->viewTemplate = $viewTemplate;

        return $this;
    }

    /**
     * Gets the View blade template
     *
     * @return mixed
     */
    public function getViewTemplate()
    {
        return $this->viewTemplate;
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
        $result = $this->filterOutByMiddleware($result, $this->middleware);
        $result = $this->filterOutByPrefix($result, $this->prefix);

        return $result;
    }

    /**
     * Get the evaluated view contents for the API documentation view.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getView()
    {
        $routes = $this->getRoutes();

        return view($this->getViewTemplate(), ['routes' => $routes]);
    }

    /**
     * Gets the string contents of the View
     *
     * @return string
     * @throws \Throwable
     */
    public function getHTML()
    {
        return $this->getView()->render();
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
     * Filters out the collection by middleware
     *
     * @param Collection $result
     * @param array $middleware
     * @return Collection|static
     */
    private function filterOutByMiddleware(Collection $result, array $middleware)
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
    private function filterOutByPrefix(Collection $result, array $prefixes)
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