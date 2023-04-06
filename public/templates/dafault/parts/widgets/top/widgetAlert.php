<div class="alert-box alert-<?= $GetParams->GetParam(4, 'param_color') ?>">
    <div class="alert-box-title"><?= $Parsing->Renderline($GetParams->GetParam(4, 'param_name')) ?></div>
    <div class="alert-box-content"><?= $Parsing->RenderText($GetParams->GetParam(4, 'param_value')) ?></div>
</div>