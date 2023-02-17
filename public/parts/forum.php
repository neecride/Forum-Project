<div class="row">
<div class="col-lg-9 col-md-9">
  <div class="row">
    
    <div class="col-md-6">
      <div class="section-title">
        <h5>
          forums tags
        </h5>
      </div>
    </div>
    <div class="col-md-6 Btncreat">
      <?php if (isset($_SESSION['auth'])) : ?>
          <a href="<?= $router->generate('creattopic') ?>" class="btn btn-danger Btncreat float-right">Créer un sujet <i class="fas fa-plus-circle"></i></a>
      <?php endif; ?>
    </div>
  </div>
  <div class="Holink anime__details__review">
    <?php
      if($topic->rowcount() >= 1):
      foreach ($topic as $posts):
      $rep = LastReponse($posts->topicid);
      $count = CountRep($posts->topicid);
      $tt = ceil($count->countid/$PerPage);
    ?>
        <div class="card F_radius mb-2 child">
          <div class="anime__review__item__text">
            <!-- on change l'image dès qu'un topic est vu -->
            <div class="col-md-1">
              <div class="<?= isset($posts->read_last) && !empty($posts->read_last > $posts->Lastdate) ? 'anime__review__item__pic' : 'anime__review__item__pic_view' ; ?>">
                <?= isset($posts->avatar) && !empty($posts->avatar)
                  ? "<img src='" . WEBROOT . "inc/img/avatars/" . $posts->avatar . "' draggable='false' alt=''>"
                  : "<img src='" . WEBROOT . "inc/img/avatars/default.png' draggable='false' alt=''>"; ?>
                <?php if($count->countid >= 10): ?>
                  <div class="hot" title="Sujet brulant"></div>
                <?php endif; ?>
              </div>
            </div>
            <div class="F_corps col-md-6">
              <div>
                <span>
                  <a href="<?= $router->generate('viewtopic', ['id' => $posts->topicid]) ?>">
                   <?= !empty($posts->sticky) ? '<i style="font-size:11px;" class="fas fa-thumbtack"></i>&nbsp;' : '' ; ?><?= $Parsing->Renderline(trunque($posts->f_topic_name,40)) ?>
                  </a>
                  <?= !empty($posts->topic_lock) && $posts->topic_lock == 1 ? '&nbsp;<i style="font-size:11px;" title="Sujet résolu" class="fas fa-check-circle"></i>' : '' ; ?>
                </span>
              </div>
              <?php if (!isset($rep->f_topic_rep_date)) { //si pas de réponse  ?>
                <div class="F_foot">
                  <span>
                    <a href="<?= $router->generate('viewtopic', ['id' => $posts->topicid]) ?>">
                      <?= $posts->username ?></a>, posté le&nbsp;<?= $GetParams->AppDate($posts->f_topic_date) ?>
                  </span>
                </div>
              <?php } else { //si on a une réponse ?>
                <div class="F_foot">
                  <a href="">
                    <?= isset($rep->avatar) && !empty($rep->avatar)
                      ? "<img src='" . WEBROOT . "inc/img/avatars/" . $rep->avatar . "' draggable='false' alt=''>"
                      : "<img src='" . WEBROOT . "inc/img/avatars/default.png' draggable='false' alt=''>"; ?>
                  </a> <small class="fa fa-share" aria-hidden="true"></small>
                  <span>
                    <?php 
                    //envoie l'utilisateur vers la bonne réponse sur la bonne page
                    if($PerPage >= $tt){
                      if($tt == 1){
                        $pp =  $router->generate('viewtopic', ['id' => $posts->topicid . '#rep-' . $rep->idrep]);
                      }else{
                        $pp =  $router->generate('viewtopic', ['id' => $posts->topicid . '?page='.$tt.'#rep-' . $rep->idrep]);
                      }
                    }
                    ?>
                    <a href="<?= $pp ?>">
                      <?= $rep->username ?></a>, réponse reçu le&nbsp;<?= $GetParams->AppDate($rep->f_topic_rep_date) ?> 
                  </span>
                  <!-- nombre de page par topic -->
                  <div class="uri">
                    <?php 
                    if($tt > 1):
                      for($ii=1; $ii <= $tt; $ii++): 
                        if($ii == 1): 
                    ?>
                        <a href="<?= $router->generate('viewtopic',['id' => $posts->topicid]) ?>"><?= $ii ?></a>
                    <?php else: ?>
                        <a href="<?= $router->generate('viewtopic',['id' => $posts->topicid]) ?>?page=<?= $ii ?>"><?= $ii ?></a>
                    <?php 
                        endif; 
                      endfor; 
                    endif;
                    ?>
                  </div>
                </div>
              <?php } ?>
            </div>
            <div class="F_user_info col-md-3">
              <div class="flags">
                <?php foreach (Tags($posts->topicid) as $tags) : ?>
                  <div class="tags">
                    <a class="F_small" href="<?= $router->generate('forum-tags', ['slug' => $tags->slug, 'id' => $tags->tagid]) ?>">
                      <?= trunque($tags->name,15) ?>
                    </a>
                  </div>
                <?php endforeach; ?>
              </div>
            </div>
            <div class="F_pin col-md-2 max-pin-1">
              <small class="fa fa-comments"></small>&nbsp;<?= isset($rep->idrep) ? $count->countid : '0'; ?>
              <br>
              <small class="far fa-eye"></small> <?= $posts->f_topic_vu ?>
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
      <?php if($Count >= 1): ?><!-- pagination -->
      <div class="page">
        <nav>
          <ul class="pagination mb-3 mt-3 pagination-sm">
            <?php 
            if($CurrentPage > 1):
            ?>
            <?php 
              $link = $router->generate('forum');
              if($CurrentPage > 2) $link .= '?page=' . ($CurrentPage-1);
            ?>
              <li class="page-item">
                <a class="page-link" href="<?= $link ?>">
                  <i class="fas fa-angle-double-left"></i>
                </a>
              </li>
            <?php else: ?>
              <li class="disabled page-item">
                <a class="page-link">
                  <i class="fas fa-angle-double-left" ></i>
                </a>
              </li>
            <?php endif; ?>
            <?php 
              $nb=2;
              for($i=1; $i <= $pages; $i++){
                if($i <= $nb || $i > $pages - $nb ||  ($i > $CurrentPage-$nb && $i < $CurrentPage+$nb)){
                  if($i == $CurrentPage) {
                    echo '<li class="page-item active"><a class="page-link">'. $i .'</a></li>';
                  } elseif($i == 1) {
                    echo '<li class="page-item"><a class="page-link" href='.$router->generate('forum').'>'. $i .'</a></li>' ;
                  }else{
                    echo '<li class="page-item"><a class="page-link" href='.$router->generate('forum').'?page='.$i.'>'. $i .'</a></li>' ;
                  }
                }else{
                  if($i > $nb && $i < $CurrentPage-$nb){
                    $i = $CurrentPage - $nb;
                  }elseif($i >= $CurrentPage + $nb && $i < $pages-$nb){
                    $i = $pages - $nb;
                  }
                  echo '<li class="page-item"><a class="page-link" href='.$link.'>...</a></li>';
                }
              }
            ?>
            <?php if($CurrentPage < $pages): ?>
              <li class="page-item">
                <a class="page-link" href="<?= $router->generate('forum') ?>?page=<?= $CurrentPage+1 ?>">
                  <i class="fas fa-angle-double-right"></i>
                </a>
              </li>
            <?php else: ?>
              <li class="disabled page-item">
                <a class="page-link">
                  <i class="fas fa-angle-double-right"></i>
                </a>
              </li>
            <?php endif; ?>
          </ul>
        </nav>
      </div>
      <?php endif; ?><!-- pagination -->
  </div>
</div>
<!-- navigation -->
<?php include 'parts/navigation.php'; ?>
<!-- navigation -->
</div>
<!-- Blog Section End -->
