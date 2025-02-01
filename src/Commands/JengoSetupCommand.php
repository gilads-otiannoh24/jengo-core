<?php

namespace Jengo\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class JengoSetupCommand extends BaseCommand
{
    protected $group       = 'Jengo';
    protected $name        = 'jengo:setup';
    protected $description = 'Sets ups jengo initials user files.';
    private array $userPrefences = [];

    public function run(array $params)
    {
        // ask user preferences
        $alpinejsPreference = CLI::prompt('Do you want alpinejs?', ['y', 'n']);
        $tailwindPreference = CLI::prompt('Do you want tailwindcss?', ['y', 'n']);
        $daisyuiPreference = CLI::prompt('Do you want daisyui?', ['y', 'n']);
        $notificationLibrary = CLI::prompt('Do you want daisyui?', ['y', 'n']);
        $abacLibrary = CLI::prompt('Do you want daisyui?', ['y', 'n']);
        $paymentLibrary = CLI::prompt('Do you want daisyui?', ['y', 'n']);

        $this->userPrefences = [
            'alpinejs' => $alpinejsPreference === 'y',
            'tailwindcss' => $tailwindPreference === 'y',
            'daisyui' => $daisyuiPreference === 'y',
            'notificationLibrary' => $notificationLibrary === 'y',
            'abacLibrary' => $abacLibrary === 'y',
            'paymentLibrary' => $paymentLibrary === 'y',
        ];

        // create pages and layouts folder nd add files
        $this->createPagesAndLayoutsFolders();

        // create the public folder structure
        $this->createPublicFolderStructure();
    }

    private function createPagesAndLayoutsFolders(): void
    {
        $directories = [
            APPPATH . 'Views/pages',
            APPPATH . 'Views/layouts/partials'
        ];

        $layoutsPartials = [
            'header_links.php' => <<<HTML
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title><?= \$title ?? "Jengo Starter Template" ?></title>

            <!-- tailwind css and global css -->
            <link rel="stylesheet" href="<?= base_url('css/main.css') . getFileVersion()  ?>">
            <link rel="stylesheet" href="<?= base_url('css/global.css') . getFileVersion()  ?>">

            <!-- main js -->
            <!-- Loads alpinejs, lodash, ajax utility class and utility functions -->
            <script src="<?= base_url('js/dist/app.bundle.js') . getFileVersion() ?>"></script>

            <!-- Define all global varialbes here -->
            <script>
                function App() {
                    return {
                        // global variables
                        // methods
                    }
                }
            </script>
            HTML,
            'footer_links.php' => ""
        ];

        // make dirs and add partials in the partials subdir
        foreach ($directories as $directory) {
            if (!is_dir($directory)) {
                mkdir($directory, 0777, true);
                CLI::write("Created directory: $directory", 'green');
            }
        }

        foreach ($layoutsPartials as $partial => $content) {
            $path = APPPATH . "Views/layouts/partials" . DIRECTORY_SEPARATOR . $partial;

            file_put_contents($path, $content);
            CLI::write("Created file: $path", 'green');
        }

        // create the home page
        $this->call('make:page', ['home', 'app']);
    }

    public function createPublicFolderStructure(): void
    {
        $baseDir = FCPATH; // The public folder
        $directories = [
            'js/src',
            'css/src',
            'resources/avatars',
            'resources/images',
            'plugins/maizzle',
        ];
        $files = [
            // JavaScript entry point
            "$baseDir/js/src/app.js" => <<<'EOT'
// Entry point for your app
import Alpine from "alpinejs";
import _ from "lodash";

Alpine.start();

window.Alpine = Alpine;
window._ = _;
EOT,
            // CSS files
            "$baseDir/css/src/tailwind.css" => $this->userPrefences['tailwindcss']
                ?
                "@tailwind base;
@tailwind utilities;
@tailwind components;"
                : "",
            "$baseDir/css/main.css" => $this->userPrefences['tailwindcss']
                ? "/* Extracted from Tailwind CSS */"
                : "",
            "$baseDir/css/global.css" => "/* Global CSS styles */",

            // Package.json for npm scripts
            "$baseDir/package.json" => json_encode([
                "scripts" => [
                    "dev" => "webpack --watch",
                    "build" => "webpack --mode production"
                ],
                "dependencies" => [
                    "tailwindcss" => $this->userPrefences['tailwindcss'] ? "^3.0.0" : null,
                    "autoprefixer" => $this->userPrefences['tailwindcss'] ? "^10.0.0" : null,
                    "daisyui" => $this->userPrefences['daisyui'] ? "^2.0.0" : null,
                    "alpinejs" => $this->userPrefences['alpinejs'] ? "^3.0.0" : null,
                ],
                "devDependencies" => [
                    "lodash" => "^4.17.21",
                    "alpinejs" => "^3.14.8",
                    "babel-loader" => "^9.2.1",
                    "mini-css-extract-plugin" => "^2.0.0",
                    "css-loader" => "^6.0.0",
                    "postcss-loader" => "^6.0.0",
                    "webpack" => "^5.0.0",
                    "webpack-cli" => "^4.0.0"
                ]
            ], JSON_PRETTY_PRINT),
            // Webpack configuration
            "$baseDir/webpack.config.js" => <<<'EOT'
const path = require('path');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');

module.exports = {
    mode: 'development',
    entry: {
        app: './js/src/app.js',
        main: './css/src/tailwind.css'
    },
    output: {
        filename: '[name].bundle.js',
        path: path.resolve(__dirname, "js/dist")
    },
    module: {
        rules: [
            {
                test: /\.css$/,
                use: [MiniCssExtractPlugin.loader, 'css-loader', 'postcss-loader']
            },
            {
                test: /\.js$/,
                exclude: /node_modules/,
                use: {
                    loader: 'babel-loader'
                }
            }
        ]
    },
    plugins: [
        new MiniCssExtractPlugin({
            filename: '../../css/[name].css'
        })
    ],
};
EOT,

            // Tailwind configuration
            "$baseDir/tailwind.config.js" => $this->userPrefences['tailwindcss']
                ? <<<'EOT'
module.exports = {
    content: ["../app/Views/**/*.php"],
    theme: {
        extend: {},
    },
    plugins: [
        require('daisyui') // DaisyUI plugin if enabled
    ].filter(Boolean),
};
EOT
                : "",

            // PostCSS configuration
            "$baseDir/postcss.config.js" => $this->userPrefences['tailwindcss']
                ? <<<'EOT'
module.exports = {
    plugins: {
        tailwindcss: {},
        autoprefixer: {},
    },
};
EOT
                : ""
        ];

        // Create directories
        foreach ($directories as $dir) {
            $path = $baseDir . $dir;
            if (!is_dir($path)) {
                mkdir($path, 0755, true);
                CLI::write("Created directory: $path", 'green');
            } else {
                CLI::write("Directory already exists: $path", 'yellow');
            }
        }

        // Create files
        foreach ($files as $file => $content) {
            if (!empty($content) && !file_exists($file)) {
                file_put_contents($file, $content);
                CLI::write("Created file: $file", 'green');
            } else {
                CLI::write("Skipped file: $file (already exists or not applicable)", 'yellow');
            }
        }

        // Additional logic for Maizzle
        $maizzleConfigPath = $baseDir . 'plugins/maizzle/config.js';
        if (!file_exists($maizzleConfigPath)) {
            file_put_contents($maizzleConfigPath, "// Maizzle configuration file");
            CLI::write("Created Maizzle config file: $maizzleConfigPath", 'green');
        }

        // Prompt the user to run npm install
        $runNpmInstall = strtolower(CLI::prompt('Do you want to run "npm install" to install dependencies?', ['y', 'n'])) === 'y';

        if ($runNpmInstall) {
            CLI::write("Running 'npm install'...", 'green');
            $this->runShellCommand('npm install', $baseDir);
            CLI::write('npm install completed successfully.', 'green');
        } else {
            CLI::write('Skipping npm install. You can run it later by navigating to the public directory and executing "npm install".', 'yellow');
        }

        CLI::write("Public folder structure created successfully.", 'green');
    }

    /**
     * Executes a shell command.
     *
     * @param string $command The command to execute.
     * @param string $workingDir The directory in which to execute the command.
     * @return void
     */
    protected function runShellCommand(string $command, string $workingDir): void
    {
        chdir($workingDir);
        $output = [];
        $returnVar = 0;

        exec($command, $output, $returnVar);

        if ($returnVar !== 0) {
            CLI::write("Error executing command: $command", 'green');
        } else {
            foreach ($output as $line) {
                CLI::write($line, 'yellow');
            }
        }
    }
}
