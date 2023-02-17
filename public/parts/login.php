<div class="row">

    <div class="col-md-6">
        <div class="section-title">
            <h5>
                Login
            </h5>
        </div>
    </div>
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="row">

                    <div class="col-md-6">
                        <form method="POST">
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="sr-only" for="inlineFormInputGroup">Username/Email</label>
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text Hoinput"><i class="far fa-envelope"></i></div>
                                        </div>
                                        <input type="text" name="username" value="<?= isset($_POST['username']) ? htmlentities($_POST['username']) : '' ?>" class="form-control Hoinput" id="inlineFormInputGroup" placeholder="Username/Email" required>
                                    </div>
                                    <label class="sr-only" for="inlineFormInputGroup">Mot de pass</label>
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text Hoinput"><span class="icon-lock fas fa-lock"></span></div>
                                        </div>
                                        <input type="password" name="password" class="form-control Hoinput" id="inlineFormInputGroup" placeholder="Password" required>
                                    </div>
                                </div>
                            </div>    
                        <div class="form-group checkbox">
                            <label><input type="checkbox" name="remember" value="1">&nbsp;Se souvenir de moi</label>
                        </div>
                        <button name="login" type="submit" class="btn btn-danger">Login</button>
                        <?= csrfInput(); ?>
            
                        </form>
                        <a href="<?= $router->generate('remember') ?>" class="forget_pass">Mots de pass oublié ?</a>
                    </div>
                    <div class="col-md-6">
                        <div class="login__register">
                            <h3 class="text-center">Si vous n'avez pas de compte ?</h3>
                            <a href="<?= $router->generate('register') ?>" class="btn btn-danger w-100">Créer un compte</a>
                        </div>
                    </div>
                </div>
                </div>
        </div>
    </div>

</div>