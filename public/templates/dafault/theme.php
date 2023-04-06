<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <base href="/">
    <meta name="description" content="Anime Template">
    <meta name="keywords" content="Anime, unica, creative, html">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= ucwords($match['target']) ?>&nbsp;|&nbsp;<?= ucwords($GetParams->GetParam(1)) ?></title>
    <link rel="icon" type="image/x-icon" href="<?= $router->webroot() ?>inc/favicon.ico">
    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Mulish:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="http://fonts.googleapis.com/css?family=Uncial+Antiqua" rel="stylesheet" type="text/css">
    <link href="http://fonts.googleapis.com/css?family=Niconne" rel="stylesheet" type="text/css">
    <link href="http://fonts.googleapis.com/css?family=Acme" rel="stylesheet" type="text/css">
    <link href="http://fonts.googleapis.com/css?family=Lato" rel="stylesheet" type="text/css">

    <!-- Css Styles -->
    <link rel="stylesheet" href="<?= $router->webroot() ?>templates/<?= $GetParams->themeForLayout() ?>/css/bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="<?= $router->webroot() ?>inc/css/all.min.css" type="text/css">
    <link rel="stylesheet" href="<?= $router->webroot() ?>inc/js/krajee-markdown-editor/css/markdown-editor.css" type="text/css">
    <link rel="stylesheet" href="<?= $router->webroot() ?>inc/css/choices.css" type="text/css">
    <link rel="stylesheet" href="<?= $router->webroot() ?>templates/<?= $GetParams->themeForLayout() ?>/css/style.css" type="text/css">
    <link rel="stylesheet" href="<?= $router->webroot() ?>templates/<?= $GetParams->themeForLayout() ?>/css/jquery.mCustomScrollbar.css" type="text/css">
    <link rel="stylesheet" href="<?= $router->webroot() ?>inc/css/prism.css" type="text/css">
        <!-- HTML5 shim, for IE6-8 support of HTML5 elements. All other JS at the end of file. -->
        <!--[if lt IE 9]>
        <script src="js/html5shiv.js"></script>
        <![endif]-->
</head>

<body>
    <!-- Page Preloder -->
    

    <!-- Header Section Begin -->
    <div class="container-fluid">
        <div id="mobile-menu-wrap"></div>
    </div>
    <!-- Blog Section Begin -->
    <section class="blog spad">
        <div class="container">  
            <div class="row">
                <div class="col-lg-12">
                        <div class="Honav">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="header__nav">
                                        <nav class="header__menu mobile-menu">
                                            <ul>
                                                <li class="<?= ($match['target'] == 'home') ?'active':null; ?>">
                                                    <a href="<?= $router->routeGenerate('home') ?>">Home</a>
                                                </li>
                                                <li class="<?= in_array($match['target'], ['forum','viewtopic','viewforums'] ) ?'active':''; ?>">
                                                    <a href="<?= $router->routeGenerate('forum') ?>">Forum</a>
                                                </li>
                                                <li class="">
                                                    <a href="#">Règles</a>
                                                </li>
                                            
                                            </ul>
                                        </nav>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="header__right">
                                        
                                        <?php if(empty($_SESSION['auth'])){ ?>
                                            <a href="<?= $router->routeGenerate('register') ?>">S'inscrire</a>
                                            <span style="color:#6a6a6a; margin:0 5px 0 5px;">|</span>
                                            <a href="<?= $router->routeGenerate('login') ?>">Se connecter</a>
                                        <?php }else{ ?>
                                            <?php if($_SESSION['auth']->authorization == 3){ ?>
                                                <a class="<?= (in_array($match['target'], ['admin','tags','tags-edit','user','user-edit'])) ?'active':null; ?>" href="<?= $router->routeGenerate('admin') ?>">
                                                    <i class="fas fa-tachometer-alt"></i>&nbsp;administration
                                                </a>
                                                <span style="color:#6a6a6a; margin:0 5px 0 5px;">|</span>  
                                            <?php } ?>
                                                <!-- 
                                                    
                                                
                                                affiche notif si réponse dans votre topic où l'id et egal a auth->id et si la date et supérieur a votre dernière réponse 
                                                
                                                -->
                                                <span class="dorpdown">
                                                    <i class="fas fa-bell dropdown-toggle" style="cursor:pointer;" data-toggle="dropdown"></i>
                                                    <div class="dropdown-menu">
                                                        <div class="dropable">
                                                            test
                                                        </div>
                                                        <div class="dropable">
                                                            test
                                                        </div>
                                                        <div class="dropable">
                                                            test
                                                        </div>
                                                    </div>
                                                </span>
                                                <span style="color:#6a6a6a; margin:0 5px 0 5px;">|</span>
                                            <a href="<?= $router->routeGenerate('account') ?>" class="<?= (in_array($match['target'], ['account','survey','account-edit'])) ? 'active' : null ?>">
                                                <i class="fas fa-user-circle"></i>&nbsp;&nbsp;<?= $_SESSION['auth']->username ?>
                                            </a>
                                            <a data-toggle="tooltip" data-placement="bottom" title="Déconnexion" href="<?= $router->routeGenerate('logout') ?>">&nbsp;&nbsp;<i class="fas fa-sign-out-alt"></i></a>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container">
                <?php 
                $activeHeader = $GetParams->GetParam(0, 'param_activ');
                $inpage = in_array($match['target'], ['home']);
                if($activeHeader == "oui" && $inpage): 
                ?>
                <div class="head">
                    <div class="col-lg-12 text-center">
                        <div class="normal__breadcrumb__text">
                            <div class="header__logo">
                                <h2><?= ucwords($GetParams->GetParam(0, 'param_name'))  ?></h2>
                            </div>
                            <p><?= $GetParams->GetParam(0,'param_value') ?></p>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <div class="container">
                <?php
                $activeWidget = $GetParams->GetParam(4, 'param_activ');
                if($activeWidget == "oui"): 
                    require_once RACINE.DS.'public'.DS.'templates'.DS.$GetParams->themeForLayout().DS.'parts'.DS.'widgets'.DS.'top'.DS.'widgetAlert.php'; 
                endif; 
                ?>
                <!-- Header End -->
                <?= $session->flash(); ?>
                <?= $contentForLayout; ?>
            </div>
    </section>
    <!-- Blog Section End -->
    <!-- Footer Section Begin -->
    <a href="#" class="go_top none"><i class="fas fa-angle-up"></i></a>
    <footer class="footer">
        <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="footer__logo">
                            <p style="text-align: left;">Conception par <a href="https://www.deviantart.com/snyl-laposny" style="color: #912c1a;">Wysiwyg</a></p>
                        </div>
                    </div>
                </div>
          </div>
      </footer>
      <!-- Footer Section End -->


    <!-- Js Plugins -->
    <script type="text/javascript" src="<?= $router->webroot() ?>templates/<?= $GetParams->themeForLayout() ?>/js/jquery-3.3.1.min.js"></script>
    <script type="text/javascript" src="<?= $router->webroot() ?>templates/<?= $GetParams->themeForLayout() ?>/js/popper.min.js"></script>
    <script type="text/javascript" src="<?= $router->webroot() ?>templates/<?= $GetParams->themeForLayout() ?>/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="<?= $router->webroot() ?>templates/<?= $GetParams->themeForLayout() ?>/js/to-markdown.js"></script>
    <script type="text/javascript" src="<?= $router->webroot() ?>templates/<?= $GetParams->themeForLayout() ?>/js/markdown.js"></script>
    <script type="text/javascript" src="<?= $router->webroot() ?>templates/<?= $GetParams->themeForLayout() ?>/js/popover.js"></script>
    <script type="text/javascript" src="<?= $router->webroot() ?>inc/js/all.min.js"></script>
    <script type="text/javascript" src="<?= $router->webroot() ?>inc/js/prism.js"></script>
    <script type="text/javascript" src="<?= $router->webroot() ?>inc/js/choices.js"></script>
    <script type="text/javascript" src="<?= $router->webroot() ?>inc/js/krajee-markdown-editor/js/markdown-editor.js"></script>
    <script type="text/javascript" src="<?= $router->webroot() ?>inc/js/krajee-markdown-editor/js/locales/fr.js"></script>
    <script type="text/javascript" src="<?= $router->webroot() ?>inc/js/krajee-markdown-editor/plugins/markdown-it/markdown-it.min.js"></script>

    <script>
        const element = document.querySelector('.js-choice');
        const choices = new Choices(element, {
            maxItemCount: 4,
            maxItemText: "Nombre de tags max atteint",
            removeItemButton: true,
            loadingText: 'Chargement...',
            searchEnabled: true,
            searchChoices: true,
            noResultsText: 'Pas de resultats',
            noChoicesText: 'Pas de choix',
            itemSelectText: 'Appuyez pour choisir',
        });
    </script>
    <script>
        $('#file-select-button').click(function(){
            $('.upload input').click();
        });
    </script>
    <!-- flash -->
    <script type="text/javascript">
        /*$(document).ready(function(){
                $(".notify").fadeOut(20000);
        });*/
    </script> 
    <script type="text/javascript">
        $(document).ready(function(){
            //click() or dblclick()
            $('.notify').click(function(){
                $(this).hide();
            });
        });
    </script>
     <!-- got top ctrl+q -->
    <script>
    $(document).ready(function(){
        // Condition d'affichage du bouton
        $(window).scroll(function(){
            if ($(this).scrollTop() > 500){
                $('.go_top').fadeIn();
            }
            else{
                $('.go_top').fadeOut();
            }
        });
        // Evenement au clic
        $('.go_top').click(function(){
            $('html, body').animate({scrollTop : 0},800);
            return false;
        });
    });
    </script>
    <script stype="text/javascript" >
        $('#editor1').markdownEditor({
        useTwemoji: true,
        theme: 'fa5',
        bsVersion: '4.4.1',
        enableSplitMode: true,
        enableLivePreview: false,
        toolbarHeaderL: [
            ['undo', 'redo'],
            ['bold', 'italic','del','blockquote'],
            ['link', 'image','codeblock']
            
            //['emoji']
        ],
        toolbarFooterL: [],
        toolbarFooterR: ['mode'],
            markdownItOptions: {}
        });
    </script>
    <script stype="text/javascript" >
        $('#editor2').markdownEditor({
        useTwemoji: true,
        theme: 'fa5',
        bsVersion: '4.4.1',
        enableSplitMode: true,
        enableLivePreview: false,
        toolbarHeaderL: [
            ['undo', 'redo'],
            ['bold', 'italic','del','blockquote'],
            ['link', 'image','codeblock']
            
            //['emoji']
        ],
        toolbarFooterL: [],
        toolbarFooterR: ['mode'],
            markdownItOptions: {}
        });
    </script>
    <script type="text/javascript">
    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip(); 
    });
    </script>
</body>

</html>
