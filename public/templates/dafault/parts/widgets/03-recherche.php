<?php
$activeWidget = 1;
$inpage = in_array($match['target'], ['home']);
if($activeWidget == 1 && $inpage){
?>
<div class="card-login mb-3">
	<div class="card-login-body">
        <form action="">
            <label class="sr-only" for="inlineFormInputGroupUsername">Recherche</label>
            <div class="input-group">
                <div class="input-group-prepend">
                <div class="input-group-text Hoinput"><i class="fas fa-search"></i></div>
                </div>
                <input type="text" class="form-control Hoinput" id="inlineFormInputGroupUsername" placeholder="Recherche">
            </div>
        </form>
	</div>
	<div class="card-login-footer"></div>
</div>
<?php } ?>