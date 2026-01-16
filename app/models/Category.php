<?php
require_once 'Model.php';

class Category extends Model {
    protected $table = 'categories';

    public function getAll() {
        return $this->findAll([], 'name ASC');
    }
}
?>