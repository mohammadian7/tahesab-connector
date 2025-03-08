<div class="wrap">
    <h2>به‌روزرسانی موجودی</h2>
    <button id="update_inventory">به‌روزرسانی</button>
    <div id="progress_bar" style="width: 100%; background-color: #ddd;">
        <div id="progress" style="width: 0%; height: 30px; background-color: #4CAF50;"></div>
    </div>
    <div id="update_log" style="height: 300px; overflow-y: scroll; border: 1px solid #ddd; padding: 10px;"></div>
    <table id="inventory_table" class="wp-list-table widefat fixed striped table-view-list">
        <thead>
            <tr>
                <th>ردیف</th>
                <th>نام محصول</th>
                <th>وضعیت فعلی</th>
                <th>وضعیت جدید</th>
                <th>تاریخ به‌روزرسانی</th>
                <th>خطاها</th>
                <th>تصویر بندانگشتی</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>

<script>
jQuery(document).ready(function($) {
    $('#update_inventory').click(function() {
        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'update_tahesab_inventory',
            },
            xhr: function() {
                var xhr = new window.XMLHttpRequest();
                xhr.upload.addEventListener("progress", function(evt) {
                    if (evt.lengthComputable) {
                        var percentComplete = (evt.loaded / evt.total) * 100;
                        $('#progress').width(percentComplete + '%');
                    }
                }, false);
                return xhr;
            },
            success: function(response) {
                var data = JSON.parse(response);
                $('#update_log').append(data.log);

                var tableBody = $('#inventory_table tbody');
                tableBody.empty();

                $.each(data.products, function(index, product) {
                    var row = '<tr>';
                    row += '<td>' + (index + 1) + '</td>';
                    row += '<td>' + product.name + '</td>';
                    row += '<td>' + product.old_status + '</td>';
                    row += '<td>' + product.new_status + '</td>';
                    row += '<td>' + product.update_date + '</td>';
                    row += '<td>' + product.errors + '</td>';
                    row += '<td><img src="' + product.thumbnail + '" width="50"></td>';
                    row += '</tr>';
                    tableBody.append(row);
                });
            },
            error: function() {
                $('#update_log').append('خطا در به‌روزرسانی.');
            }
        });
    });
});
</script>