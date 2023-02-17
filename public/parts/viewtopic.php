<div class="row">
    <div class="col-md-6">
      <div class="section-title">
        <h5>
          Forum topics
        </h5>
      </div>
    </div>
    <div class="col-md-6 Btncreat">
      <?php 
        if (isset($_SESSION['auth'])){
          if($_SESSION['auth']->id == $topic->usersid or in_array($_SESSION["auth"]->authorization, [2,3])) { 
      ?>
          <?php if($topic->topic_lock == 0): ?>
            <a href="<?= $router->generate('lock',['id' => $topic->topicsid, 'lock' => 1, 'getcsrf' =>  csrf()]) ?>" class="btn btn-success Btncreat ml-3 float-right">Mettre en  résolu <i class="fas fa-lock-open"></i></a>
          <?php elseif($topic->topic_lock == 1): ?>
            <a href="<?= $router->generate('unlock',['id' => $topic->topicsid, 'lock' => 0, 'getcsrf' =>  csrf()]) ?>" class="btn btn-info Btncreat ml-3 float-right">Mettre en non résolu <i class="fas fa-lock"></i></a>
          <?php endif; ?>
      <?php 
          }
        } 
      ?> 
      <?php if (isset($_SESSION['auth'])) : ?>
          <a href="<?= $router->generate('creattopic') ?>" class="btn btn-danger Btncreat float-right">Créer un sujet <i class="fas fa-plus-circle"></i></a>
      <?php endif; ?>
    </div>
  </div>

  <div class="card mb-2">
    <div class="card-body">
      <div class="media forum-item"> 
        <span class="card-link anime__review__item__pic_topic"> 
          <?= isset($topic->avatar) && !empty($topic->avatar)
            ? "<img src='" . WEBROOT . "inc/img/avatars/" . $topic->avatar . "' draggable='false' alt='' />"
            : "<img src='" . WEBROOT . "inc/img/avatars/default.png' draggable='false' alt='' />"; ?>
          <small class="<?= rank($topic->slug) ?> d-block text-center text-secondary">
            <?= $topic->slug ?>
          </small> 
        </span>
          <div class="media-body ml-3" style="border-left: 1px solid #3c3d55; padding-left: 15px;"> 
              <?= !empty($topic->sticky) == 1 ? '<i style="font-size:11px;" class="fas fa-thumbtack"></i>&nbsp;' : '' ?>
                <a href="" class="text-secondary">
                  <?= isset($topic->username) && !empty($topic->username) ? htmlentities($topic->username) : 'userdelete'; ?>
                </a> 
                <div class="tags float-right">
                    <?php 
                      foreach(TagsLink($topic->topicsid) as $tags): 
                    ?>
                      <a class="F_small" href="<?= $router->generate('forum-tags', ['slug' => $tags->tagslug,'id' => $tags->tagid]) ?>">
                          <?= htmlentities($tags->name) ?>
                      </a>
                    <?php endforeach; ?>
                </div>
              <h5 class="mt-3">
                <?= $Parsing->Renderline(trunque($topic->f_topic_name, 80)); ?>
              </h5>
              <div class="mt-3 font-size-sm Topic_colorperso Topic_alignperso">
                <?= $Parsing->RenderText($topic->f_topic_content) ?>
              </div>
              <?php if(isset($topic->description) && !empty($topic->description)): ?>
                <div class="mt-3 pt-3 font-size-sm signature" style="border-top:1px solid #3c3d55;">
                  <?= $Parsing->RenderText($topic->description); ?>
                </div>
              <?php endif; ?>
          </div>
          
        </div>
        
      </div>
      <div class="card-footer HOfooter d-flex text-muted bd-highlight">
          <?php 
            if (isset($_SESSION['auth'])){
              if($_SESSION['auth']->id == $topic->usersid or in_array($_SESSION["auth"]->authorization, [2,3])) { 
          ?>
            <a class="p-2 bd-highlight" href="<?= $router->generate('editetopic', ['id' => $topic->topicsid]) ?>"><i class="far fa-edit"></i> Editer</a> 
            <?php 
              }
            } 
          ?>
            <?php if(isset($_SESSION['auth']->id) && in_array($_SESSION["auth"]->authorization, [2,3])): ?>
              <?php if(isset($topic->sticky) && !empty($topic->sticky) <= 0): ?>
                <a href="<?= $router->generate('sticky', ['id' => $topic->topicsid, 'sticky' => 1, 'getcsrf' =>  csrf()]) ?>" class="p-2 bd-highlight">
                  <i class="pin fas fa-thumbtack"></i>&nbsp;Mettre en sticky
                </a>
              <?php elseif((isset($topic->sticky) && !empty($topic->sticky) >= 1)): ?>
                <a href="<?= $router->generate('sticky', ['id' => $topic->topicsid, 'sticky' => 0, 'getcsrf' =>  csrf()]) ?>" class="p-2 bd-highlight">
                  <i class="pin fas fa-ban"></i>&nbsp;Retiré le sticky
                </a>
              <?php endif; ?>
            <?php endif; ?>

          <div class="ml-auto text-muted p-2 bd-highlight"> 
            <span><i class="far fa-calendar-alt"></i>&nbsp;<?= $GetParams->AppDate($topic->f_topic_date); ?></span>
          </div>  
      </div>
    </div>
    <?php if($Count >= 1): ?><!-- pagination -->
      <div class="page">
        <div class="row mb-3 mt-3">
          <div class="col-md-12">
            <nav>
              <ul class="pagination pagination-sm">
                <?php 
                if($CurrentPage > 1):
                ?>
                <?php 
                  $link = $router->generate('viewtopic', ['id' => $params['id']]);
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
                      } else if($i == 1) {
                        echo '<li class="page-item"><a class="page-link" href='.$router->generate('viewtopic', ['id' => $params['id']]).'>'. $i .'</a></li>' ;
                      }else{
                        echo '<li class="page-item"><a class="page-link" href='.$router->generate('viewtopic', ['id' => $params['id']]).'?page='.$i.'>'. $i .'</a></li>' ;
                      }
                    }else{
                      if($i > $nb && $i < $CurrentPage-$nb){
                        $i = $CurrentPage - $nb;
                      }elseif($i >= $CurrentPage + $nb && $i < $pages-$nb){
                        $i = $pages - $nb;
                      }
                      echo '<li class="page-item"><a class="page-link" href='.$router->generate('viewtopic', ['id' => $params['id']]).'?page='.($i-1) .'>...</a></li>';
                    }
                  }
                ?>
                <?php if($CurrentPage < $pages): ?>
                  <li class="page-item">
                    <a class="page-link" href="<?= $router->generate('viewtopic', ['id' => $params['id']]) ?>?page=<?= $CurrentPage+1 ?>">
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
        </div>
      </div>
      <?php endif; ?><!-- pagination -->
<?php
foreach ($response as $reps) {
?>
    <div id="rep-<?= $reps->topicsrep ?>" class="card mb-3">
    <div class="card-body">
      <div class="media forum-item"> 
        <span class="anime__review__item__pic_topic"> 
          <?= isset($reps->avatar) && !empty($reps->avatar)
            ? "<img src='" . WEBROOT . "inc/img/avatars/" . $reps->avatar . "' draggable='false' alt='' />"
            : "<img src='" . WEBROOT . "inc/img/avatars/default.png' draggable='false' alt='' />"; ?> 
          <small class="<?= rank($reps->slug) ?> d-block text-center text-secondary"><?= htmlentities($reps->slug) ?></small> 
        </span>
          <div class="media-body ml-3" style="border-left: 1px solid #3c3d55; padding-left: 15px;"> 
              <a href="" class="text-secondary">
                <?= isset($reps->username) && !empty($reps->username) ? htmlentities($reps->username) : 'userdelete' ?>
              </a> 
              <div class="tags float-right">
                  <?php foreach(TagsLink($topic->topicsid) as $tags): ?>
                  <a class="F_small" href="<?= $router->generate('forum-tags', ['slug' => $tags->tagslug,'id' => $tags->tagid]) ?>">
                      <?= $tags->name ?>
                  </a>
                  <?php endforeach; ?>
              </div>
              <h5 class="mt-3">
                Re - <?= $Parsing->Renderline(trunque($topic->f_topic_name, 80)); ?>
              </h5>
              <div class="mt-3 font-size-sm Topic_colorperso Topic_alignperso">
                 <?= $Parsing->RenderText($reps->f_topic_reponse) ?>
              </div>
              <?php if(isset($reps->description) && !empty($reps->description)): ?>
              <div class="mt-3 pt-3 font-size-sm signature" style="border-top:1px solid #3c3d55;">
                <?= $Parsing->RenderText($reps->description); ?>
              </div>
              <?php endif; ?>
          </div>
        </div>
      </div>
      <div class="card-footer HOfooter d-flex text-muted bd-highlight">
          <?php 
            if (isset($_SESSION['auth'])){
              if($_SESSION['auth']->id == $reps->usersrep or in_array($_SESSION["auth"]->authorization, [2,3])) { 
          ?>
          <a class="p-2 bd-highlight" href="<?= $router->generate('editerep', ['id' => $reps->topicsrep]) ?>"><i class="far fa-edit"></i> Editer</a>
          <?php 
              } 
            }
          ?>
          <div class="ml-auto text-muted p-2 bd-highlight"> 
            <span><i class="far fa-calendar-alt"></i>&nbsp;<?= $GetParams->AppDate($reps->rep_date); ?></span>
          </div>
      </div>
    </div>  
  <?php } ?>
    <?php if($Count >= 1): ?><!-- pagination -->
      <div class="row mb-3 mt-3">
        <div class="col-md-12">
          <div class="page">
            <nav>
              <ul class="pagination pagination-sm">
                <?php 
                if($CurrentPage > 1):
                ?>
                <?php 
                  $link = $router->generate('viewtopic', ['id' => $params['id']]);
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
                      } else if($i == 1) {
                        echo '<li class="page-item"><a class="page-link" href='.$router->generate('viewtopic', ['id' => $params['id']]).'>'. $i .'</a></li>' ;
                      }else{
                        echo '<li class="page-item"><a class="page-link" href='.$router->generate('viewtopic', ['id' => $params['id']]).'?page='.$i.'>'. $i .'</a></li>' ;
                      }
                    }else{
                      if($i > $nb && $i < $CurrentPage-$nb){
                        $i = $CurrentPage - $nb;
                      }elseif($i >= $CurrentPage + $nb && $i < $pages-$nb){
                        $i = $pages - $nb;
                      }
                      echo '<li class="page-item"><a class="page-link" href='.$router->generate('viewtopic', ['id' => $params['id']]).'?page='.($i-1) .'>...</a></li>';
                    }
                  }
                ?>
                <?php if($CurrentPage < $pages): ?>
                  <li class="page-item">
                    <a class="page-link" href="<?= $router->generate('viewtopic', ['id' => $params['id']]) ?>?page=<?= $CurrentPage+1 ?>">
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
        </div>
      </div>
      <?php endif; ?><!-- pagination -->
  <!--


  editeur


  -->
  <?php if(isset($_SESSION['auth']->id)) { ?>
    <?php 
    if($topic->topic_lock == 0 && $topic->sticky == 0 or in_array($_SESSION["auth"]->authorization, [2,3]) or $_SESSION['auth']->id == $topic->usersid): 
    ?>

    <form method="POST">
      <div class="editor-area-forum mt-3">
          <?= BootstrapMde('f_topic_content', isset($_POST['f_topic_name']) && !empty($_POST['f_topic_name']) ? $_POST['f_topic_name'] : $Parsing->JustDemo()); ?>
      </div>
          <button type="submit" name="topics" class="btn btn-danger mt-3">Envoyez <i class="fas fa-paper-plane"></i></button>
          <?= csrfInput() ?>
    </form>

    <?php else: ?>
      
      <?php if($topic->topic_lock == 1): ?>
        <div class="alert alert-warning">Le topic est résolue</div>
      <?php elseif($topic->sticky == 1): ?>
        <div class="alert alert-warning">Vous ne pouvez pas répondre aux annonces</div>
      <?php endif; ?>

    <?php endif; ?>

  <?php } else { ?>
            <div class="alert alert-warning">Il faut être connecter pour créer une réponse</div>
  <?php } ?>