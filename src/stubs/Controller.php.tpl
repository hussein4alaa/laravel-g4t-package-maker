<?php echo "<?php\n"; ?>

namespace <?php echo $vendor; ?>\<?php echo $package; ?>\Controllers;

use App\Http\Controllers\Controller;

class <?php echo $package ?>Controller extends Controller
{

    public function index()
    {
        return view('<?php echo $configFileName ?>::welcome');
    }

}