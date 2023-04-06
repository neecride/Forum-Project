<div class="row">
    <?php 
    $activeWidget = 1;
    if($activeWidget == 1): ?>
        <div class="col-md-9">
    <?php elseif($activeWidget == 0): ?>
        <div class="col-md-12">
    <?php endif ?>
        <div class="row">
            <div class="col-md-12">
                <div id="home" class="section-title">
                    <h5>Annonces - TOPIC PERTINENT</h5>
                </div>
            </div>
        </div>
        <div class="anime__details__review">
            <div class="row row-cols-1 row-cols-md-3">
                <?php 
                if($home->homePage() != null): ?>
                <?php foreach ($home->homePage() as $posts): ?>
                    <div class="col-md-12 mb-3">
                        <div class="card">
                            <div class="card-header">
                                <h6>
                                    <i style="font-size:15px;" class="fas fa-thumbtack"></i>
                                    <a href="<?= $router->routeGenerate('viewtopic',['id' => $posts->topicid]) ?>">
                                        <?= $Parsing->Renderline($app->trunque($posts->f_topic_name, 100)) ?>
                                    </a>
                                </h6>
                                <div class="tags float-right">
                                    <?php foreach($home->Tags($posts->topicid) as $tags): ?>
                                    <a class="F_small" href="<?= $router->routeGenerate('forum-tags', ['slug' => $tags->slug,'id' => $tags->tagid]) ?>">
                                        <?= $app->trunque($tags->name,15) ?>
                                    </a>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <div class="card-body">
                                <p><?= $Parsing->RenderText($posts->f_topic_content) ?></p>
                            </div>
                            <div class="card-footer">
                                <h6>Posté par - <?= $posts->username ?></h6> 
                                <span class="float-right"><i class="far fa-calendar-alt"></i>
                                    <small class="text-muted">
                                        &nbsp;&nbsp;<?= $GetParams->AppDate($posts->f_topic_date) ?>
                                    </small>
                                </span> 
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
    </div><!-- close content md-9 -->
    <!-- widget -->
    <?php $app->widget() ?>
    <!-- widget -->

</div><!-- close row -->

<?php 
if (isset($_SESSION['auth'])){ 
    if(in_array($_SESSION["auth"]->authorization, [2,3])){
?>
<div class="section-title">
    <h5>Créer un direct topic</h5>
</div>
<div class="card">
    <div class="card-body">
        Soyez correct et inspiré ne postez pas du vide <small class="text-muted">(merci)</small>
        <form id="creat" action="<?= $router->routeGenerate('creattopic') ?>" method="post">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                    <?= $Parsing->input('f_topic_name','text','titre du topic','required="required"') ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <select class="js-choice" name="tags[]" multiple>
                            <option value="">Choisissez vos tags</option>
                        <?php foreach($home->queryTags() as $tagL){ ?>
                            <option value="<?= $tagL->id ?>"><?= $tagL->name ?></option>
                        <?php } ?>
                        </select>
                    </div>
                </div>
            </div>
            <label for="">En savoir plus sur le <a href="https://fr.wikipedia.org/wiki/Markdown#Formatage">markdown</a></label>
            <div class="editor-area-forum">
                <?= $Parsing->MarkDownEditor('f_topic_content') ?>
                <input type="checkbox" name="sticky" value="1"> Sticky <small style="color:#912c1a;">(Personne ne pourra répondre a par les admin) </small>
            </div>
            <?= $session->csrfInput() ?>
            <button name="topics" type="submit" class="btn btn-danger mt-3">Envoyez <i class="fas fa-paper-plane"></i></button>
        </form>
    </div>
    <div class="card-footer"></div>
</div>
<?php 
        }
    } 
?>
<!-- Blog Section End -->