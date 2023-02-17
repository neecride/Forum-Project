<div class="col-md-12">

    <aside id="recent-comments-2" class="card">
        <div class="card-header">Réinitialiser votre mots de pass</div>
            <div class="card-body">
            <form method="post">
                <div class="form-group validate">
                    <input type="email" placeholder="email" name="email" class="form-control Hoinput" require>
                    <span class="help-block">Si vous n'avez pas reçu le mail renvoyez ce formulaire</span>
                </div>
            <button type="submit" name="submit" class="btn btn-danger ">Envoyer</button> 
            <a href="<?= WEBROOT; ?>login" class="btn btn-link">Login</a>
            <?= csrfInput(); ?>
            </form>

            </div>
    </aside>
</div>
