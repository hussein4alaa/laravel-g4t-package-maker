<?php

namespace G4T\PackageMaker\Commands\Traits;

use Exception;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\View\Engines\PhpEngine;
use G4T\PackageMaker\Exceptions\RuntimeException;

trait CopiesSkeleton
{
    use InteractsWithUser;

    protected $packageBaseDir = __DIR__.'/../../../';

    /**
     * Copy skeleton to package folder.
     *
     * @param string $packagePath
     * @param string $vendor
     * @param string $package
     * @param string $vendorFolderName
     * @param string $packageFolderName
     *
     * @throws RuntimeException
     */
    protected function copySkeleton(
        $packagePath,
        $vendor,
        $package,
        $vendorFolderName,
        $packageFolderName
    ) {
        $this->info('Copy skeleton.');

        $skeletonDirPath = $this->getPathFromConfig(
            'skeleton_dir_path', $this->packageBaseDir.'/src/skeleton'
        );

        foreach (File::allFiles($skeletonDirPath, true) as $filePath) {
            $filePath = realpath($filePath);

            $destFilePath = Str::replaceFirst(
                $skeletonDirPath, $packagePath, $filePath
            );

            $this->copyFileWithDirsCreating($filePath, $destFilePath);
        }

        $this->copyStubs($packagePath, $package, $packageFolderName);

        $variables = $this->getVariables(
            $vendor, $package, $vendorFolderName, $packageFolderName
        );
        $this->replaceTemplates($packagePath, $variables);
    }

    /**
     * Copy stubs.
     *
     * @param $packagePath
     * @param $package
     * @param $packageFolderName
     */
    protected function copyStubs($packagePath, $package, $packageFolderName)
    {
        $controllerFilePath = $this->packageBaseDir.'/src/stubs/Controller.php.tpl';
        $mainClassFilePath = $this->packageBaseDir.'/src/stubs/MainClass.php.tpl';
        $configFilePath = $this->packageBaseDir.'/src/stubs/config.php';
        $routesPath = $this->packageBaseDir.'/src/stubs/web.php.tpl';
        $viewPath = $this->packageBaseDir.'/src/stubs/welcome.blade.php';
        $assetsPath = $this->packageBaseDir.'/src/stubs/styles.css';

        $filePaths = [
            $controllerFilePath => "$packagePath/src/Controllers/{$package}Controller.php.tpl",
            $mainClassFilePath => "$packagePath/src/$package.php.tpl",
            $configFilePath => "$packagePath/src/config/$packageFolderName.php",
            $routesPath => "$packagePath/src/routes/web.php.tpl",
            $viewPath => "$packagePath/src/views/welcome.blade.php",
            $assetsPath => "{$packagePath}/src/assets/css/styles.css",
        ];

        foreach ($filePaths as $filePath => $destFilePath) {
            $this->copyFileWithDirsCreating($filePath, $destFilePath);
        }
    }

    /**
     * Substitute all variables in *.tpl files and remove tpl extension.
     *
     * @param string $packagePath
     * @param array $variables
     */
    protected function replaceTemplates($packagePath, $variables)
    {
        $phpEngine = app()->make(PhpEngine::class);

        foreach (File::allFiles($packagePath, true) as $filePath) {
            $filePath = realpath($filePath);

            if (! Str::endsWith($filePath, '.tpl')) {
                continue;
            }

            try {
                $newFileContent = $phpEngine->get($filePath, $variables);
            } catch (Exception $e) {
                $this->error("Template [$filePath] contains syntax errors");
                $this->error($e->getMessage());
                continue;
            }

            $filePathWithoutTplExt = Str::replaceLast(
                '.tpl', '', $filePath
            );

            File::put($filePathWithoutTplExt, $newFileContent);
            File::delete($filePath);
        }
    }

    /**
     * Copy source file to destination with needed directories creating.
     *
     * @param string $src
     * @param string $dest
     */
    protected function copyFileWithDirsCreating($src, $dest)
    {
        $dirPathOfDestFile = dirname($dest);

        if (! File::exists($dirPathOfDestFile)) {
            File::makeDirectory($dirPathOfDestFile, 0755, true);
        }

        if (! File::exists($dest)) {
            File::copy($src, $dest);
        }
    }

    /**
     * Get variables for substitution in templates.
     *
     * @param string $vendor
     * @param string $package
     * @param string $vendorFolderName
     * @param string $packageFolderName
     *
     * @return array
     */
    protected function getVariables(
        $vendor,
        $package,
        $vendorFolderName,
        $packageFolderName
    ) {
        $packageWords = str_replace('-', ' ', Str::snake($packageFolderName));

        $composerDescription = $this->askUser(
            'The composer description?', "A $packageWords"
        );
        $composerKeywords = $this->getComposerKeywords($packageWords);

        $packageHumanName = $this->askUser(
            'The package human name?', Str::title($packageWords)
        );

        return [
            'vendor' => $vendor,
            'package' => $package,
            'vendorFolderName' => $vendorFolderName,
            'packageFolderName' => $packageFolderName,
            'packageHumanName' => $packageHumanName,

            'composerName' => "$vendorFolderName/$packageFolderName",
            'composerDesc' => $composerDescription,
            'composerKeywords' => $composerKeywords,
            'license' => $this->askUser('The package licence?', 'MIT'),
            'phpVersion' => $this->askUser('Php version constraint?', '>=8.2'),

            'aliasName' => $packageFolderName,
            'configFileName' => $packageFolderName,

            'year' => date('Y'),

            'name' => $this->askUser('Your name?'),
            'email' => $this->askUser('Your email?'),
            'githubPackageUrl' => "https://github.com/$vendorFolderName/$packageFolderName",
        ];
    }

    /**
     * Get path from config.
     *
     * @param string $configName
     * @param string $default
     *
     * @return string
     *
     * @throws RuntimeException
     */
    protected function getPathFromConfig($configName, $default)
    {
        $path = config("package-generator.$configName");

        if (empty($path)) {
            $path = $default;
        } else {
            $path = base_path($path);
        }

        $realPath = realpath($path);

        if ($realPath === false) {
            throw RuntimeException::noAccessTo($path);
        }

        return $realPath;
    }

    /**
     * Get composer keywords.
     *
     * @param $packageWords
     *
     * @return string
     */
    protected function getComposerKeywords($packageWords)
    {
        $keywords = $this->askUser(
            'The composer keywords? (comma delimited)', str_replace(' ', ',', $packageWords)
        );
        $keywords = explode(',', $keywords);
        $keywords = array_map(function ($keyword) {
            return "\"$keyword\"";
        }, $keywords);

        return implode(",\n".str_repeat(' ', 4), $keywords);
    }
}