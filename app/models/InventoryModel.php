<?php

// فعال کردن نمایش خطاها برای دیباگ
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// مسیر فایل ApiModel.php را بدست آورده و در صورت وجود آن را include می‌کنیم
$api_model_path = realpath(plugin_dir_path(__FILE__) . '/ApiModel.php');
if ($api_model_path) {
    require_once $api_model_path;
} else {
    // در صورت عدم وجود فایل، خطا را در log ثبت کرده و یک آرایه خالی برمی‌گردانیم
    error_log('ApiModel.php not found in: ' . plugin_dir_path(__FILE__) . '/ApiModel.php');
    return array('log' => 'خطا: فایل ApiModel.php پیدا نشد.', 'products' => array());
}

// مسیر فایل SettingsModel.php را بدست آورده و در صورت وجود آن را include می‌کنیم
$settings_model_path = realpath(plugin_dir_path(__FILE__) . '/SettingsModel.php');
if ($settings_model_path) {
    require_once $settings_model_path;
} else {
    // در صورت عدم وجود فایل، خطا را در log ثبت کرده و یک آرایه خالی برمی‌گردانیم
    error_log('SettingsModel.php not found in: ' . plugin_dir_path(__FILE__) . '/SettingsModel.php');
    return array('log' => 'خطا: فایل SettingsModel.php پیدا نشد.', 'products' => array());
}

class InventoryModel {
    public function update_inventory() {
        global $wpdb;

        // دریافت تمام محصولات از WooCommerce
        $products = wc_get_products(array('limit' => -1));

        // ایجاد یک نمونه از کلاس ApiModel برای ارتباط با API
        $api_model = new ApiModel();

        // ایجاد یک نمونه از کلاس SettingsModel برای دریافت تنظیمات
        $settings_model = new SettingsModel();
        $settings = $settings_model->get_settings();

        // ثبت اطلاعات تنظیمات در log
        error_log("Settings Data: " . print_r($settings, true));

        // نمایش اطلاعات تنظیمات در صفحه پلاگین
        echo '<h2>تنظیمات افزونه</h2>';
        echo '<pre>';
        print_r($settings);
        echo '</pre>';

        // متغیرهایی برای ذخیره log و محصولات به‌روزرسانی شده
        $log = '';
        $updated_products = array();

        // حلقه برای بررسی هر محصول
        foreach ($products as $product) {
            $sku = $product->get_sku();

            // چک کردن وجود SKU
            if (!$sku) {
                $log .= "محصول " . $product->get_name() . " SKU ندارد.\n";
                continue;
            }

            // ارسال درخواست به API برای هر SKU
            $body = json_encode(array('DoList_EtiketByCodeKar' => array($sku)));

            // چک کردن وجود تنظیمات قبل از ارسال درخواست به API
            if (!isset($settings['api_base_url']) || !isset($settings['method']) || !isset($settings['api_authorization'])) {
                error_log("Missing settings data in InventoryModel.php");
                continue; // در صورت عدم وجود تنظیمات، از این تکرار حلقه صرف نظر می‌کنیم
            }

            $result = $api_model->send_request(
                $settings['api_base_url'],
                $settings['method'],
                $settings['api_authorization'],
                $settings['db_name'],
                $body
            );

            
            // اگر نتیجه با موفقیت دریافت شد
            if ($result) {
                $data = json_decode($result, true);

                if ($data && is_array($data) && !empty($data)) {
                    // دریافت اولین محصول از پاسخ API
                    $item = reset($data);
                    $item = reset($item);

                    
                    if (isset($item['IsMojood'])) {
                        $old_status = $product->get_stock_status();
                        $new_status = ($item['IsMojood'] == 1) ? 'instock' : 'outofstock';
                        wc_update_product_stock($product->get_id(), ($item['IsMojood'] == 1) ? 1 : 0, $new_status);

                        $log .= "محصول " . $product->get_name() . " (SKU: " . $sku . ") به‌روزرسانی شد. وضعیت قبلی: " . $old_status . ", وضعیت جدید: " . $new_status . "\n";

                        $updated_products[] = array(
                            'name' => $product->get_name(),
                            'old_status' => $old_status,
                            'new_status' => $new_status,
                            'update_date' => current_time('mysql'),
                            'errors' => '',
                            'thumbnail' => wp_get_attachment_image_src($product->get_image_id(), 'thumbnail')[0],
                        );
                    } else {
                        $log .= "محصول " . $product->get_name() . " (SKU: " . $sku . ") اطلاعات موجودی در پاسخ API یافت نشد.\n";
                    }
                } else {
                    $log .= "محصول " . $product->get_name() . " (SKU: " . $sku . ") پاسخ نامعتبر از API دریافت شد.\n";
                }
            } else {
                $log .= "محصول " . $product->get_name() . " (SKU: " . $sku . ") درخواست به API ناموفق بود.\n";
            }
        }

        // برگرداندن log و اطلاعات محصولات به‌روزرسانی شده
        return array('log' => $log, 'products' => $updated_products);
    }
}
?>