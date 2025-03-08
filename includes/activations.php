<?php

function tahesab_activate() {
    // ایجاد فایل‌های لاگ و پیکربندی در صورت عدم وجود
    if (!file_exists(plugin_dir_path(dirname(__FILE__)) . 'logs')) {
        mkdir(plugin_dir_path(dirname(__FILE__)) . 'logs');
    }

    if (!file_exists(plugin_dir_path(dirname(__FILE__)) . 'config/config.php')) {
        $config_content = "<?php\n\nreturn [\n    'api_base_url' => 'https://80.210.38.28:8081',\n    'api_authorization' => 'N1B1X1B5B6B1J1H3B6L4A7R2R3C1Q4N3F3W8X3T6Y2F6B5A7D5P8A3O3X7Y6L8O7H5S6D4H5I1V7D8B8',\n    'db_name' => 'TahesabDB',\n    'method' => 'post',\n    'encode_body' => 'json_encode',\n];";
        file_put_contents(plugin_dir_path(dirname(__FILE__)) . 'config/config.php', $config_content);
    }

    if (!file_exists(plugin_dir_path(dirname(__FILE__)) . 'logs/settings.log')) {
        touch(plugin_dir_path(dirname(__FILE__)) . 'logs/settings.log');
    }

    if (!file_exists(plugin_dir_path(dirname(__FILE__)) . 'logs/inventory.log')) {
        touch(plugin_dir_path(dirname(__FILE__)) . 'logs/inventory.log');
    }
}