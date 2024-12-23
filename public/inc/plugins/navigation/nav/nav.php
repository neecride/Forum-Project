<div id="boxnavbar">
    <div class="container-fluid">
        <div class="header-left">
            <div class="header-logo">
                <a id="logo" class="navbar-brand" href="<?= $router->routeGenerate('home') ?>">PixelCrafter <a href="#" class="go_top show"><i class="fas fa-angle-up"></i></a></a>
            </div>
        </div>
        <div class="header-right">
            <div class="dropdown">
                <span data-bs-toggle="offcanvas" href="#offcanvasScrolling" aria-controls="offcanvasScrolling"><i class="fas fa-th-large"></i></span>
                <!--<span class="header-separation"></span>
                <span id="golight"><i class="fas fa-sun"></i></span>-->
                <span class="header-separation"></span>
                <i class="fas fa-bell"></i>
                <span class="header-separation"></span>
                <span class="userbox-btn dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-user-circle"></i>
                </span>
                <ul class="dropdown-menu">
                    <?php if(empty($_SESSION['auth'])){ ?>
                        <li>
                            <a class="dropdown-item" href="<?= $router->routeGenerate('register') ?>"> S'inscrire</a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="<?= $router->routeGenerate('login') ?>"> Se connecter</a>
                        </li>
                    <?php }else{ ?>
                    <?php if($_SESSION['auth']->authorization == 3){ ?>
                        <li>
                            <a class="dropdown-item" href="<?= $router->routeGenerate('admin') ?>">
                                <i class="fas fa-tachometer-alt"></i>&nbsp;administration
                            </a>
                        </li>
                    <?php } ?> 
                    <li>
                        <a href="<?= $router->routeGenerate('account') ?>" class="dropdown-item">
                            <i class="fas fa-user-circle"></i>&nbsp;&nbsp;<?= $_SESSION['auth']->username ?>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="<?= $router->routeGenerate('logout') ?>"><i class="fas fa-sign-out-alt"></i> Logout</a>
                    </li>
                    <?php } ?>
                </ul>
            </div>
        </div>
        <div id="roundednav">
            <div class="links">
                <nav class="navbar navbar-expand">
                    <ul class="navbar-nav p-0">
                    <li class="nav-item">
                        <a id="homenav" class="nav-link active" href="<?= $router->routeGenerate('home') ?>"><i class="fas fa-home"></i> Home</a>
                    </li>
                    <li class="nav-item">
                        <a id="forumnav" class="nav-link" href="<?= $router->routeGenerate('forum') ?>"><i class="fas fa-comments"></i> Forum</a>
                    </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div> 

<div class="offcanvas offcanvas-start" data-bs-scroll="true" data-bs-backdrop="false" tabindex="-1" id="offcanvasScrolling" aria-labelledby="offcanvasScrollingLabel">
  <div class="offcanvas-header">
    <h3 style="color:white;">Navigation</h3>
    <span type="button" style="color:#fff;" data-bs-dismiss="offcanvas" aria-label="Close">
    <i class="fas fa-times"></i>
    </span>
  </div>
  <div class="offcanvas-body">
  <?php $app->widget(0,12) ?>
  </div>
</div>