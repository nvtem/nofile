<?
    use yii\widgets\LinkPager;
    use app\helpers\Format;
    use yii\helpers\Url;
?>

<h2 class="title">
    Uploaded filesets
</h2>

<table class="filesets-table">
    <thead>
        <tr>
            <th>ID <a href="<?= Url::to(['/admin/fileset/list', 'order_by' => 'id', 'order_dir' => '^']) ?>">&#9650;</a> <a href="<?= Url::to(['/admin/fileset/list', 'order_by' => 'id', 'order_dir' => 'v']) ?>">&#9660;</a></th>
            <th>IP <a href="<?= Url::to(['/admin/fileset/list', 'order_by' => 'ip', 'order_dir' => '^']) ?>">&#9650;</a> <a href="<?= Url::to(['/admin/fileset/list', 'order_by' => 'ip', 'order_dir' => 'v']) ?>">&#9660;</a></th>
            <th>Upload time <a href="<?= Url::to(['/admin/fileset/list', 'order_by' => 'time_uploaded', 'order_dir' => '^']) ?>">&#9650;</a> <a href="<?= Url::to(['/admin/fileset/list', 'order_by' => 'time_uploaded', 'order_dir' => 'v']) ?>">&#9660;</a></th>
            <th>Files</th>
            <th>Size <a href="<?= Url::to(['/admin/fileset/list', 'order_by' => 'size', 'order_dir' => '^']) ?>">&#9650;</a> <a href="<?= Url::to(['/admin/fileset/list', 'order_by' => 'size', 'order_dir' => 'v']) ?>">&#9660;</a></th>
            <th>Protected <a href="<?= Url::to(['/admin/fileset/list', 'order_by' => 'is_protected', 'order_dir' => '^']) ?>">&#9650;</a> <a href="<?= Url::to(['/admin/fileset/list', 'order_by' => 'is_protected', 'order_dir' => 'v']) ?>">&#9660;</a></th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>

    <? foreach ($filesets as $fileset): ?>
        <tr>
            <td class="id">
                <a target="_blank" href="<?= $fileset->url('download', true) ?>"><?= $fileset->id ?></a>
            </td>
            <td><?= $fileset->ip ?></td>
            <td><span title="Will be deleted: <?= date('d.m.Y H:i:s', $fileset->time_expiration)?>"><?= date('d.m.Y H:i:s', $fileset->time_uploaded) ?></span></td>
            <td class="files">
                <ul>
                    <? foreach ($fileset->get_files_array() as $i => $f): ?>
                        <a title="<?= $f['name'] ?>"href="<?= $fileset->url('download-file', true, false, ['file_no' => $i]) ?>"><?= Format::trunc_string_add_ellipsis($f['name'], 15) ?> [<?= Format::format_bytes($f['size']) ?>]</a>
                        <br>
                    <? endforeach ?>
                </ul>
            </td>
            <td><?= Format::format_bytes($fileset->size) ?></td>
            <td>
                <? echo $fileset->is_protected ? 'Yes' : 'No'; ?>
            </td>
            <td class="actions">
                <a href="#" onclick="prompt('Access hash:', '<?= $fileset->is_protected ? $fileset->access_hash : ""?>'); return false;">Access-Hash</a>
                <a href="<?= $fileset->url('download-as-zip', true) ?>">Zip</a>
                <a href="<?= Url::to(['/admin/fileset/delete', 'id' => $fileset->id]) ?>" class="delete-fileset-link">Delete</a>
            </td>
        </tr>
    <? endforeach ?>
    </tbody>

</table>

<?
echo LinkPager::widget([
    'pagination' => $pages,
]);
?>