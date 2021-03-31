<?

namespace app\models;

use Yii;
use yii\base\BaseObject;
use yii\web\IdentityInterface;

class User extends BaseObject implements IdentityInterface {

    public $id;
    public $name;
    public $password;

    public static function findIdentity($id) {
        $users = Yii::$app->params['users'];
        return isset($users[$id]) ? new static($users[$id]) : null;
    }

    public function getId() {
        return $this->id;
    }

    public function getAuthKey() {
        return $this->authKey;
    }

    public function validateAuthKey($authKey) {
        return $this->authKey === $authKey;
    }

    public function validatePassword($password) {
        return $password == $this->password;
    }

    public function isAdmin() {
        return $this->name == 'admin';
    }

    public static function findIdentityByAccessToken($token, $type = null) {
        $users = Yii::$app->params['users'];
        foreach ($users as $user) {
            if ($user['accessToken'] === $token) {
                return new static($user);
            }
        }
        return null;
    }

}
