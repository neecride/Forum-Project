<?= $User->checkError() ?>
<div class="row">
    <div class="col-md-12">
        <div class="row">
          <div class="col-md-12">
            <div class="section-title">
              <h5>
                Administration
              </h5>
            </div>
          </div>
        </div>
        <div class="anime__details__review">
      <div class="row">
        <div class="col-xs-6 col-md-12">
          <div class="card">
            <div class="card-header">
              <h6>Edition utilisateur</h6>
            </div>
            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-md-11">
                        <h3>Username : <?= $User->getUser()->username ?></h3>
                    </div>
                </div>
                <form method="post">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input id="username" placeholder="Username" class="form-control Hoinput" type="text" name="name" value="<?= isset($_POST['name']) ? htmlentities($_POST['name']) : $User->getUser()->username ?>" required>
                        <span id="pseudo"></span>
                    </div>
                    <div class="form-group">
                      <select id="slug" class="form-control Hoinput" name="slug">
                          <option <?= !empty($User->getUser()->slug == 'admin')  ? 'selected="selected"' : '' ; ?> value="admin">admin</option>
                          <option <?= !empty($User->getUser()->slug == 'modo')   ? 'selected="selected"' : '' ; ?> value="modo">modo</option>
                          <option <?= !empty($User->getUser()->slug == 'membre') ? 'selected="selected"' : '' ; ?> value="membre">membre</option>
                      </select>
                    <span>Le slug | Statut actuel = <span style="color:#dc3545;" ><?= $User->getUser()->slug; ?></span></span>
                    </div>
                    <?= $session->csrfInput(); ?>
                    <button type="submit" name="users" class="btn btn-danger">Envoyer</button>
                    <a href="<?= $router->routeGenerate('user') ?>" class="btn btn-link">Anulée</a>
                </form>
            </div>
          </div>
        </div>
      </div>
  </div>
</div>
</div>
<!-- Blog Section End -->