<?= $reset->checkError() ?>
<div class="row">
    <div class="col-md-12">
        <div class="section-title">
            <h5>
                Réinitialisez votre mots de pass
            </h5>
        </div>
    </div>
    <div class="col-md-12">
    <div class="card">
        <div class="card-header"></div>
        <div class="card-body">
            <aside id="recent-comments-2" class="block box-shadow widget_recent_comments">
                    <div class="droiteContenu">
                    <form method="post" id="pass" action="">
                        <div class="form-group">
                        <?= $Parsing->input('password','password','nouveau mots de passe','required="required"') ?>
                        <?= $Parsing->input('password_confirm','password','Répétez le mots de passe','required="required"') ?>
                        <button type="submit" name="pwd" class="btn btn-danger btn-block">Confirmer votre nouveau password</button>
                        <?= $session->csrfInput(); ?>
                    </form>

                    </div>
            </aside>
        </div>
        <div class="card-footer"></div>
    </div>
    </div>
</div>
