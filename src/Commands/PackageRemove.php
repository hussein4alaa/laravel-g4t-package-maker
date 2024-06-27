<?php

namespace G4T\PackageMaker\Commands;

use Exception;
use Illuminate\Console\Command;
use G4T\PackageMaker\Commands\Traits\ChangesComposerJson;
use G4T\PackageMaker\Commands\Traits\InteractsWithComposer;
use G4T\PackageMaker\Commands\Traits\InteractsWithUser;
use G4T\PackageMaker\Commands\Traits\ManipulatesPackageFolder;

class PackageRemove extends Command
{
    use ChangesComposerJson;
    use ManipulatesPackageFolder;
    use InteractsWithUser;
    use InteractsWithComposer;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'remove:package';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove the existing package.';

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
            $this->composerRemovePackage($vendorFolderName, $packageFolderName);
            $this->removePackageFolder($packagePath);
            $this->unregisterPackage($vendor, $package, "packages/$vendorFolderName/$packageFolderName");
            $this->composerDumpAutoload();

            return 0;
        } catch (Exception $e) {
            $this->error($e->getMessage());

            return -1;
        }
    }
}