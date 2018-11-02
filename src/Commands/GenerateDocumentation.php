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
    {--middleware= : Filter out the routes by middleware(s) (comma delimited) }
    {--prefix= : Filter out the routes by prefix(es) (comma delimited) }';

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

        $result = $documenter->getRoutes();
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

                    $string .= $rule->attribute . ":" .$validation->text . "\n";
                }
            }

            return [
                implode(" ", $row->methods->all() ?: []) . "\n" . $row->uri,
                $row->class . "@" . $row->function,
                implode("\n", $row->middleware->all() ?: []),
                $string ."\n\n",
            ];
        });

        $this->table(["URI", "Controller", "Middleware", "Validation"], $table, 'compact');
    }
}
