# Proper Lad (Laravel API Documenter)

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]
[![Build Status][ico-travis]][link-travis]
[![StyleCI][ico-styleci]][link-styleci]

Proper Lad (Proper Laravel API Documenter) provides an easy way for generating an API documentation. 

If your code is properly coded, you will love the Proper LAD.

Proper LAD generates a documentation in a very smart way (so you don't have to):
 * **loads all routes** added by Router (in your `web.php` and `api.php` files... see [Laravel routing](https://laravel.com/docs/5.7/routing))
 * **filters out the routes** you want to document; by middleware (ie `api` or `web`) or by prefix (ie `api/1.0` or `oauth`) ... or both
 * **loads the Controller Class** for the route, along with its [PHP DocBlock](https://en.wikipedia.org/wiki/PHPDoc), loading any [@tags](https://en.wikipedia.org/wiki/PHPDoc#Tags) defined 
 * **loads the (Controller) function** for the route, along with its [PHP DocBlock](https://en.wikipedia.org/wiki/PHPDoc), loading any [@tags](https://en.wikipedia.org/wiki/PHPDoc#Tags) defined
 * **loads the [custom request](https://laravel.com/docs/5.7/validation#creating-form-requests)** passed to the function call
 * **loads the [validation rules](https://laravel.com/docs/5.7/validation#form-request-validation)** used in the Custom Request
 * **loads the response @return type** found in the [PHP DocBlock](https://en.wikipedia.org/wiki/PHPDoc)
 * **mocks a response using [Laravel's Model Factories](https://laravel.com/docs/5.7/seeding#using-model-factories)** or loads it from predefined examples
 * **loads the middleware names** assigned to the route for explaining route limitations/restrictions/whatevz
 * **and renders a neat documentation template** + much more if you want to customize your documenting paradigm.

Enjoy!

Contributions to this repo are welcome and will be fully credited. For more info please check [contributing.md](contributing.md) to see a to do list.


## Table of Contents

- [Installation](#installation)
- [Configuration](#configuration)
- [Usage](#usage)
  - [Using a Route](#using-a-route)
  - [Using Artisan](#using-artisan)
  - [Using Proper LAD](#using-proper-lad)
  - [Filtering out routes](#filtering-out-routes)
  - [Grouping routes](#grouping-routes)
- [Customization](#customization)
  - [Creating custom validation descriptions](#creating-custom-validation-descriptions)
  - [Creating hardcoded response examples](#creating-hardcoded-response-examples)
  - [Replacing parsed data](#replacing-parsed-data)
  - [Choosing a custom view template](#choosing-a-custom-view-template)
- [Route object](#route-object)
- [Change log](#change-log)
- [Testing](#testing)
- [Contributing](#contributing)
- [Security](#security)
- [Credits](#credits)
- [License](#license)

## Installation

To install via Composer run:

``` bash
composer require doge-dev/laravel-api-documenter
```

After the package has been added, run `vnedor:publish` if you want to publish the config files, texts, and views:

``` bash
php artisan vendor:publish
```

and select `doge-dev/laravel-api-documenter` from the dropdown list.


## Configuration

After running `php artisan vendor:publish` a config file called `laravel-api-documenter.php` will be created in your config directory.

There are a couple of things that can be set here so that you don't need to set them pragmatically:
 * **view-template** determines the default template to be used for [rendering the HTML](https://laravel.com/docs/5.7/blade)
 * **descriptions** determines the default translation file to be used for describing validation rules found in [Custom Requests](https://laravel.com/docs/5.7/validation#creating-form-requests)
 * **examples** determines the default translation file to be used for storing **@return** examples  
 * **prefix** lists the default prefixes to be used for filtering out the routes
 * **middleware** lists the default middlewares to be used for filtering out the routes

## Usage

There are a couple of ways that you can generate the documentation from your code.

The Proper LAD is highly customizable and adjustable to your preferred documenting paradigm. These features will be discussed in the [Customization](#customization) section. 

#### Using a Route

The easiest way of generating the documentation is by simply adding a route and rendering the blade template:

```php
Route::get('api-documentation', function () {

    $documenter = new \DogeDev\LaravelAPIDocumenter\LaravelAPIDocumenter();

    return $documenter->getView();
});
```

#### Using Artisan

You can generate an HTML file to a route of your choice through an `artisan` command:

```bash
php artisan doge-dev:generate-documentation
```

**NOTE:** If you run the command with `--help` you will get a list of all options for running the console command.

#### Using Proper LAD

There are a couple of functions that Proper LAD `DogeDev\LaravelAPIDocumenter\LaravelAPIDocumenter` uses for this, which you can utilize:

```php
getRoutes();            // Gets a collection of documented routes 
getView();              // Get the evaluated view contents for the API documentation view 
getHTML();              // Gets the string contents of the View
saveHTMLToFile($path);  // Saves the API documentation to a file
``` 

#### Filtering out routes 

You can choose to document only certain routes.

You can filter out only `api` routes using a middleware filter ie:

```php

$documenter->setMiddleware(['api', 'some-other-middleware-name'])->getRoutes()

``` 

Or you can filter out the routes using prefixes:

```php

$documenter->setPrefix(['oauth', 'api/1.0'])->getRoutes()

```

#### Grouping routes

Proper LAD uses [Collections](https://laravel.com/docs/5.7/collections), so sorting grouping and transforming the routes should be fairly straightforward. 

For instance, you can group the routes using the **@title** tag in the Controller DocBlock:

```php

$routes = $documenter->getRoutes()->groupBy(function($route) {

    if ($route->controller) {

        if ($route->controller->comment->tags->where('type', '@title')->isNotEmpty()) {

            return $route->controller->comment->tags->where('type', '@title')->first()->value;
        }
    }

    return 'Undefined';
});

```

Or you can simply sort them by the Controller name:

```php

$routes = $documenter->getRoutes()->groupBy('controller.name');

```

## Customization

#### Creating custom validation descriptions

All of the validation rules are stored in the LAD's `descriptions` translation file.

LAD uses pretty much the same strings as Laravel for describing the validation constraints, however, they are adjusted a bit to fit the purpose (documenting requirements rather than reporting validation errors).

If you have custom validation rules, you should add a description string to `descriptions.php` translation file located in the resources vendor folder. ie:

```php
'same' => 'The :attribute and :other must match.'
``` 

The `:attribute` parameter will be replaced with the name of the attribute being validated.

If there are more `:whatever` tokens in the string, they will be replaced with the validation parameters.

#### Creating hardcoded response examples

By default, Proper LAD parses the **@response** tag of the Controllers function and tries mocking the response object using [Laravel's Model Factories](https://laravel.com/docs/5.7/seeding#using-model-factories).

However, you can also choose to write the response as plain text. This can be achieved by adding examples to the `examples.php` translation file (located in the resources/vendors folder). 

```php
\App\Some\Class::class => [
        "objectData" => [
            "foo" => "bar"
        ],
        "arrayData" => [1,2,3,4],
        "number" => 1,
        "boolean" => true,
    ],
```

So, whenever a `\App\Some\Class` class is found in the response, the listed example will be used.

#### Replacing parsed data

Quite often you might need to replace the whole Route object with custom data - like when documenting Passports authentication endpoints. This can be easily achieved by placing an item in the `class-replacements.php` translation file (located in the resources -> vendor folder).

The Proper LAD will parse the route and overwrite it with data found in the `class-replacements` file. However, it will keep any unmodified attributes.

```php
[
    'Laravel\Passport\Http\Controllers\AuthorizationController@authorize' => [
        'function' => [
            'return' => [
                'example' => [
                    'token'      => 'some-realy-long-token',
                    'expires-in' => 3423452345423,
                ],
            ],
        ],
    ],
]
```

By adding this line you would effectively replace the example attribute for this controller's parsed data, the rest of the data will stay untouched.

#### Choosing a custom view template

By default, Prope LAD uses the default blade template shipped with LAD.

The template handles a lot of cases, where there are is data missing, and we strongly encourage exploring the template and exploring the objects that LAD is using for rending the templates. 

By getting familiar with the data you can easily develop your own documentation paradigm and write a template for rendering it quite easily.

You can chose to use a different blade template for rendering the HTML:

```php

$documenter->setViewTemplate('pages.index')->getView()

```

Or if you are implementing this on a single page app, you can simply just return the route objects via AJAX:

```php

$documenter->getRoutes();

```

## Route object

This is what a single Proper LAD's route object looks like:

```php

{#4628 ▼
  +"uri": "api/1.1/logout"         // API route
  +"name": null                    // Named route [https://laravel.com/docs/5.7/routing#named-routes]
  +"methods": Collection {#4615 ▼  // List of accepted HTTP methods
    #items: array:1 [▼
      0 => "POST"
    ]
  }
  +"middleware": {#4616 ▼            // List of Middleware used
    +"names": Collection {#4612 ▼    // Names of middleware groups
      #items: array:3 [▼
        0 => "api"
        1 => "least_supported_api_version"
        2 => "auth:api"
      ]
    }
    +"classes": Collection {#4618 ▼  // List of middleware classes used
      #items: array:5 [▼
        0 => "Illuminate\Routing\Middleware\ThrottleRequests:60,1"
        1 => "Illuminate\Auth\Middleware\Authenticate:api"
        2 => "Illuminate\Routing\Middleware\SubstituteBindings"
        3 => "App\Http\Middleware\LogsAPIIP"
        4 => "App\Http\Middleware\LeastSupportedApiVersion"
      ]
    }
  }
  +"controller": {#4623 ▼                                 // The Controller object
    +"name": "App\Http\Controllers\Auth\LoginController"  // Name of the Controller 
    +"comment": {#4622 ▼                                  // The Docblock comment object
      +"text": "Class LoginController"                    // ... text of the comment
      +"tags": Collection {#4621 ▼                        // ... tags found in the docblock
        #items: array:2 [▼
          0 => {#4620 ▼
            +"type": "@package"                             // ... type of the tag
            +"value": "App\Http\Controllers\Auth"           // ... value for the tag
          }
          1 => {#4614 ▼
            +"type": "@title"                               // ... type of the tag
            +"value": "Login"                               // ... value for the tag
          }
        ]
      }
      +"original": """                                    // ... original docblock that was parsed 
        /**\n
         * Class LoginController\n
         *\n
         * @package App\Http\Controllers\Auth\n
         * @title Login\n
         */
        """
    }
  }
  +"function": {#4629 ▼                     // The Controllers function object
    +"name": "apiLogout"                    // Name of the function
    +"comment": {#4627 ▼                    // The PHP Docblock Comment object
      +"text": "API Logout function"
      +"tags": Collection {#4626 ▼
        #items: array:2 [▼
          0 => {#4624 ▼
            +"type": "@param"
            +"value": "Request $request"
          }
          1 => {#4625 ▼
            +"type": "@return"
            +"value": "boolean"
          }
        ]
      }
      +"original": """
        /**\n
             * API Logout function\n
             *\n
             * @param Request $request\n
             *\n
             * @return boolean\n
             */
        """
    }
    +"request": null                        // Custom Request object used in the Controlller function
    +"return": Collection {#4633 ▼          // Return value
      #items: array:1 [▼
        0 => {#4634 ▼
          +"type": "boolean"                // ... return type
          +"object": null                   // ... return Object
          +"example": null                  // ... return example
          +"text": null                     // ... reutrn text
        }
      ]
    }
  }
  +"errors": Collection {#4631 ▼            // errors found on route
    #items: []
  }
  +"warnings": Collection {#4630 ▼          // warnings found on route
    #items: array:1 [▼
      0 => "Custom request not found"
    ]
  }
}

```

## Change log

Please see the [changelog](changelog.md) for more information on what has changed recently.

## Testing

No tests dare done so far. We encourage you to contribute by writing tests :)

## Contributing

Please see [contributing.md](contributing.md) for details and a todo list.

## Security

If you discover any security-related issues, please email marko@dogedev.com instead of using the issue tracker.

## Credits

- [Marko Maletic][link-author]
- [All Contributors][link-contributors]

## License

MIT. Please see the [license file](license.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/dogedev/laravelapidocumenter.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/dogedev/laravelapidocumenter.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/dogedev/laravelapidocumenter/master.svg?style=flat-square
[ico-styleci]: https://styleci.io/repos/12345678/shield

[link-packagist]: https://packagist.org/packages/dogedev/laravelapidocumenter
[link-downloads]: https://packagist.org/packages/dogedev/laravelapidocumenter
[link-travis]: https://travis-ci.org/dogedev/laravelapidocumenter
[link-styleci]: https://styleci.io/repos/12345678
[link-author]: https://github.com/dogedev
[link-contributors]: ../../contributors]