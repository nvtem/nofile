<?

namespace app\assets;

use yii\web\AssetBundle;

class JqueryFormPluginAsset extends AssetBundle
{
    public $sourcePath = "@bower/jquery-form/dist";

    public $js = [
        'jquery.form.min.js',
    ];
}
