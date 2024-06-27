<?php echo "<?php\n"; ?>

namespace <?php echo $vendor; ?>\<?php echo $package; ?>;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    const CONFIG_PATH = __DIR__ . '/config/<?php echo $configFileName; ?>.php';

    public function boot()
    {
        $this->publishes([
            self::CONFIG_PATH => config_path('<?php echo $configFileName; ?>.php'),
        ], 'config');

        $this->publishes([
            __DIR__.'/assets' => public_path('<?php echo $configFileName; ?>-assets'),
        ], 'public');

        
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
        $this->loadViewsFrom(__DIR__.'/views', '<?php echo $configFileName; ?>');
    }

    public function register()
    {
        $this->mergeConfigFrom(
            self::CONFIG_PATH,
            '<?php echo $configFileName; ?>'
        );

    }
}
