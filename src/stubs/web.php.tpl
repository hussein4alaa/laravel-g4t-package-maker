<?php echo "<?php\n"; ?>

use Illuminate\Support\Facades\Route;
use <?php echo $vendor; ?>\<?php echo $package; ?>\Controllers\<?php echo $package ?>Controller;

Route::get('<?php echo $configFileName ?>', [<?php echo $package ?>Controller::class, 'index']);