<?php

namespace Jengo\Core\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class MakeModelWithFacade extends BaseCommand
{
    protected $group = 'Jengo';
    protected $name = 'jengo:model';
    protected $description = 'Generates a model and an associated static facade.';

    public function run(array $params)
    {
        if (!isset($params[0])) {
            CLI::error('Please provide the model name.');
            return;
        }

        $modelName = $params[0];

        // Call native make:model
        CLI::write("⏳ Generating CI4 model...", 'yellow');
        $this->call("make:model", $params);

        // Call Jengo facade generator
        CLI::write("⚙️  Creating facade for model...", 'yellow');
        $this->call('jengo:make:facade', $params);

        CLI::write("✅ Model + Facade created successfully.", 'green');
    }
}
