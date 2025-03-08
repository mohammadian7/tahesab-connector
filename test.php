<?php
require_once plugin_dir_path(__FILE__) . 'app/models/InventoryModel.php';
$model = new InventoryModel();
$model->update_inventory();
?>