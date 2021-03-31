<?php

use yii\db\Migration;

class m190809_100621_create_filesets_table extends Migration {
    public function safeUp() {
        $this->createTable('filesets', [
            'id' => $this->primaryKey(),
            'ip' => $this->text(),
            'files' => $this->text(),
            'time_uploaded' => $this->integer(),
            'time_expiration' => $this->integer(),
            'size' => $this->integer(),
            'is_protected' => $this->integer(),
            'access_hash' => $this->text(),
        ]);
    }

    public function safeDown() {
        $this->dropTable('filesets');
    }
}
