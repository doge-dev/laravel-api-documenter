<?php

namespace DogeDev\LaravelAPIDocumenter\Commands;

use DogeDev\LaravelAPIDocumenter\LaravelAPIDocumenter;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

class GenerateDocumentation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'doge-dev:generate-documentation 
    {--show-errors : Outputs Errors }
    {--show-warnings : Outputs Warnings }
    {--middleware= : Filter out the routes by middleware(s) (comma delimited) }
    {--prefix= : Filter out the routes by prefix(es) (comma delimited) }
    {--descriptions= : Language file from which to read the validation descriptions (defaults to laravel-api-documenter::validation) }
    {--view= : View file to use for rendering the HTML (defaults to laravel-api-documenter::index) }
    {--export-path= : Export an HTML to path }
    {--render-table : Render table in the console. }';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates documented routes';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $documenter = new LaravelAPIDocumenter();

        if ($this->option('middleware')) {

            $documenter->setMiddleware(explode(',', $this->option('middleware')));
        }

        if ($this->option('prefix')) {

            $documenter->setPrefix(explode(',', $this->option('prefix')));
        }

        if ($this->option('descriptions')) {

            $documenter->setDescriptions($this->option('descriptions'));
        }

        if ($this->option('view')) {

            $documenter->setView($this->option('view'));
        }

        $results = $documenter->getRoutes();

        if ($this->option('show-warnings')) {

            foreach ($documenter->getWarnings() as $warning) {

                $this->warn($warning);
            }
        }

        if ($this->option('show-errors')) {

            foreach ($documenter->getErrors() as $error) {

                $this->error($error);
            }
        }

        if ($this->option('render-table')) {

            $this->renderTable($results);
        }

        if ($this->option('export-path')) {

            $parameters = ['routes' => $documenter->getRoutes()];

            $html = view($documenter->getView(), $parameters)->render();

            file_put_contents($this->option('export-path'), $html);
        }

        return null;
    }

    /**
     * Renders a console table
     *
     * @param Collection $result
     */
    private function renderTable(Collection $result)
    {
        $table = $result->transform(function ($row) {

            $string = "";

            foreach ($row->rules as $rule) {

                foreach ($rule->validations as $validation) {

                    $string .= $rule->attribute . ":" . $validation->text . "\n";
                }
            }

            return [
                implode(" ", $row->methods->all() ?: []) . "\n" . $row->uri,
                $row->class . "@" . $row->function,
                implode("\n", $row->middleware->all() ?: []),
                $string . "\n\n",
            ];
        });

        $this->table(["URI", "Controller", "Middleware", "Validation"], $table, 'compact');
    }
}
