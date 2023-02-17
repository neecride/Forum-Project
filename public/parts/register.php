
<div class="section-title">
    <h5>Géré les tags</h5>
</div>
<div class="card">
  <div class="card-body">

    <form method="post">
      <div class="row">
        <div class="col-md-6">

          <div class="form-group">
            <label for="username">Username</label>
            <input id="username" placeholder="Username" class="form-control Hoinput" type="text" name="name" value="<?= isset($_POST['name']) ? htmlentities($_POST['name']) : '' ?>" required>
            <span id="pseudo"></span>
          </div>
        </div>
        <div class="col-md-6">

          <div class="form-group">
            <label for="email">Votre email</label>
            <input id="email" placeholder="Votre email" class="form-control Hoinput" type="email" name="email" value="<?= isset($_POST['email']) ? htmlentities($_POST['email']) : '' ?>" required>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label for="password">Votre mot de pass</label>
            <input id="password" placeholder="Votre mot de pass" class="form-control Hoinput" type="password" name="pass" required>
            <span id="passwd"></span>
          </div>
        </div>

        <div class="col-md-6">
          <div class="form-group">
            <label for="password_confirm">Confirmez votre mot de pass</label>
            <input id="password_confirm" placeholder="Confirmez votre mot de pass" class="form-control Hoinput" type="password" name="pass_confirm" required>
            <span id="passwdc"></span>
          </div>

        </div>

        <div class="col-md-6">
          <div class="form-group">
            <label for="captcha">Confirmez le captcha</label>
            <input id="captcha" placeholder="Confirmez le captcha" class="form-control Hoinput" type="text" name="captcha" required>
            <img src="<?= WEBROOT ?>inc/img/captcha.php" alt="">
          </div>

        </div>
      
      
      </div>
      <button type="submit" name="register" class="btn btn-danger">Envoyer <i class="fas fa-paper-plane"></i></button>
      <?= csrfInput(); ?>
    </form>
  </div>
</div>