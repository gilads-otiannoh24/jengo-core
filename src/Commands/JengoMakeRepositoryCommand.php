<?php

namespace Jengo\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class JengoMakeRepositoryCommand extends BaseCommand
{
    /**
     * The Command's Group
     *
     * @var string
     */
    protected $group = 'Jengo';

    /**
     * The Command's Name
     *
     * @var string
     */
    protected $name = 'make:repo';

    /**
     * The Command's Description
     *
     * @var string
     */
    protected $description = 'Creates a repository class';

    /**
     * The Command's Usage
     *
     * @var string
     */
    protected $usage = 'make:repo [arguments] [options]';

    /**
     * The Command's Arguments
     *
     * @var array
     */
    protected $arguments = [
        'name' => "The name of the repository",
    ];

    /**
     * The Command's Options
     *
     * @var array
     */
    protected $options = [];

    /**
     * Actually execute a command.
     *
     * @param array $params
     */
    public function run(array $params)
    {
        $name = array_shift($params);
        if (empty($name)) {
            CLI::error('You must provide a Service name.');
            return;
        }

        $serviceName = ucfirst($name);
        $filePath    = APPPATH . "Repositories/{$serviceName}.php";

        if (file_exists($filePath)) {
            CLI::error("The service {$serviceName} already exists.");
            return;
        }

        $template =
            "<?php
namespace App\Repositories;

use stdClass;

class {$serviceName}
{
    public \$model; 
    public function __construct()
    {
        \$this->model = new stdClass();
        // Initialize your service
    }

    public function exampleMethod()
    {
        // Example method
    }
}
        ";
        if (!is_dir(APPPATH . 'Repositories')) {
            mkdir(APPPATH . 'Repositories', 0777, true);
        }

        file_put_contents($filePath, $template);

        CLI::write("Repository created successfully: {$filePath}", 'green');
    }
}
