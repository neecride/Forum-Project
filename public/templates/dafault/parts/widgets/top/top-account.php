<div class="row">
    <div class="col-md-12 text-center mb-3">
        <div class="avav">
            <?= $forum->renderAvatar($user->userAccount()->avatar, 'ig-avatar') ?>
            <div class="ctnfile">
                <?php if(empty($user->userAccount()->avatar)): ?>
                <form action="account-edit" method="post" enctype="multipart/form-data">
                    <label for="file" data-toggle="tooltip" data-placement="top" title="Selectionner votre avatar" id="file-select-button" class="label-file">
                        <i class="fas fa-upload"></i>
                    </label>
                    <input id="file" class="input-file" name="avatar" type="file">
                    <button type="submit" name="avatar" data-toggle="tooltip" data-placement="bottom" title="Uppload avatar" class="btn-file">
                        <i class="fas fa-check-square"></i>
                    </button>
                    <?= $session->csrfInput(); ?>
                </form>
                <?php else: ?>
                    <?php if(isset($user->userAccount()->avatar) && !empty($user->userAccount()->avatar)){ ?>
                        <form action="account-edit" method="post">
                            <?= $session->csrfInput() ?>
                            <button type="submit" name="delete-avatar" data-toggle="tooltip" data-placement="bottom" title="Avatar suppréssion" class="btn-del-file" onclick="return confirm('Sur de sur ?');">
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
        </div>
        <?= $user->checkError(); ?>
    </div>
</div>