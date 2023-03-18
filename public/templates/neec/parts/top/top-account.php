<div class="col-md-12 text-center mb-3">
    <div class="avav">
        <?= isset($user->userAccount()->avatar) && !empty($user->userAccount()->avatar)
        ? "<img class='ig-avatar' draggable='false' src='". $router->webroot() ."inc/img/avatars/".$user->userAccount()->avatar."' alt=''>"
        : "<img class='ig-avatar' draggable='false' src='" . $router->webroot() . "inc/img/avatars/default.png' alt=''>" ; ?>
        <div class="ctnfile">
            <?php if(empty($user->userAccount()->avatar)): ?>
            <form action="account-edit" method="post" enctype="multipart/form-data">
                <label for="file" data-toggle="tooltip" data-placement="top" title="Selectionner votre avatar" id="file-select-button" class="label-file"><i class="fas fa-upload"></i></label>
                <input id="file" class="input-file" name="avatar" type="file">
                <button type="submit" name="avatar" data-toggle="tooltip" data-placement="top" title="Uppload avatar" class="btn-file"><i class="fas fa-check-square"></i></button>
                <?= csrfInput(); ?>
            </form>
            <?php else: ?>
                <?php if(isset($user->userAccount()->avatar) && !empty($user->userAccount()->avatar)){ ?>
                    <form action="account-edit" method="post">
                        <?= csrfInput() ?>
                        <button type="submit" name="delete-avatar" data-toggle="tooltip" data-placement="top" title="Avatar suppréssion" class="btn-del-file" onclick="return confirm('Sur de sur ?');">
                            <i class="fas fa-times-circle"></i>
                        </button>
                    </form>
                <?php } ?>
            <?php endif; ?>
        </div>
    </div>
</div>
<div class="col-md-12 text-center mb-3">
    <p class="user-nickname"><?= $user->userAccount()->username ?></p>
    <p class="user-info"><strong>Membre depuis le</strong> : <?= $GetParams->AppDate($user->userAccount()->date_inscription); ?></p>
    <p class="user-info"><strong>Type de compte</strong> : <?= ucwords($user->userAccount()->slug) ?></p>
</div>
<div class="col-md-12">
    <div class="nav-tabs">
        <div class="row">
            <div class="col-md-12">
            <ul class="nav">
                <li class="<?= ($match['target'] == 'account') ?'accountLiactive':''; ?> accountLi">
                    <a href="<?= $router->routeGenerate('account') ?>"><i class="fas fa-user"></i> Account</a>
                </li>
                <li class="<?= ($match['target'] == 'survey') ?'accountLiactive':''; ?> accountLi">
                    <a href="<?= $router->routeGenerate('survey') ?>"><i class="fas fa-inbox"></i> Suivie de vos topic
                </a>
                </li>
                <li class="<?= ($match['target'] == 'account-edit') ?'accountLiactive':''; ?> accountLi">
                    <a href="<?= $router->routeGenerate('account-edit') ?>"><i class="fas fa-cogs"></i> Edition du profil</a>
                </li>
            </ul>
            </div>
        </div>
    </div>
    <?= $user->checkError(); ?>
</div>