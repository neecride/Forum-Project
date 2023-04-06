<?= $Response->checkError(); ?>
<div class="row">
    <div class="col-md-12">
      <div id="viewtopic" class="section-title">
        <h5>
          Forum topics     
        <?php if (isset($_SESSION['auth'])) : ?>
          <a href="<?= $router->routeGenerate('creattopic') ?>" data-toggle="tooltip" data-placement="top" title="Créer un topic">
            <i class="fas fa-plus-circle"></i>
          </a>
        <?php endif; ?>
        </h5>
      </div>
    </div>
</div>
  <div id="topic-<?= $Response->firstTopic()->topicsid ?>" class="card mb-2">
    <div class="card-header">
      <h6>
      <?= !empty($Response->firstTopic()->sticky) == 1 ? '<i style="font-size:15px;" class="fas fa-thumbtack"></i>&nbsp;' : '' ?>
      <?= $Parsing->Renderline($app->trunque($Response->firstTopic()->f_topic_name, 80)); ?>
      </h6>
      <div class="tags float-right">
          <?php 
            foreach($forum->Tags($Response->firstTopic()->topicsid) as $tags): 
          ?>
            <a class="F_small" href="<?= $router->routeGenerate('forum-tags', ['slug' => $tags->slug,'id' => $tags->tagid]) ?>">
                <?= $app->trunque($tags->name,15) ?>
            </a>
          <?php endforeach; ?>
      </div>
    </div>
    <div class="card-body">
      <div class="media forum-item"> 
        <div class="card-link anime__review__item__pic_topic"> 
          <?= $forum->renderAvatar($Response->firstTopic()->avatar) ?>
        </div>
          <div class="media-body query-content hr-col-ava ml-3"> 
              <div class="font-size-sm Topic_colorperso Topic_alignperso">
                <?= $Parsing->RenderText($Response->firstTopic()->f_topic_content) ?>
              </div>
              <?php if(isset($Response->firstTopic()->description) && !empty($Response->firstTopic()->description)): ?>
                <div class="mt-3 pt-3 hr-sign font-size-sm signature">
                  <p><?= $Parsing->RenderText($Response->firstTopic()->description); ?></p>
                </div>
              <?php endif; ?>
          </div>
        </div>
      </div>
      <div class="card-footer">
            <h6 class="p-2">Auteur - <?= $Response->firstTopic()->username ?></h6>
            <?= $Response->editBtnTopic() ?>
            <?= $Response->resolvedStatusBtn() ?>
            <?= $Response->stickyStatusBtn() ?>
            <span class="float-right">
              <i class="far fa-calendar-alt"></i><small class="text-muted">&nbsp;&nbsp;<?= $GetParams->AppDate($Response->firstTopic()->f_topic_date); ?></small>
            </span>
      </div>
    </div>
    <!-- pagination -->
      <div class="page">
        <div class="row mb-3 mt-3">
          <div class="col-md-12">
            <nav>
              <ul class="pagination pagination-sm">
                <?php $pagination->pageFor() ?>
              </ul>
            </nav>
          </div>
        </div>
      </div>
      <!-- pagination -->
<?php
foreach ($Response->viewTopicRep() as $reps) {
?>
    <div id="rep-<?= $reps->topicsrep ?>" class="card mb-3">
      <div class="card-header">
        <h6>Re - <?= $Parsing->Renderline($app->trunque($Response->firstTopic()->f_topic_name, 80)); ?></h6>
        <div class="tags float-right">
            <?php foreach($forum->Tags($Response->firstTopic()->topicsid) as $tags): ?>
            <a class="F_small" href="<?= $router->routeGenerate('forum-tags', ['slug' => $tags->slug,'id' => $tags->tagid]) ?>">
                <?= $app->trunque($tags->name, 15) ?>
            </a>
            <?php endforeach; ?>
        </div>
      </div>
      <div class="card-body">
        <div class="media forum-item"> 
          <div class="anime__review__item__pic_topic"> 
              <?= $forum->renderAvatar($reps->avatar) ?> 
          </div>
            <div class="media-body query-content hr-col-ava ml-3"> 
                <div class="font-size-sm Topic_colorperso Topic_alignperso">
                  <?= $Parsing->RenderText($reps->f_topic_reponse) ?>
                </div>
                <?php if(isset($reps->description) && !empty($reps->description)): ?>
                <div class="mt-3 pt-3 hr-sign font-size-sm signature">
                  <?= $Parsing->RenderText($reps->description) ?>
                </div>
                <?php endif ?>
            </div>
          </div>
        </div>
        <div class="card-footer">
            <h6 class="p-2">Auteur - <?= $reps->username ?></h6>
            <?= $Response->editBtnResponse($reps->usersrep, $reps->authorization,$reps->topicsrep) ?>
            <span class="float-right"><i class="far fa-calendar-alt"></i><small class="text-muted">&nbsp;&nbsp;<?= $GetParams->AppDate($reps->rep_date); ?></small></span>
        </div>
    </div>  
  <?php } ?>
<!-- pagination -->
      <div class="row mb-3 mt-3">
        <div class="col-md-12">
          <div class="page">
            <nav>
              <ul class="pagination pagination-sm">
                <?php $pagination->pageFor() ?>
              </ul>
            </nav>
          </div>
        </div>
      </div>
<!-- pagination -->
  <!--


  editeur


  -->
  <?php if(isset($_SESSION['auth']->id)) { ?>
    <?php 
    if($Response->firstTopic()->topic_lock == 0 && $Response->firstTopic()->sticky == 0 or in_array($_SESSION["auth"]->authorization, [2,3]) or $_SESSION['auth']->id == $Response->firstTopic()->usersid): 
    ?>
    <div class="card">
      <div class="card-header"><h6>Ecrire une réponse</h6></div>
      <div class="card-body">
        <form method="POST">
          <div class="editor-area-forum mt-3">
              Soyez correct et inspiré ne postez pas du vide <small class="text-muted">(merci)</small>
              <?= $Parsing->MarkDownEditor('f_topic_content'); ?>
          </div>
              <button type="submit" name="topics" class="btn btn-danger mt-3">
                Envoyez <i class="fas fa-paper-plane"></i>
              </button>
              <?= $session->csrfInput() ?>
        </form>
      </div>
      <div class="card-footer"></div>
    </div>
    <?php else: ?>
      <?php if($Response->firstTopic()->topic_lock == 1): ?>
        <div class="alert alert-warning">Le topic est résolue</div>
      <?php elseif($Response->firstTopic()->sticky == 1): ?>
        <div class="alert alert-warning">Vous ne pouvez pas répondre aux annonces</div>
      <?php endif; ?>
    <?php endif; ?>

  <?php } else { ?>
            <div class="alert alert-warning">Il faut être connecter pour créer une réponse</div>
  <?php } ?>