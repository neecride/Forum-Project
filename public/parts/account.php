<div class="row">

<?php include 'parts/top-account.php'; ?>
    <div class="col-md-6">
        <div class="section-title">
            <h5>Vos infos</h5>
        </div>
        <p><strong>Votre role sur ce site</strong> : <?= htmlentities($user->slug); ?></p>
        <p><strong>Date inscription</strong> : <?= $GetParams->AppDate($user->date_inscription); ?></p>
        <p><strong>Site WEB</strong> : <a href="<?= $user->userurl; ?>" target="_blank"><i class="fas fa-globe"></i></a></p>
        <p><strong>Votre eMail</strong> : <a href="mailto:<?= $user->email; ?>"><i class="fas fa-at"></i></a></p>
    </div>
    <div class="col-md-6">
        <div class="section-title">
            <h5>Votre signature</h5>
        </div>
        <hr>
        <div class="signature">
            <?= $Parsing->RenderText($user->description); ?>
        </div>
    </div>

    <div class="col-md-6">
        <div class="section-title">
            <h5>Changez de theme</h5>
        </div>
        <form action="" method="post">
            <div class="form-group">
                <label for="service">Choisissez un thème <small>theme actuel - <span style="color:#dc3545;">(<?= $user->user_theme ?>)</span> </small></label>
                <select class="form-control Hoinput form-control-sm" name="theme_name">
                    <option  value="neec">neec</option>
                    <option  value="reup">reup</option>
                </select>
            </div>
            <?= csrfInput(); ?>
            <button type="submit" name="theme" class="btn btn-sm btn-danger">Changer de theme</button>
        </form>
        <div class="section-title">
            <h5>Desactivez votre compte</h5>
        </div>
        <form method="post">
            <?= csrfInput(); ?>
            <?php /*if(!empty($_SESSION['auth']->id != 1)){ */?>
            <button type="submit" name="lock-account" class="btn btn-sm btn-danger btn-xs" data-toggle="tooltip" data-placement="top" title="désactiver compte" onclick="return confirm('Sur de sur ?');">
                <strong>Desactiver votre compte</strong>
            </button>
            <?php /*}*/ ?>
        </form>
    </div>

</div>
