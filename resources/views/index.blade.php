<html>
<head>
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js"></script>
    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
</head>
<body>
<style>
    #wrapper {
        padding-left: 250px;
        transition: all 0.4s ease 0s;
    }

    #page-content-wrapper {
        padding: 20px;
    }

    #sidebar-wrapper {
        margin-left: -250px;
        left: 250px;
        width: 250px;
        background: #000;
        position: fixed;
        height: 100%;
        overflow-y: auto;
        z-index: 1000;
        transition: all 0.4s ease 0s;
    }

    #wrapper.active {
        padding-left: 0;
    }

    #wrapper.active #sidebar-wrapper {
        left: 0;
    }

    #page-content-wrapper {
        width: 100%;
    }

    .sidebar-nav {
        position: absolute;
        top: 0;
        width: 250px;
        list-style: none;
        margin: 0;
        padding: 0;
    }

    .sidebar-nav li {
        line-height: 40px;
        text-indent: 20px;
    }

    .sidebar-nav li a {
        color: #999999;
        display: block;
        text-decoration: none;
        padding: 0;
    }

    .sidebar-nav li a span:before {
        position: absolute;
        left: 0;
        color: #41484c;
        text-align: center;
        width: 20px;
        line-height: 18px;
    }

    .sidebar-nav li a:hover,
    .sidebar-nav li.active {
        color: #fff;
        background: rgba(255, 255, 255, 0.2);
        text-decoration: none;
    }

    .sidebar-nav li a:active,
    .sidebar-nav li a:focus {
        text-decoration: none;
    }

    .sidebar-nav > .sidebar-brand {
        height: 65px;
        line-height: 60px;
        font-size: 18px;
    }

    .sidebar-nav > .sidebar-brand a {
        color: #999999;
    }

    .sidebar-nav > .sidebar-brand a:hover {
        color: #fff;
        background: none;
    }

    .content-header {
        height: 65px;
        line-height: 65px;
    }

    .content-header h1 {
        margin: 0;
        margin-left: 20px;
        line-height: 65px;
        display: inline-block;
    }

    #menu-toggle {
        text-decoration: none;
    }

    .btn-menu {
        color: #000;
    }

    .inset {
        padding: 20px;
    }

    @media (max-width: 767px) {

        #wrapper {
            padding-left: 0;
        }

        #sidebar-wrapper {
            left: 0;
        }

        #wrapper.active {
            position: relative;
            left: 250px;
        }

        #wrapper.active #sidebar-wrapper {
            left: 250px;
            width: 250px;
            transition: all 0.4s ease 0s;
        }

        #menu-toggle {
            display: inline-block;
        }

        .inset {
            padding: 15px;
        }

    }

</style>

<div id="wrapper">

    <!-- Sidebar -->
    <div id="sidebar-wrapper">
        <nav id="spy">
            <ul class="sidebar-nav nav">
                <li class="sidebar-brand">
                    <a href="#home"><span class="fa fa-home solo">Home</span></a>
                </li>
                @foreach($routes as $key => $route)
                    <li>
                        <a href="#route{{$key}}" data-scroll>
                            <span class="fa fa-anchor solo">{{ $route->methods->first() }} {{$route->uri}}</span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </nav>
    </div>

    <!-- Page content -->
    <div id="page-content-wrapper">
        <div class="content-header">
            <h1 id="home">
                <a id="menu-toggle" href="#" class="glyphicon glyphicon-align-justify btn-menu toggle">
                    <i class="fa fa-bars"></i>
                </a>
                {{ env('APP_NAME') }} API
            </h1>
        </div>

        <div class="page-content inset" data-spy="scroll" data-target="#spy">

            <div class="row">
                @foreach($routes as $key => $route)
                    <div class="col-md-12 well">
                        <h2 id="route{{$key}}">{{ $route->methods->first() }} {{$route->uri}}</h2>

                        @if ($route->controller)
                            <p><b>{{  $route->controller->name }}</b></p>
                        @endif

                        @if ($route->function)

                            <p>{{ $route->function->name }}</p>
                            <p>{{ $route->function->comment->text }}</p>

                            @if ($route->function->request)
                                <section>
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
                                                            <div>{{ $validation->description }}</div>
                                                        @endforeach
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    @else
                                        <p>No parameters required</p>
                                    @endif
                                </section>
                            @endif

                            @if($route->function->return)
                                <section>
                                    <h3>Returns</h3>
                                    @foreach ($route->function->return as $return)

                                        <div>
                                            <code><b>{{ $return->type }}</b></code> {{ substr($return->text, strpos($return->text, ' ')) }}
                                            @if ($return->object !== null)
                                                <pre><code class="bg-white">{{json_encode($return->object, JSON_PRETTY_PRINT)}}</code></pre>
                                            @elseif ( $return->example !== null )
                                                @if (is_array($return->example))
                                                    <pre><code class="bg-white">{{ json_encode($return->example, JSON_PRETTY_PRINT ) }}</code></pre>
                                                @else
                                                    <pre><code class="bg-white">{{ $return->example }}</code></pre>
                                                @endif
                                            @endif
                                        </div>

                                    @endforeach
                                </section>
                            @endif

                            @if ($route->middleware)

                                <h3>Middleware Names</h3>
                                @foreach ($route->middleware->names as $name)
                                    <div><a href="#">{{ $name }}</a></div>
                                @endforeach

                                <h3>Middleware Classes</h3>
                                @foreach ($route->middleware->classes as $class)
                                    <div><a href="#">{{ $class }}</a></div>
                                @endforeach
                            @endif

                        @endif

                        @if (env('APP_DEBUG'))

                            @if ($route->errors->count() > 0)
                                <section>
                                    <h3 class="text-danger">Errors</h3>

                                    @foreach ($route->errors as $error)
                                        <div class="alert alert-danger" role="alert">
                                            {{ $error }}
                                        </div>
                                    @endforeach
                                </section>
                            @endif

                            @if ($route->warnings->count() > 0)
                                <section>
                                    <h3 class="text-warning">Warnings</h3>

                                    @foreach ($route->warnings as $warning)
                                        <div class="alert alert-warning" role="alert">
                                            {{ $warning }}
                                        </div>
                                    @endforeach
                                </section>
                            @endif

                        @endif
                    </div>
                @endforeach
            </div>

            <div class="navbar navbar-default navbar-static-bottom">
                <p class="navbar-text pull-left">
                    Built with <a href="https://github.com/doge-dev/laravel-api-documenter" target="_blank">Laravel API
                        Documenter</a>
                </p>
            </div>
        </div>

    </div>

    <script>

        /*Menu-toggle*/
        $("#menu-toggle").click(function (e) {
            e.preventDefault();
            $("#wrapper").toggleClass("active");
        });

        /*Scroll Spy*/
        $('body').scrollspy({target: '#spy', offset: 80});

        /*Smooth link animation*/
        $('a[href*=#]:not([href=#])').click(function () {
            if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') || location.hostname == this.hostname) {

                var target = $(this.hash);
                target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
                if (target.length) {
                    $('html,body').animate({
                        scrollTop: target.offset().top
                    }, 1000);
                    return false;
                }
            }
        });
    </script>

</div>
</body>
</html>