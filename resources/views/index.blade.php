<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    {{-- Font Awesome --}}
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"
          integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
    {{-- Bootstrap CSS --}}
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
          integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    {{-- jQuery --}}
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"
            integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    {{-- Bootstrap JS --}}
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"
            integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy"
            crossorigin="anonymous"></script>

    <style>
        body {
            position: relative;
            font-size: 0.9rem;
        }

        .dropdown {
            display: none;
        }

        .sidebar {
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            padding: 0;
            z-index: 100; /* Behind the navbar */
            box-shadow: inset -1px 0 0 rgba(0, 0, 0, .1);
        }

        .sidebar-sticky {
            position: relative;
            top: 0;
            height: 100vh;
            padding-top: .5rem;
            overflow-x: hidden;
            overflow-y: auto; /* Scrollable contents if viewport is shorter than content. */
        }

        @supports ((position: -webkit-sticky) or (position: sticky)) {
            .sidebar-sticky {
                position: -webkit-sticky;
                position: sticky;
            }
        }

        .break-word {
            word-break: break-word;
        }

        /* nav links bootstrap override */
        a.nav-link:focus,
        a.nav-link:active,
        a.nav-link:visited {
            /*background-color: #f8f9fa !important;*/
            /*color: #343a40 !important;*/
        }

        a.nav-link.active {
            background-color: #0F75D4 !important;
            color: #fff !important;
        }

        .dropdown {
            background-color: #1E2224 !important;
        }

        .borderless td, .borderless th {
            border: none;
        }

    </style>
</head>
<?php

$routes->transform(function ($route, $key) {

    if (!empty($route->function)) {

        if (!empty($route->function->comment->tags->where('type', '@title')->isNotEmpty())) {

            $route->title = $route->function->comment->tags->where('type', '@title')->first()->value;

            return $route;
        }
    }

    if (!empty($route->name)) {

        $route->title = $route->name;

        return $route;
    }

    $route->title = $route->function->name;

    return $route;
});

// try and group by Class @title attribute
$classes = $routes->groupBy(function ($route) {

    if ($route->controller) {

        if ($route->controller->comment->tags->where('type', '@title')->isNotEmpty()) {

            return $route->controller->comment->tags->where('type', '@title')->first()->value;
        }
    }

    return 'Undefined';
});

// if titles are not there, group by Controller Class name
if ($classes->count() < 2) {

    $classes = $routes->groupBy('controller.name');
}

$i = 0;
$j = 0;

?>
<body data-spy="scroll" data-target="#nav" data-offset="0">
<div class="container-fluid">
    <div class="row">
        {{-- Sidebar --}}
        <div id="nav" class="col-sm-4 col-md-3 bg-dark d-none d-md-block sidebar text-light small">
            <div class="sidebar-sticky">
                <nav class="nav flex-column">
                    <h1 class="px-3">
                        <a href="#route-{{$i}}" class="navbar-brand text-light">
                            <span class="fa fa-home mr-2"></span>
                            {{ config('app.name') }}
                        </a>
                    </h1>
                    @foreach($classes as $class => $routes)
                        <?php $key = snake_case($class) ?>
                        <a
                                class="nav-link nav-dropdown text-light border-bottom border-dark"
                                href="#route-{{$i}}"
                                data-dropdown="#dropdown{{$j}}"
                        ><strong>{{$class}}</strong></a>
                        <nav class="nav flex-column dropdown" id="dropdown{{$j}}">
                            @foreach ($routes as $route)
                                <a
                                        class="nav-link text-white px-4 border-bottom border-dark"
                                        href="#route-{{$i}}"
                                >{{ $route->title }}</a>
                                <?php $i++ ?>
                            @endforeach
                            <?php $j++ ?>
                        </nav>
                    @endforeach
                    <div class="py-4 px-4 border-top border-dark">
                        <p>
                            Built with <a href="https://github.com/doge-dev/laravel-api-documenter" target="_blank">Laravel API Documenter</a>
                        </p>
                    </div>
                </nav>
            </div>
        </div>
        {{-- !Sidebar --}}
        {{-- Main --}}
        <main role="main" class="col-sm-8 col-md-9 ml-sm-auto">
            <div class="row">
                <div class="col-md-12 border-bottom">
                    <h1 class="py-4">{{ config('app.name') }} API</h1>
                </div>
            </div>
            <?php
            $i = 0;
            $j = 0;
            ?>
            @foreach($classes as $class => $routes)

                <div class="row">
                    <h2 id="route-{{$i}}" class="py-2 px-2">{{$class}}</h2>
                </div>

                @foreach($routes as $route)
                    <div class="row border-top border-bottom">
                        <div class="col-md-12">
                            <h3 class="py-2 px-2" id="route-{{$i}}">
                                {{ $route->title}} <br>
                            </h3>
                            <h4 class="py-2 px-2">
                                <span class="badge badge-secondary">{{$route->methods->first()}}</span>
                                <code class="">{{$route->uri}}</code>
                            </h4>
                        </div>
                    </div>

                    @if ($route->function)

                        <div class="py-4">
                            <h5>Description</h5>
                            <p>{{ $route->function->comment->text }}</p>
                        </div>


                        @if ($route->middleware)
                            <div class="py-4">
                                <h5>Constraints</h5>
                                @foreach ($route->middleware as $name => $description)
                                    <p>{{$description}}</p>
                                @endforeach
                            </div>
                        @endif

                        @if ($route->function->request)
                            <div class="py-4">
                                <h5>Function parameters:</h5>
                                @if ($route->function->request->parameters && $route->function->request->parameters->count() > 0)
                                    <table class="table table-striped table-bordered table-hover">
                                        <thead>
                                        <tr>
                                            <th scope="col">Parameter</th>
                                            <th scope="col">Constraints</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($route->function->request->parameters as $parameter)
                                            <tr>
                                                <td><b>{{$parameter->attribute}}</b></td>
                                                <td>
                                                    @foreach($parameter->validations as $validation)
                                                        <span class="break-word">{{ $validation->description }}</span>
                                                    @endforeach
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    <p>No parameters required</p>
                                @endif
                            </div>
                        @endif

                        @if($route->function->return)
                            <div class="py-4">
                                <h5>Returns:</h5>
                                @foreach ($route->function->return as $return)
                                <div class="card">
                                    <div class="card-header">
                                        <code class="">{{ $return->type }}</code> {{ substr($return->text, strpos($return->text, ' ')) }}
                                    </div>
                                    <div class="card-body">
                                            <p>
                                            @if ($return->object !== null)
                                                <pre class="">{{json_encode($return->object, JSON_PRETTY_PRINT)}}</pre>
                                            @elseif ( $return->example !== null )
                                                @if (is_array($return->example))
                                                    <pre class="">{{ json_encode($return->example, JSON_PRETTY_PRINT ) }}</pre>
                                                @else
                                                    <pre class="">{{ $return->example }}</pre>
                                                @endif
                                            @endif
                                            </p>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @endif

                    @endif

                    @if (env('APP_DEBUG'))

                        <div class="py-4">
                            @if (!empty($showErrors) && $route->errors->count() > 0)
                                @foreach ($route->errors as $error)
                                    <p class="alert alert-danger" role="alert">
                                        {{ $error }}
                                    </p>
                                @endforeach
                            @endif

                            @if (!empty($showErrors) && $route->warnings->count() > 0)
                                @foreach ($route->warnings as $warning)
                                    <p class="alert alert-warning" role="alert">
                                        {{ $warning }}
                                    </p>
                                @endforeach
                            @endif
                        </div>

                    @endif
                    <?php $i++ ?>
                @endforeach
                <?php $j++ ?>
            @endforeach

        </main>
        {{-- !Main --}}
    </div>
</div>

<script>

    (function () {

        // Function for self opening/closing tabs in the navigation
        $(window).on('activate.bs.scrollspy', function (e) {

            // get the dropdown that should be opened (has active class from bootstrap scrollspy)
            let activeDropdown = $('.nav-dropdown.active').data('dropdown');
            // open the dropdown only if not already visible
            if (!$(activeDropdown).is(':visible')) {
                $(activeDropdown).slideDown();
            }

            // get all other dropdowns
            let nonActiveDropdowns = $('.nav-dropdown').not('.active');

            // if any of then is opened, close it
            for (let i = 0; i < nonActiveDropdowns.length; i++) {
                let nonActiveDropdown = $(nonActiveDropdowns[i]).data('dropdown');
                if ($(nonActiveDropdown).is(':visible')) {
                    $(nonActiveDropdown).slideUp();
                }
            }
        });

        $('a[data-dropdown]').click(function (e) {
            e.preventDefault();
            let dropdown = $(this).data('dropdown');
            $(dropdown).slideToggle();
        });

        let $root = $('html, body');

        $('a[href^="#"]').click(function (e) {
            let dropdown = $(this).data('dropdown');
            if (!dropdown) {
                let href = $(this).attr('href');
                $root.animate({
                    scrollTop: $(href).offset().top,
                }, 500, function () {
                    window.location.hash = href;
                });
            }
        });

    })();
</script>

</body>
</html>