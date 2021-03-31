<?
namespace app\models\forms;

use yii\base\Model;

class AdminLoginForm extends Model {

    public $password;

    public function formName() {
        return '';
    }

    public function rules() {
        return [
            ['password', 'required']
        ];
    }
}