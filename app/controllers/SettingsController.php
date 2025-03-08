<?php

class SettingsController {
    public function index() {
        require_once plugin_dir_path(dirname(__FILE__)) . '/models/SettingsModel.php';
        $model = new SettingsModel();
        $settings = $model->get_settings();
        require_once plugin_dir_path(dirname(__FILE__)) . '/views/settings-view.php';
    }

    public function save() {
        require_once plugin_dir_path(dirname(__FILE__)) . '/models/SettingsModel.php';
        $model = new SettingsModel();
        $model->save_settings($_POST);
        wp_redirect(admin_url('admin.php?page=tahesab-connector'));
        exit;
    }
}