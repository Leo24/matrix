<?php
/**
 * @var $this \yii\web\View
 */
//\backend\assets\EditorAsset::register($this);
?>
<div ng-app="unEditor">
    <div class="editor-navigation">
        <a href="#/editor/types/list" class="item">Типы документов</a>
        <a href="#/editor/categories/list" class="item">Категории документов</a>
    </div>
    <div data-ui-view=""></div>
</div>