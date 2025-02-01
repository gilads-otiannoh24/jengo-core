<?php

namespace Jengo\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class JengoGenerateModels extends BaseCommand
{
    protected $group       = 'Jengo';
    protected $name        = 'generate:models';
    protected $description = 'Scan migrations and generate models for tables created by them.';

    public function run(array $params)
    {
        $migrationsPath = APPPATH . 'Database/Migrations/';
        $files = glob($migrationsPath . '*.php');

        if (!$files) {
            CLI::write('No migration files found.', 'yellow');
            return;
        }

        foreach ($files as $file) {
            $content = file_get_contents($file);

            // Check if the migration creates a table
            if (preg_match('/\$this->forge->createTable\(\'([a-zA-Z0-9_]+)\'/', $content, $matches)) {
                $tableName = $matches[1];
                $columns = $this->extractColumns($content);
                $this->generateModel($tableName, $columns);
            }
        }
    }

    private function extractColumns(string $content): array
    {
        $columns = [];
        if (preg_match_all('/\$this->forge->addField\(\[\s*(.*?)\s*\]\);/s', $content, $matches)) {
            $fields = $matches[1][0] ?? '';

            // Extract individual field definitions
            preg_match_all('/\'([a-zA-Z0-9_]+)\'\s*=>\s*\[.*?\]/s', $fields, $fieldMatches);

            $columns = $fieldMatches[1];
        }

        return $columns;
    }

    private function generateModel(string $tableName, array $columns)
    {
        helper('inflector');
        $modelName = ucfirst(camelize($tableName)) . 'Model';
        $modelPath = APPPATH . "Models/{$modelName}.php";

        if (file_exists($modelPath)) {
            CLI::write("Model already exists for table: {$tableName}", 'yellow');
            return;
        }

        // Define the primary key (assuming `id` by default)
        $primaryKey = 'id';
        if (!in_array('id', $columns)) {
            $primaryKey = $columns[0] ?? 'id'; // Use the first column as a fallback
        }

        // Generate allowedFields
        $allowedFields = implode(", ", array_map(fn($field) => "'{$field}'", $columns));

        $template = <<<EOD
<?php

namespace App\Models;

use CodeIgniter\Model;

class {$modelName} extends Model
{
    protected \$table      = '{$tableName}';
    protected \$primaryKey = '{$primaryKey}';
    protected \$useAutoIncrement = true;
    protected \$returnType = 'array';
    protected \$useSoftDeletes = false;

    protected \$allowedFields = [{$allowedFields}];

    // Validation rules
    protected \$validationRules    = [];
    protected \$validationMessages = [];
    protected \$skipValidation     = false;

    // Callbacks
    protected \$beforeInsert = [];
    protected \$afterInsert  = [];
    protected \$beforeUpdate = [];
    protected \$afterUpdate  = [];
    protected \$beforeDelete = [];
    protected \$afterDelete  = [];
}
EOD;

        if (file_put_contents($modelPath, $template)) {
            CLI::write("Model created for table: {$tableName}", 'green');
        } else {
            CLI::write("Failed to create model for table: {$tableName}", 'red');
        }
    }
}
