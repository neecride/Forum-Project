 <div class="row">
        <div class="col-md-6">
            <div class="section-title">
                <h5>Modifiez votre Mail</h5>
            </div>

            <div class="card mb-3">
                <div class="card-body">
                    <form method="post" id="mail" action="">
                    <div class="form-group">
                        <label for="email">Changer votre Email</label>
                        <input type="email" name="email" class="Hoinput form-control" placeholder="email" value="<?= isset($_POST['email'] ) ? $_POST['email'] : $user->userAccount()->email ?>" id="email" require>
                    </div>
                    <div class="form-group">
                        <label for="email_confirm">Confirmation</label>
                        <input type="email" name="emailConfirm" class="Hoinput form-control" placeholder="email confirmation" value="<?= isset($_POST['emailConfirm'] ) ? $_POST['emailConfirm'] : $user->userAccount()->email ?>" id="email_confirm" require>
                    </div>
                    <button type="submit" name="edit-email" value="edit-email" class="btn btn-block btn-danger">Modifier votre email</button>
                    <?= $session->csrfInput(); ?>
                    </form>
                </div>
                <div class="card-footer"></div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="section-title">
                <h5>Modifiez votre mots de pass</h5>
            </div>
            <div class="card">
                <div class="card-body">
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
                        <button type="submit" name="pwd" class="btn btn-block btn-danger">Confirmer votre nouveau password</button>
                        <?= $session->csrfInput(); ?>
                    </form>
                </div>
                <div class="card-footer"></div>
            </div>
        </div>
        <div class="col-md-12 mb-3">
                <div class="card">
                    <div class="card-header">
                        <h6>
                            Bio
                        </h6>
                    </div>
                    <div class="card-body">
                        <form method="post" id='comment'>
                            <div class="form-group">
                                <label for="description">Une petite signature visible sur le forum <small class="text-muted">(Optionel)</small></label>
                                <div class="editor-area-forum">
                                    <?= $Parsing->MarkDownEditor('description', $user->userAccount()->description); ?>
                                </div>
                                <span class="help-block">Votre description ne dois pas dépasser 200 caractères</span>
                            </div>
                
                            <button type="submit" name="edit-profil" class="btn btn-sm btn-danger">Confirmer l'édition de votre profil</button>
                            <?= $session->csrfInput(); ?>
                        </form>
                    </div>
                    <div class="card-footer"></div>
                </div>
            </div>



</div>