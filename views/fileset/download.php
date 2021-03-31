<?
    use yii\helpers\Url;
    use app\helpers\Format;
    $files = $fileset->get_files_array();
    $multiple_files = count($files) > 1;
?>

<? if (Yii::$app->session->hasFlash('files_uploaded')): ?>
    <? Yii::$app->session->removeFlash('files_uploaded') ?>
    <div class="files-uploaded-notice">
        <a href="#" class="close-btn">x</a>
        <p class="text">
            Files uploaded successfully! Download link:
        </p>
        <div class="url-and-copy-btn">
            <input class="url" id="url" type="text" value="<?= $fileset->url('download', false, true) ?>">
            <input class="copy-btn" type="button" class="btn-outline-black" value="Copy" onclick="url.select(); url.setSelectionRange(0, 1000); document.execCommand('copy'); url.setSelectionRange(0, 0);">
            <script>url.select()</script>
        </div>
    </div>
<? endif ?>

<table class="file-list">
    <? foreach ($files as $f): ?>
        <tr>
            <td class="icon-and-name"><span title="<?= $f['name'] ?>"><?= $f['name'] ?></span></td>
            <td class="size-and-dl-link"><span><?= Format::format_bytes($f['size']) ?></span>
            <? if ($multiple_files): ?>
                <a href="<?= $fileset->url('download-file', true, false, ['file_no' => explode('-', $f['id'])[1]]) ?>">Download</a>
            <? endif ?>
            </td>
        </tr>
    <? endforeach; ?>
    <? if ($multiple_files): ?>
        <tr>
            <td colspan="3" class="total-size">Total: <?= Format::format_bytes($fileset->size) ?></td>
        </tr>
    <? endif ?>
</table>

<? if ($multiple_files): ?>
    <a class="btn-black download-files-as-zip-btn" href="<?= $fileset->url('download-as-zip', true)?>">Download all</a><br>
    <span class="btn-comment">as zip acrhive</span>
<? else: ?>
    <a class="btn-black download-file-btn" href="<?= $fileset->url('download-file', true, false, ['file_no' => explode('-', $f['id'])[1]]) ?>">Download</a><br>
<? endif ?>

<div class="file-dates">
    Uploaded <?= date("d.m.Y", $fileset->time_uploaded) ?><br>
    Will be deleted <?= date("d.m.Y", $fileset->time_expiration) ?>
</div>
