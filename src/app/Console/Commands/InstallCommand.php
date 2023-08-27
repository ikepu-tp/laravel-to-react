<?php

namespace ikepu_tp\ToReact\app\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'toReact:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command makes this project with react.js(ts).';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        foreach ([".prettierignore", ".prettierrc", "tsconfig.json", "tsconfig.node.json"] as $val) {
            $this->copyFile(self::basePath() . "/root/{$val}", base_path($val));
        }
        (new Filesystem)->copyDirectory(self::basePath() . "resources/react", resource_path("react"));
        $this->copyFile(self::basePath() . "resources/views/react.blade.php", resource_path("views/react.blade.php"));
        $this->updateFile(base_path("routes/web.php"), "Route::get('/react', function () { return view('react'); })->name('react');");

        if ($this->confirm("Do you want to copy \"vite.config.js\" which is need to use react?")) {
            $this->copyFile(self::basePath() . "/root/vite.config.js", base_path("vite.config.js"), false);
        }

        if ($this->confirm("Do you want to update package.json?")) {
            $this->updateNodePackages([
                "@vitejs/plugin-react" => "^4.0.4",
                "prettier" => "^2.8.7",
            ]);
            $this->updateNodePackages([
                "@testing-library/jest-dom" => "^5.17.0",
                "@testing-library/react" => "^13.4.0",
                "@testing-library/user-event" => "^13.5.0",
                "@types/jest" => "^27.5.2",
                "@types/node" => "^16.18.46",
                "@types/react" => "^18.2.21",
                "@types/react-dom" => "^18.2.7",
                "react" => "^18.2.0",
                "react-dom" => "^18.2.0",
                "react-scripts" => "5.0.1",
                "typescript" => "^4.9.5",
                "web-vitals" => "^2.1.4"
            ]);
            $this->info("Please run \"npm install && npm run dev\".");
        }
    }

    protected function updateFile(string $path, string $code): void
    {
        if (!file_exists($path)) {
            return;
        }

        $file = file_get_contents($path);

        file_put_contents(
            $path,
            $file . PHP_EOL  . PHP_EOL . $code . PHP_EOL
        );

        $this->info("Update \"{$path}\".");
    }

    protected static function updateNodePackages(array $libs, $dev = true): void
    {
        if (!file_exists(base_path('package.json'))) {
            return;
        }

        $configurationKey = $dev ? 'devDependencies' : 'dependencies';

        $packages = json_decode(file_get_contents(base_path('package.json')), true);

        if (!isset($packages[$configurationKey])) $packages[$configurationKey] = [];

        $packages[$configurationKey] = array_merge($packages[$configurationKey], $libs);

        ksort($packages[$configurationKey]);

        file_put_contents(
            base_path('package.json'),
            json_encode($packages, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . PHP_EOL
        );
    }

    protected function copyFile(string $path, string $target, bool $check_exists = true): void
    {
        if ($check_exists && file_exists($target)) return;
        copy($path, $target);
        $this->info($path . " is copied to" . $target);
    }

    protected static function basePath(): string
    {
        return __DIR__ . "/../../../";
    }
}
