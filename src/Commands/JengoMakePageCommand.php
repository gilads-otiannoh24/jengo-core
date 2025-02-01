<?php

namespace Jengo\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class JengoMakePageCommand extends BaseCommand
{

    protected $group       = 'Jengo';
    protected $name        = 'make:page';
    protected $description = 'Creates a new page in the Views/pages directory and optionally a layout in the Views/layouts directory.';

    protected $usage = 'make:page [pagename] [layout]';
    protected $arguments = [
        'pagename' => 'The name of the page to create. Can include subdirectories like admin/pagename.',
        'layout'   => '(Optional) The name of the layout to use. If it does not exist, it will be created.'
    ];

    public function run(array $params)
    {
        $validation = \Config\Services::validation();
        $pagename = $params[0] ?? null;
        $layout   = $params[1] ?? null;

        $validation->setRules(config('Jengo\Config\Jengo')->validationRules['file_name']);

        $tries = 3;
        $tried = 0;

        /* if (empty($pagename) || !$validation->run(['file_name' => $pagename])) {
            $pagename = CLI::prompt("Provide a valid name for the page:");

            while (!$validation->run(['file_name' => $pagename]) && $tried < $tries) {
                $pagename = CLI::prompt("Provide a valid name for the page");
                $tried++;
            }

            if ($tried === $tries) {
                CLI::error("Failed to provide a valid name. Process exit 0");
                return;
            }
        } */

        $this->makePage($pagename, $layout);
    }

    public function makePage(string $pagename, ?string $layout): void
    {
        $pagePath = WRITEPATH . '../app/Views/pages/' . $pagename . '.php';
        $config = config('Jengo\Config\Jengo');

        // Ensure directories exist
        $directory = dirname($pagePath);
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        // Create the page file
        if (!file_exists($pagePath)) {
            $pageContent = $layout ? <<<HTML
            <?php \$this->extend("layouts/$layout"); ?>

            <?php \$this->section('pageStyles'); ?>
            <style>
                /* Styles go here */
            </style>
            <?php \$this->endSection(); ?>


            <?php \$this->section('content'); ?>

            <?php \$this->endSection(); ?>


            <?php \$this->section('pageScripts'); ?>
            <script>
                document.addEventListener("alpine:init", () => {
                    Alpine.data("$pagename . Page", () => ({
                        
                    }))
                })
            </script>
            <?php \$this->endSection(); ?>
            HTML : "";
            file_put_contents($pagePath, $pageContent);
            CLI::write("Page created at: " . $pagePath, 'green');
        } else {
            CLI::error("Page already exists at: " . $pagePath);
        }

        // Create the layout file if provided
        if ($layout) {
            $this->call('make:layout', [$layout]);
        }
    }
}
