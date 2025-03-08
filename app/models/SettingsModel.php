<?php

class SettingsModel {
    public function get_settings() {
        $config = include plugin_dir_path(dirname(dirname(__FILE__))) . 'config/config.php';
        // بررسی کنید که $config یک آرایه است
        if (!is_array($config)) {
            // در صورت عدم وجود فایل config.php یا عدم برگرداندن آرایه، یک آرایه پیش‌فرض برگردانید
            return array(
                'api_base_url' => '',
                'api_authorization' => '',
                'db_name' => '',
                'method' => 'post',
                'encode_body' => 'json_encode',
            );
        }
        return $config;
    }

    public function save_settings($data) {
        $config = $this->get_settings();
        $config['api_base_url'] = sanitize_text_field($data['api_base_url']);
        $config['api_authorization'] = sanitize_text_field($data['api_authorization']);
        $config['db_name'] = sanitize_text_field($data['db_name']);
        $config['method'] = sanitize_text_field($data['method']);
        $config['encode_body'] = sanitize_text_field($data['encode_body']);

        $config_content = "<?php\n\nreturn " . var_export($config, true) . ";";
        file_put_contents(plugin_dir_path(dirname(dirname(__FILE__))) . 'config/config.php', $config_content);
        return true;
    }
}