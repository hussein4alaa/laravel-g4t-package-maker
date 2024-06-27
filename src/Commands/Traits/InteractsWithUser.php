<?php

namespace G4T\PackageMaker\Commands\Traits;

use Illuminate\Support\Str;

trait InteractsWithUser
{
    /**
     * Get vendor part of the namespace part.
     *
     * @param string $default
     *
     * @return string
     */
    protected function getVendor($question,)
    {
        return $this->ask($question);
    }

    /**
     * Get the name of package for the namespace.
     *
     * @param string $default
     *
     * @return string
     */
    protected function getPackage($question)
    {
        return $this->ask($question);
    }

    /**
     * Get vendor folder name.
     *
     * @param string $vendor
     *
     * @return string
     */
    protected function getVendorFolderName($vendor)
    {
        return Str::kebab($vendor);
    }

    /**
     * Get package folder name.
     *
     * @param string $package
     *
     * @return string
     */
    protected function getPackageFolderName($package)
    {
        return Str::kebab($package);
    }

    /**
     * Ask user.
     *
     * @param $question
     * @param $defaultValue
     *
     * @return string
     */
    protected function askUser($question, $defaultValue = '')
    {
        return $this->ask($question, $defaultValue);
    }
}