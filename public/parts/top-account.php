<div class="col-md-12 text-center mb-3">
    <div class="avav">
        <?= isset($user->avatar) && !empty($user->avatar)
        ? "<img class='ig-avatar' draggable='false' src='".WEBROOT."inc/img/avatars/".$user->avatar."' alt=''>"
        : "<img class='ig-avatar' src='" . WEBROOT . "inc/img/avatars/default.png' draggable='false' alt=''>" ; ?>
    </div>
</div>
<div class="col-md-12 text-center mb-3">
    <p class="user-nickname"><?= htmlentities($user->username); ?></p>
    <p class="user-info"><strong>Membre depuis le</strong> : <?= $GetParams->AppDate($user->date_inscription); ?></p>
    <p class="user-info"><strong>Type de compte</strong> : <?= htmlentities(ucwords($user->slug)); ?></p>
</div>
<div class="col-md-12">
    <div class="nav-tabs">
        <div class="row">
            <div class="col-md-12">
            <ul class="nav">
                <li class="<?= ($match['target'] == 'account') ?'accountLiactive':''; ?> accountLi">
                    <a href="<?= $router->generate('account') ?>"><i class="fas fa-user"></i> Account</a>
                </li>
                <li class="<?= ($match['target'] == 'survey') ?'accountLiactive':''; ?> accountLi">
                    <a href="<?= $router->generate('survey') ?>"><i class="fas fa-inbox"></i> Suivie des topic
                </a>
                </li>
                <li class="<?= ($match['target'] == 'account-edit') ?'accountLiactive':''; ?> accountLi">
                    <a href="<?= $router->generate('account-edit') ?>"><i class="fas fa-cogs"></i> Edition du profil</a>
                </li>
            </ul>
            </div>
        </div>
    </div>
</div>