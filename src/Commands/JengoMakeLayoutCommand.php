<?php

namespace Jengo\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class JengoMakeLayoutCommand extends BaseCommand
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
    protected $name = 'make:layout';

    /**
     * The Command's Description
     *
     * @var string
     */
    protected $description = 'Makes a layout inside the layouts folder in the views directory';

    /**
     * The Command's Usage
     *
     * @var string
     */
    protected $usage = 'make:layout [layout]';

    /**
     * The Command's Arguments
     *
     * @var array
     */
    protected $arguments = [
        'layout' => 'The name of the layout',
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
        $validation = \Config\Services::validation();
        $tries = 3;
        $tried = 0;

        $validation->setRules(config('Jengo\Config\Jengo')->validationRules);

        $layoutName = $params[0];
        /* if (empty($layoutName) || !$validation->run(['file_name' => $layoutName])) {
            $layoutName = CLI::prompt("Provide a valid name for the layout:");

            while (!$validation->run(['file_name' => $layoutName]) && $tried < $tries) {
                $layoutName = CLI::prompt("Provide a valid name for the layout:");
                $tried++;
            }

            if ($tried === $tries) {
                CLI::write("Failed to provide a valid name. Process exit 0", 'red');
                return;
            }
        } */

        $this->makeLayout($layoutName);
    }

    private function makeLayout($layoutName): void
    {
        $baseDir = WRITEPATH . '../app/Views/layouts';

        $content = <<<HTML
        <!DOCTYPE html>
        <html lang="en" x-data="App()">

        <head>
            <?= $this->include('layouts/partials/header_links'); ?>

            <?= $this->renderSection('pageStyles'); ?>
        </head>

        <body>

            <main>
                <?= $this->renderSection('content'); ?>
            </main>

            <?= $this->include('layouts/partials/footer_links'); ?>
            <?= $this->renderSection('pageScripts'); ?>
        </body>

        </html>
        HTML;

        if (!is_dir($baseDir)) {
            mkdir($baseDir, 0777, true);
        }

        $filePath = $baseDir . "/" . $layoutName . ".php";

        if (!file_exists($filePath)) {
            file_put_contents($filePath, $content);
            CLI::write("Layout created successfully at $filePath", 'green');
        } else {
            CLI::write("Layout already exists at $filePath", 'red');
        }
    }
}
