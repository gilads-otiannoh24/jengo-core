<?php

namespace Jengo\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class JengoMakeServiceCommand extends BaseCommand
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
    protected $name = 'make:service';

    /**
     * The Command's Description
     *
     * @var string
     */
    protected $description = 'Creates a service class';

    /**
     * The Command's Usage
     *
     * @var string
     */
    protected $usage = 'make:service [arguments] [options]';

    /**
     * The Command's Arguments
     *
     * @var array
     */
    protected $arguments = [
        'name' => "The name of the service",
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
        $filePath    = APPPATH . "Services/{$serviceName}.php";

        if (file_exists($filePath)) {
            CLI::error("The service {$serviceName} already exists.");
            return;
        }

        $template =
            "<?php
namespace App\Services;

class {$serviceName}
{
    public function __construct()
    {
        // Initialize your service
    }

    public function exampleMethod()
    {
        // Example method
    }
}
        ";
        if (!is_dir(APPPATH . 'Services')) {
            mkdir(APPPATH . 'Services', 0777, true);
        }

        file_put_contents($filePath, $template);

        CLI::write("Service created successfully: {$filePath}", 'green');
    }
}
