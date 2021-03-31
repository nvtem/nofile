<?

namespace app\assets;

use yii\web\AssetBundle;

class ResetCssAsset extends AssetBundle {
    public $sourcePath = "@bower/reset-css/";

    public $css = [
        'reset.css',
    ];
}
