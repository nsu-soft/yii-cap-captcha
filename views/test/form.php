<?php

use yii\helpers\Html;
?>
<?= Html::beginForm('', 'post', ['id' => 'text-form']) ?>
    <?= Html::input('text', 'text') ?>
    <?= Html::submitButton() ?>
<?= Html::endForm() ?>