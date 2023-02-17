<div class="row">
        <div class="col-md-12">
            <div class="section-title">
                <h5>Annonces - TOPIC PERTINENT</h5>
            </div>
        </div>
        <div class="col-md-12">
            <div class="row row-cols-1 row-cols-md-3">
                <?php if($home->rowcount() > 0): ?>
                <?php foreach ($home as $posts): ?>
                    <div class="col-md-12 mb-4">
                        <div class="card h-100">
                        <div class="card-header">
                            <span style="font-size:13px;"><i class="fas fa-thumbtack"></i></span>&nbsp;-&nbsp;<strong><?= $Parsing->Renderline(trunque($posts->f_topic_name, 100)) ?></strong>
                            <span class="float-right"><i class="far fa-calendar-alt"></i><small class="text-muted">&nbsp;&nbsp;Posté le&nbsp;:&nbsp;<?= $GetParams->AppDate($posts->f_topic_date) ?></small></span>
                            <div class="tags">
                                <?php foreach(Tags($posts->topicid) as $tags): ?>
                                <a class="F_small" href="<?= $router->generate('forum-tags', ['slug' => $tags->tagslug,'id' => $tags->tagid]) ?>">
                                    <?= $tags->name ?>
                                </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <div class="card-body">
                            <p class="card-text">
                                <?= $Parsing->RenderText($posts->f_topic_content) ?>
                            </p>
                        </div>
                        <div class="card-footer">
                            <?= isset($posts->avatar) && !empty($posts->avatar)
                                ? "<img src='" . WEBROOT . "inc/img/avatars/" . $posts->avatar . "' draggable='false' width='15px' height='15px' alt=''>"
                                : "<img src='" . WEBROOT . "inc/img/avatars/default.png' draggable='false' width='15px' height='15px' alt=''>"; ?>
                                <small class="text-muted">Posté par <?= $posts->username ?></small>
                                <a class="float-right" href="<?= $router->generate('viewtopic',['id' => $posts->topicid]) ?>">
                                    <i class="fas fa-arrow-right"></i>
                                </a> 
                        </div>
                        </div>
                    </div>
                <?php endforeach; ?>
                <?php else:?>
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-12"> 
                                <div class="card">
                                    <div class="card-body"><h4>Il n'y a pas d'annonce pour le moment</h4></div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

<?php 
if (isset($_SESSION['auth'])){ 
    
    if(in_array($_SESSION["auth"]->authorization, [2,3])){
?>


<div class="section-title">
    <h5>Créer un direct topic</h5>
</div>

 Soyez correct et inspiré ne postez pas du vide <small class="text-muted">(merci)</small>
    <form id="creat" action="<?= $router->generate('creattopic') ?>" method="post">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <input class="form-control HoTagsI" placeholder="Titre du topic" value="<?= isset($_POST['f_topic_name']) && !empty($_POST['f_topic_name']) ? $_POST['f_topic_name'] : "" ; ?>" id="f_topic_name" type="text" name="f_topic_name" required>
                    </div>
                </div>
                <div class="col-md-6">

                    <div class="form-group">
                        <select class="js-choice" name="tags[]" multiple>
                            <option value="">Choisissez vos tags</option>
                        <?php foreach($tagList as $tagL){ ?>
                            
                            <option value="<?= $tagL->id ?>"><?= $tagL->name ?></option>
    
                        <?php } ?>

                        </select>
                    </div>
                </div>
            </div>
            <label for="">En savoir plus sur le <a href="https://fr.wikipedia.org/wiki/Markdown#Formatage">markdown</a></label>
            <div class="editor-area-forum">
                <?= BootstrapMde('f_topic_content', isset($_POST['f_topic_name']) && !empty($_POST['f_topic_name']) ? $_POST['f_topic_name'] : $Parsing->JustDemo() ); ?>
                <?php if(in_array($_SESSION["auth"]->authorization, [2,3])): ?>
                    <input type="checkbox" name="sticky" value="1"> Sticky <small style="color:#912c1a;">(Personne ne pourra répondre a par les admin) </small>
                <?php endif; ?>
            </div>
        
            <?= csrfInput() ?>
            <button name="topics" type="submit" class="btn btn-danger mt-3">Envoyez <i class="fas fa-paper-plane"></i></button>

    </form>
<?php 

        }
    } 
?>
<!-- Blog Section End -->