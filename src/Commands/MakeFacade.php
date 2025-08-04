<?php

namespace Jengo\Core\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class MakeFacade extends BaseCommand
{
    protected $group = 'Jengo';
    protected $name = 'jengo:facade';
    protected $description = 'Generates a static model facade from an existing model.';

    public function run(array $params)
    {
        $model = $params[0] ?? null;

        if (!$model) {
            CLI::error('Please provide a model class name.');
            return;
        }

        $model = str_replace('/', '\\', $model);
        $modelParts = explode('\\', $model);
        $className = array_pop($modelParts);
        $modelNamespace = implode('\\', $modelParts);

        $facadeNamespace = $modelNamespace; // or customize if needed
        $facadeName = $className . 'Facade';
        $facadePath = APPPATH . 'Facades/' . $facadeName . '.php';

        // Read template
        $templatePath = __DIR__ . '/../Templates/Facade.tpl';
        $template = file_get_contents($templatePath);

        // Replace
        $content = str_replace(
            ['{{ namespace }}', '{{ class }}', '{{ modelNamespace }}', '{{ modelClass }}'],
            [$facadeNamespace, $facadeName, $modelNamespace . '\\' . $className, $className],
            $template
        );

        // Ensure dir
        $targetDir = dirname($facadePath);
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        // Write
        file_put_contents($facadePath, $content);
        CLI::write("✔️  Created Facade: {$facadeName}", 'green');
    }
}
