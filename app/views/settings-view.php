<div class="wrap">
    <h2>تنظیمات اتصال طه حساب</h2>
    <form method="post" action="<?php echo admin_url('admin-post.php'); ?>">
        <input type="hidden" name="action" value="save_tahesab_settings">
        <table class="form-table">
            <tr>
                <th scope="row"><label for="api_base_url">Base URL</label></th>
                <td><input type="text" name="api_base_url" id="api_base_url" value="<?php echo esc_attr($settings['api_base_url']); ?>" class="regular-text"></td>
            </tr>
            <tr>
                <th scope="row"><label for="api_authorization">Authorization Bearer</label></th>
                <td><input type="text" name="api_authorization" id="api_authorization" value="<?php echo esc_attr($settings['api_authorization']); ?>" class="regular-text"></td>
            </tr>
            <tr>
                <th scope="row"><label for="db_name">DBName</label></th>
                <td><input type="text" name="db_name" id="db_name" value="<?php echo esc_attr($settings['db_name']); ?>" class="regular-text"></td>
            </tr>
            <tr>
                <th scope="row"><label for="method">Method</label></th>
                <td>
                    <select name="method" id="method">
                        <option value="post" <?php selected($settings['method'], 'post'); ?>>POST</option>
                        <option value="get" <?php selected($settings['method'], 'get'); ?>>GET</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="encode_body">Encode Body</label></th>
                <td>
                    <select name="encode_body" id="encode_body">
                        <option value="json_encode" <?php selected($settings['encode_body'], 'json_encode'); ?>>JSON Encode</option>
                        <option value="serialize" <?php selected($settings['encode_body'], 'serialize'); ?>>Serialize</option>
                    </select>
                </td>
            </tr>
        </table>
        <?php submit_button('ذخیره تنظیمات'); ?>
    </form>
</div>