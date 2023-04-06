<?php
$user = new Action\AccountAction;
$session = new App\Session;
$activeWidget = 1;
$inpage = in_array($match['target'], ['home']);
if($activeWidget == 1 && $inpage){
	if(isset($_SESSION['auth']))
	{
?>
<div class="card-login mb-3">
	<div class="card-login-body">
		<div class="card-ava">
		<?= isset($user->userAccount()->avatar) && !empty($user->userAccount()->avatar)
        ? "<img class='ig-avatar' draggable='false' src='". $router->webroot() ."inc/img/avatars/".$user->userAccount()->avatar."' alt=''>"
        : "<img class='ig-avatar' draggable='false' src='" . $router->webroot() . "inc/img/avatars/default.png' alt=''>" ; ?>
		</div>
		<div class="row">
			<div class="col-md-12 text-center">
				<div class="btn-group" role="group" aria-label="...">
					<a href="<?= $router->routeGenerate('account') ?>" class="btn btn-filed filed"><i class="fas fa-user"></i></a>
					<a href="<?= $router->routeGenerate('creattopic') ?>" class="btn btn-filed filed"><i class="fas fa-plus"></i></a>
					<a href="<?= $router->routeGenerate('logout') ?>" class="btn btn-filed filed"><i class="far fa-times-circle"></i></a>
				</div>
			</div>
		</div>
	</div>
	<div class="card-limiter">
		<div class="row">
			<div class="col-md-12 text-center">
				<div class="btn-group" role="group" aria-label="...">
					<?php if(in_array($user->userAccount()->authorization, [3])): ?>
						<a href="<?= $router->routeGenerate('admin') ?>" class="btn btn-filed-limiter filed-limiter"><i class="fas fa-tachometer-alt"></i></a>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
	<div class="card-login-footer"></div>

</div>

<?php

	}else{
	
?>
<div class="card-login mb-3">

	<div class="card-login-body">
		<form action="<?= $router->routeGenerate('login') ?>" method="POST">
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
		<?= $session->csrfInput(); ?>

		</form>
	</div>
	<div class="card-login-footer">

	</div>

</div>

<?php 

	} 

}
?>