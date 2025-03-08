<?php

class InventoryController {
    public function index() {
        require_once plugin_dir_path(dirname(__FILE__)) . '/models/InventoryModel.php';
        $model = new InventoryModel();
        require_once plugin_dir_path(dirname(__FILE__)) . '/views/inventory-view.php';
    }

    public function update() {
        require_once plugin_dir_path(dirname(__FILE__)) . '/models/InventoryModel.php';
        $model = new InventoryModel();
        $result = $model->update_inventory();
        echo json_encode($result);
        wp_die();
    }
}