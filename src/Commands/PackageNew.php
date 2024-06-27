<?php

namespace G4T\PackageMaker\Commands;

use Exception;
use Illuminate\Console\Command;
use G4T\PackageMaker\Commands\Traits\ChangesComposerJson;
use G4T\PackageMaker\Commands\Traits\CopiesSkeleton;
use G4T\PackageMaker\Commands\Traits\InteractsWithComposer;
use G4T\PackageMaker\Commands\Traits\InteractsWithGit;
use G4T\PackageMaker\Commands\Traits\ManipulatesPackageFolder;
use Illuminate\Support\Str;

class PackageNew extends Command
{
    use ChangesComposerJson;
    use ManipulatesPackageFolder;
    use InteractsWithComposer;
    use CopiesSkeleton;
    use InteractsWithGit;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:package';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new package.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $vendor = $this->getVendor('What is vendor name ?');
        $package = $this->getPackage('What is your package name');
        $vendorFolderName = $this->getVendorFolderName($vendor);
        $packageFolderName = $this->getPackageFolderName($package);
        $relPackagePath = "g4t/packages/$vendorFolderName/$packageFolderName";
        $packagePath = base_path($relPackagePath);

        try {
            $this->createPackageFolder($packagePath);    
            $this->registerPackage($vendorFolderName, $packageFolderName, $relPackagePath);
            $this->copySkeleton($packagePath, $vendor, $package, $vendorFolderName, $packageFolderName);
            $this->initRepo($packagePath);
            $this->composerUpdatePackage($vendorFolderName, $packageFolderName);
            $this->composerDumpAutoload();
            $this->info('Completed! Are you excited to create an amazing package?');
            $app_url = env('APP_URL');
            $packageWords = Str::snake($packageFolderName);
            $url = "{$app_url}/{$packageWords}";
            $this->info('Test your package from here: ' . $url);
    
            return 0;
        } catch (Exception $e) {
            $this->error($e->getMessage());
            return -1;
        }
    }
}