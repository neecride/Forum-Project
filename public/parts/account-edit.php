    <div class="row">
        <?php include 'parts/top-account.php'; ?>

        <div class="col-md-6">
        <div class="section-title">
                <h5>Modifiez votre Mail</h5>
            </div>
            <form method="post" id="mail" action="">
            <div class="form-group">
                <label for="email">Changer votre Email</label>
                <input type="email" name="email" class="Hoinput form-control" placeholder="email" value="<?= htmlentities($user->email) ?>" id="email" required>
            </div>
            <div class="form-group">
                <label for="email_confirm">Confirmation</label>
                <input type="email" name="email_confirm" class="Hoinput form-control" placeholder="email confirmation" value="<?= htmlentities($user->email) ?>" id="email_confirm" required>
                <small class="text-muted">Vous pouvez liée votre emil a gravatar, ou enoyez un avatar via le formulaire</small>
            </div>
            <button type="submit" name="edit-email" value="edit-email" class="btn btn-sm btn-danger">Modifier votre email</button>
            <?= csrfInput(); ?>
            </form>
        </div>
        <div class="col-md-6">
            <div class="section-title">
                <h5>Modifiez votre mots de pass</h5>
            </div>
            <form method="post" id="pass" action="">
                <div class="form-group password-toggle">
                    <label for="password">Changez mots de pass</label>
                    <input type="password" name="password" class="Hoinput form-control" placeholder="password" id="password" required>
                    <span id="passwd"></span>
                </div>
    
                <div class="form-group">
                    <label for="password_confirm">Répétez mots de pass</label>
                    <input type="password" name="password_confirm" class="Hoinput form-control" placeholder="password confirmation" id="password_confirm" required>
                    <span id="passwdc"></span>
                </div>
                <button type="submit" name="pwd" class="btn btn-sm btn-danger">Confirmer votre nouveau password</button>
                <?= csrfInput(); ?>
            </form>
        </div>
        <div class="col-md-12">

            <div class="section-title">
                <h5>Upload avatar</h5>
            </div>
        </div>
            <div class="col-md-6">

                <p>Format : png - Taille max 200ko</p>
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <input type="file" class="btn btn-sm btn-info" name="avatar">
                    </div>
                    <?= csrfInput(); ?>
                    <button type="submit" name="avatar" class="btn btn-sm btn-danger">Confirmer l'envoie de l'avatar</button>
                </form>
            </div>
            <div class="col-md-6">
                    <?= isset($user->avatar) && !empty($user->avatar)
                    ? "<img class='rounded mb-3' draggable='false' src='".WEBROOT."inc/img/avatars/".$user->avatar."' width='80px' alt=''>"
                    : "<img class='rounded mb-3' src='" . WEBROOT . "inc/img/avatars/default.png' draggable='false' width='80px' alt=''>" ; ?>
                <?php if(isset($user->avatar) && !empty($user->avatar)){ ?>
                    <form action="" method="post">
                        <?= csrfInput(); ?>
                        <button type="submit" name="delete-avatar" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="supprimer avatar" onclick="return confirm('Sur de sur ?');">Supprimer votre avatar <i class="fas fa-times-circle"></i></button>
                    </form>
                <?php } ?>
            </div>
        <div class="col-md-12 mb-3">
            <div class="section-title">
                    <h5>Bio</h5>
                </div>
                <form method="post" id='comment'>
                    <div class="form-group">
                        <label for="description">Une petite signature visible sur le forum <small class="text-muted">(Optionel)</small></label>
                        <div class="editor-area-forum">
                            <?= BootstrapMde('description', htmlentities($user->description)); ?>
                        </div>
                        <span class="help-block">Votre description ne dois pas dépasser 200 caractères</span>
                    </div>
        
                    <button type="submit" name="edit-profil" class="btn btn-sm btn-danger">Confirmer l'édition de votre profil</button>
                    <?= csrfInput(); ?>
                </form>
            </div>
        </div>