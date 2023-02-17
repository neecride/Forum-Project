<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <base href="/">
    <meta name="description" content="Anime Template">
    <meta name="keywords" content="Anime, unica, creative, html">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= ucwords($match['target']) ?>&nbsp;|&nbsp;<?= ucwords($GetParams->GetParam(0)) ?></title>
    <link rel="icon" type="image/x-icon" href="<?= WEBROOT ?>inc/favicon.ico">
    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Mulish:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Css Styles -->
    <link rel="stylesheet" href="<?= WEBROOT ?>templates/<?= $themeForLayout ?>/css/bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="<?= WEBROOT ?>inc/css/all.min.css" type="text/css">
    <link rel="stylesheet" href="<?= WEBROOT ?>inc/js/krajee-markdown-editor/css/markdown-editor.css" type="text/css">
    <link rel="stylesheet" href="<?= WEBROOT ?>inc/css/choices.css" type="text/css">
    <link rel="stylesheet" href="<?= WEBROOT ?>templates/<?= $themeForLayout ?>/css/style.css" type="text/css">
    <link rel="stylesheet" href="<?= WEBROOT ?>templates/<?= $themeForLayout ?>/css/jquery.mCustomScrollbar.css" type="text/css">
    <link rel="stylesheet" href="<?= WEBROOT ?>inc/css/prism.css" type="text/css">
        <!-- HTML5 shim, for IE6-8 support of HTML5 elements. All other JS at the end of file. -->
        <!--[if lt IE 9]>
        <script src="js/html5shiv.js"></script>
        <![endif]-->
</head>

<body>
    <!-- Page Preloder -->
    

    <!-- Header Section Begin -->
    <div class="container">
        <div class="row">
            <!--<div class="col-lg-2">
                <div class="header__logo">
                    Wysiwyg
                </div>
            </div>-->
            <div class="col-lg-8">
                <div class="header__nav">
                    <nav class="header__menu mobile-menu">
                        <ul>
                            <li class="<?= ($match['target'] == 'home') ?'active':''; ?>">
                                <a href="<?= $router->generate('home') ?>"><i class="fas fa-home"></i>&nbsp;Home</a>
                            </li>
                            <li class="<?= ($match['target'] == 'forum' || $match['target'] == 'viewtopic' || $match['target'] == 'viewforums') ?'active':''; ?>">
                                <a href="<?= $router->generate('forum') ?>"><i class="fas fa-comments"></i>&nbsp;Forum</a>
                            </li>
                            <li class="">
                                <a href="#"><i class="fab fa-diaspora"></i>&nbsp;Règles</a>
                            </li>
                           
                        </ul>
                    </nav>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="header__right None">
                    <?php if(empty($_SESSION['auth'])){ ?>
                        <a href="<?= $router->generate('register') ?>">S'inscrire</a>
                        <a href="<?= $router->generate('login') ?>">Se connecter</a>
                    <?php }else{ ?>
                        <?php if($_SESSION['auth']->authorization == 3){ ?>
                            <a class="<?= ($match['target'] == 'admin') ?'active':''; ?>" href="<?= $router->generate('admin') ?>">
                                <i class="fas fa-tachometer-alt"></i>&nbsp;administration
                            </a>
                            |
                        <?php } ?>
                            <!-- 
                                
                            
                            affiche notif si réponse dans votre topic où l'id et egal a auth->id et si la date et supérieur a votre dernière réponse 
                            
                            
                            <span class="dorpdown">
                                <i class="fas fa-bell dropdown-toggle" style="cursor:pointer;" data-toggle="dropdown"></i>
                                <ul class="dropdown-menu">
                                    <li><a href="#">topic 1</a></li>
                                    <li><a href="#">topic 2</a></li>
                                    <li><a href="#">topic 3</a></li>
                                </ul>
                            </span>
                            |
                            -->
                        <a href="<?= $router->generate('account') ?>"><i class="fas fa-user-circle"></i>&nbsp;<?= $_SESSION['auth']->username ?></a>
                        <a href="<?= $router->generate('logout') ?>"><i class="fas fa-sign-out-alt"></i></a>
                    <?php } ?>
                </div>
            </div>
        </div>
        <div id="mobile-menu-wrap"></div>
    </div>
    <div class="Honav">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 None">
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
                <div class="col-md-8">
                    <div class="breadcrumb__links" style="line-height: 37px;">
                        <a href="https://fr.wikipedia.org/wiki/Markdown#Formatage" target="_blank">Découvez markdown</a>
                        <a href="https://www.deviantart.com/snyl-laposny" target="_blank">My DevianArt</a>
                        <a href="https://github.com/neecride" target="_blank">My GitHub</a>
                        <span><?= $GetParams->GetParam(0) ?></span>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- Blog Section Begin -->
    <section class="blog spad">
    <div class="container">
    <!-- Header End -->
    <?= Flash(); ?>
    <?= CheckErreor(isset($error) ? $error : '' ); ?>
    <?= $contentForLayout; ?>
    </div>
    </section>
    <!-- Blog Section End -->
    <!-- Footer Section Begin -->
    <footer class="footer">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3">
                    <div class="footer__logo">
                        <p style="text-align: left;"><?= copyleft() ?></p>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="footer__nav">
                        <ul>
                            <li>
                                <a href="<?= $router->generate('home') ?>">Accueil</a>
                            </li>
                            <li>
                                <a href="<?= $router->generate('forum') ?>">Forum</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-3">
                    <p style="text-align: right;"><?= copyleft() ?></p>
                </div>
              </div>
          </div>
      </footer>
      <a href="#" class="go_top" style="display: none;"><i class="fas fa-angle-up"></i></a>
      <!-- Footer Section End -->


    <!-- Js Plugins -->
    <script type="text/javascript" src="<?= WEBROOT ?>templates/<?= $themeForLayout ?>/js/jquery-3.3.1.min.js"></script>
    <script type="text/javascript" src="<?= WEBROOT ?>templates/<?= $themeForLayout ?>/js/popper.min.js"></script>
    <script type="text/javascript" src="<?= WEBROOT ?>templates/<?= $themeForLayout ?>/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="<?= WEBROOT ?>templates/<?= $themeForLayout ?>/js/jquery.mCustomScrollbar.concat.min.js"></script>
    <script type="text/javascript" src="<?= WEBROOT ?>templates/<?= $themeForLayout ?>/js/to-markdown.js"></script>
    <script type="text/javascript" src="<?= WEBROOT ?>templates/<?= $themeForLayout ?>/js/markdown.js"></script>
    <script type="text/javascript" src="<?= WEBROOT ?>templates/<?= $themeForLayout ?>/js/popover.js"></script>
    <script type="text/javascript" src="<?= WEBROOT ?>inc/js/all.min.js"></script>
    <script type="text/javascript" src="<?= WEBROOT ?>inc/js/prism.js"></script>
    <script type="text/javascript" src="<?= WEBROOT ?>inc/js/choices.js"></script>
    <script type="text/javascript" src="<?= WEBROOT ?>templates/<?= $themeForLayout ?>/js/jquery.hotkeys.js"></script>
    <script type="text/javascript" src="<?= WEBROOT ?>inc/js/krajee-markdown-editor/js/markdown-editor.js"></script>
    <script type="text/javascript" src="<?= WEBROOT ?>inc/js/krajee-markdown-editor/js/locales/fr.js"></script>
    <script type="text/javascript" src="<?= WEBROOT ?>inc/js/krajee-markdown-editor/plugins/markdown-it/markdown-it.min.js"></script>
 

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
    <!-- flash -->
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

</body>

</html>
