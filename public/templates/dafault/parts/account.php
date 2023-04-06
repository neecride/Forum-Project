<?php require_once RACINE.DS.'public'.DS.'templates'.DS.$GetParams->themeForLayout().DS.'parts'.DS.'widgets'.DS.'top'.DS.'top-account.php'; ?>
<div class="row">
    <div class="col-md-6">
        <div class="section-title">
            <h5>Vos infos</h5>
        </div>
        <div class="card">
            <div class="card-body">
                <p><strong>Votre role sur ce site</strong> : <?= $user->userAccount()->slug ?></p>
                <p><strong>Date inscription</strong> : <?= $GetParams->AppDate($user->userAccount()->date_inscription); ?></p>
                <p><strong>Site WEB</strong> : <a href="<?= $user->userAccount()->userurl; ?>" target="_blank"><i class="fas fa-globe"></i></a></p>
                <p><strong>Votre eMail</strong> : <a href="mailto:<?= $user->userAccount()->email; ?>"><i class="fas fa-at"></i></a></p>
                <form method="post">
                    <?= $session->csrfInput(); ?>
                    <?php if($_SESSION['auth']->id != 1){ ?>
                    <hr>
                    <h4>Cette action est définitive</h4>
                    <br>
                    <button type="submit" name="lock-account" class="btn btn-block btn-danger" onclick="return confirm('Sur de sur ?');">
                        Desactiver votre compte
                    </button>
                    <?php } ?>
                </form>
            </div>
            <div class="card-footer"></div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6">
            <div class="row">
                <div class="col-md-12">
                <div id="survey" class="section-title">
                    <h5>
                    Suivis de vos topic
                    <?php if (isset($_SESSION['auth'])) : ?>
                        <a href="<?= $router->routeGenerate('creattopic') ?>" data-toggle="tooltip" data-placement="top" title="Créer un topic">
                            <i class="fas fa-plus-circle"></i>
                        </a>
                        <?php endif; ?>
                    </h5>
                </div>
                </div>
            </div>
            <div class="anime__details__review">
                <?php
                if($forum->accountLastTopic() != null):
                foreach ($forum->accountLastTopic() as $posts):
                $rep = $forum->viewLastReponse($posts->topicid);
                $count = $forum->CountRep($posts->topicid);
                ?>
                    <div class="card mb-2 child">
                        <div class="anime__review__item__text">
                            <!-- on change l'image dès qu'un topic est vu -->
                            <div class="col-md-1">
                                <div class="anime__review__item__pic">
                                    <?= $forum->renderAvatar($posts->avatar) ?>
                                    <?= $forum->hot($count->countid,20) ?>
                                    <?= $forum->isNew($posts->read_last, $posts->Lastdate) ?>
                                </div>
                            </div>
                            <div class="F_corps col-md-10">
                                <div>
                                    <span>
                                    <a href="<?= $router->routeGenerate('viewtopic', ['id' => $posts->topicid.'#topic-'.$posts->topicid]) ?>">
                                    <?= ($posts->sticky == 1) ? '<i style="font-size:11px;" class="fas fa-thumbtack"></i>&nbsp;' : null ; ?>
                                    <?= $Parsing->Renderline($app->trunque($posts->f_topic_name,40)) ?>
                                    </a>
                                    <?= ($posts->topic_lock == 1) ? '&nbsp;<i style="font-size:11px;" title="Sujet résolu" class="fas fa-check-circle"></i>' : null ; ?>
                                    </span>
                                </div>
                                <small class="text-muted">Poster par <?= $posts->username ?></small> 
                                <?php if (!isset($rep->f_topic_rep_date)) { //si pas de réponse  ?>
                                    <div class="F_foot">
                                    <span>
                                        posté le, <?= $GetParams->AppDate($posts->f_topic_date) ?>
                                    </span>
                                    </div>
                                <?php } else { //si on a une réponse ?>
                                    <div class="F_foot">
                                    <a href="">
                                    <?= $forum->renderAvatar($rep->avatar) ?>
                                    </a> <small class="fa fa-share" aria-hidden="true"></small>
                                    <span>
                                        <a href="<?= $pagination->userLinkPage($posts->topicid, $rep->idrep,$count->countid) ?>">
                                        <?= $rep->username ?></a> - réponse reçu le, <?= $GetParams->AppDate($rep->f_topic_rep_date) ?> 
                                    </span>
                                        <!-- nombre de page par topic -->
                                        <?= $pagination->tinyLinkPage($posts->topicid,$count->countid) ?>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
                <?php else: ?>
                    <div class="card mb-2 child">
                    <div class="card-body">
                        <h3>Pas de topic pour le moment</h3>
                    </div>
                    </div>
                <?php endif; ?>  
                <!-- last -->
            </div>
        </div>
        <div class="col-md-6">
                <div class="section-title">
                    <h5>Modifiez votre Mail</h5>
                </div>
                <div class="card mb-3">
                    <div class="card-body">
                        <form method="post" id="mail" action="">
                        <div class="form-group">
                            <label for="email">Changer votre Email</label>
                            <input type="email" name="email" class="Hoinput form-control" placeholder="email" value="<?= isset($_POST['email'] ) ? $_POST['email'] : $user->userAccount()->email ?>" id="email" require>
                        </div>
                        <div class="form-group">
                            <label for="email_confirm">Confirmation</label>
                            <input type="email" name="emailConfirm" class="Hoinput form-control" placeholder="email confirmation" value="<?= isset($_POST['emailConfirm'] ) ? $_POST['emailConfirm'] : $user->userAccount()->email ?>" id="email_confirm" require>
                        </div>
                        <button type="submit" name="edit-email" value="edit-email" class="btn btn-block btn-danger">Modifier votre email</button>
                        <?= $session->csrfInput(); ?>
                        </form>
                    </div>
                    <div class="card-footer"></div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="section-title">
                    <h5>Modifiez votre mots de pass</h5>
                </div>
                <div class="card">
                    <div class="card-body">
                        <form method="post" id="pass" action="">
                            <div class="form-group password-toggle">
                                <label for="password">Changez mots de pass</label>
                                <input type="password" name="password" class="Hoinput form-control" placeholder="password" id="password" required>
                                <span id="passwd"></span>
                            </div>
                            <div class="form-group">
                                <label for="password_confirm">Répétez mots de pass</label>
                                <input type="password" name="password_confirm" class="Hoinput form-control" placeholder="password confirmation" id="password_confirm" required>
                                <span id="passwdc"></span>
                            </div>
                            <button type="submit" name="pwd" class="btn btn-block btn-danger">Confirmer votre nouveau password</button>
                            <?= $session->csrfInput(); ?>
                        </form>
                    </div>
                    <div class="card-footer"></div>
                </div>
            </div>
            <div class="col-md-12 mb-3">
                <div class="card">
                    <div class="card-header">
                        <h6>
                            Bio
                        </h6>
                    </div>
                    <div class="card-body">
                        <form method="post" id='comment'>
                            <div class="form-group">
                                <label for="description">Une petite signature visible sur le forum <small class="text-muted">(Optionel)</small></label>
                                <div class="editor-area-forum">
                                    <?= $Parsing->MarkDownEditor('description', $user->userAccount()->description); ?>
                                </div>
                                <span class="help-block">Votre description ne dois pas dépasser 200 caractères</span>
                            </div>
                
                            <button type="submit" name="edit-profil" class="btn btn-sm btn-danger">Confirmer l'édition de votre profil</button>
                            <?= $session->csrfInput(); ?>
                        </form>
                    </div>
                    <div class="card-footer"></div>
                </div>
            </div>
</div>