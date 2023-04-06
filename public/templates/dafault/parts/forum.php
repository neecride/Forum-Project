<div class="row">
  <div class="col-lg-9 col-md-9">
    <div class="row">
        <div class="col-md-12">
          <div id="forum" class="section-title">
            <h5>
              forums       
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
        if($forum->homeForum() != null):
        foreach ($forum->homeForum() as $posts):
        $rep = $forum->viewLastReponse($posts->topicid);
        $count = $forum->CountRep($posts->topicid);
      ?>
          <div class="card mb-2 child">
            <div class="anime__review__item__text">
              <!-- on change l'image dès qu'un topic est vu -->
              <div class="col-md-1">
                <div class="anime__review__item__pic">
                  <?= $forum->renderAvatar($posts->avatar) ?>
                  <?= $forum->hot($count->countid,10) ?>
                  <?= $forum->isNew($posts->read_last, $posts->Lastdate) ?>
                </div>
              </div>
              <div class="F_corps col-md-6">
                <div>
                  <span>
                    <a href="<?= $router->routeGenerate('viewtopic', ['id' => $posts->topicid]) ?>">
                      <?= ($posts->sticky == 1) ? '<i style="font-size:11px;" class="fas fa-thumbtack"></i>&nbsp;' : null ; ?>
                      <?= $Parsing->Renderline($app->trunque($posts->f_topic_name,40)) ?>
                    </a>
                    <?= 
                    ($posts->topic_lock == 1) 
                    ? '&nbsp;<span data-toggle="tooltip" data-placement="right" title="Sujet résolu"><i style="font-size:11px;" class="fas fa-check-circle"></i></span>' 
                    : null ; 
                    ?>
                    
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
                      <a href="<?= $pagination->userLinkPage($posts->topicid,$rep->idrep,$count->countid) ?>">
                        <?= $rep->username ?></a> - réponse reçu le, <?= $GetParams->AppDate($rep->f_topic_rep_date) ?> 
                    </span>
                      <!-- nombre de page par topic -->
                      <?= $pagination->tinyLinkPage($posts->topicid,$count->countid) ?>
                  </div>
                <?php } ?>
              </div>
              <div class="F_user_info col-md-3">
                <div class="flags">
                  <?php foreach ($forum->Tags($posts->topicid) as $tags) : ?>
                    <div class="tags">
                      <a class="F_small" href="<?= $router->routeGenerate('forum-tags', ['slug' => $tags->slug, 'id' => $tags->tagid]) ?>">
                        <?= $app->trunque($tags->name,15) ?>
                      </a>
                    </div>
                  <?php endforeach; ?>
                </div>
              </div>
              <div class="F_pin col-md-2 max-pin-1">
                <small class="fa fa-comments"></small>&nbsp;<?= isset($rep->idrep) ? $count->countid : '0'; ?>
                <br>
                <i class="far fa-eye"></i>&nbsp;<?= $posts->f_topic_vu ?>
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
  <!-- pagination -->
        <div class="page">
          <nav>
            <ul class="pagination mb-3 mt-3 pagination-sm">
              <?php $pagination->pageFor() ?>
            </ul>
          </nav>
        </div>
        <!-- pagination -->
    </div>

</div><!-- close content md-9 -->
    <!-- widget -->
    <?php $app->widget() ?>
    <!-- widget -->
</div>
<!-- Blog Section End -->
