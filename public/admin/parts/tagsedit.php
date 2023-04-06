<?= $tags->checkError() ?>
<div class="row">
    <div class="col-md-12">
        <div class="row">
          <div class="col-md-12">
            <div class="section-title">
              <h5>
                Administration
              </h5>
            </div>
          </div>
        </div>
        <div class="anime__details__review">
      <div class="row">
        <div class="col-xs-6 col-md-12">
          <div class="card">
            <div class="card-header">
              <h6><?= ($match['name'] == "tags-edit") ? "Edition du tags" : "Ajouté un tag" ; ?></h6>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-md-12 mb-2">
                  <span>Rappel ordre des tags <small>(cliquez pour editer)</small></span>
                  <?php
                      foreach($tags->getTags() as $tagsOrder){
                  ?>
                    <div class="tags">
                        <a class="F_small" href="<?= $router->routeGenerate('tags-edit',['editid' => $tagsOrder->id, 'getcsrf' => $session->csrf()]) ?>">
                          <?= $tagsOrder->name.'&nbsp;-&nbsp;'.$tagsOrder->ordre ?>                    
                        </a>
                    </div>
                  <?php } ?>
                </div>
              </div>
              <hr>
              <form action="" method="post" enctype="multipart/form-data">
              <div class="row"> 
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="name">Nom du tags</label>
                        <input class="form-control Hoinput" placeholder="Nom du tags" id="name" value="<?= !empty($tags->getTag()->name) ? $tags->getTag()->name : null; ?>" type="text" name="name" />
                        <p class="help-block">Au moins 3 cractères requis et moins de 100 !</p>
                    </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label for="ordre">Ordre du tags</label>
                    <input class="form-control Hoinput" placeholder="Odre du tags" id="ordre" value="<?= !empty($tags->getTag()->ordre) ? $tags->getTag()->ordre : null; ?>" type="text" name="ordre" />
                    <p class="help-block">Un chiffre entre 1 et l'infinie !</p>
                  </div>
                </div>
              </div>
                  <?= $session->csrfInput(); ?>

                  <?php if(isset($match['params']['editid'])){ ?>
                      <button type="submit" name="tagEdit" class="btn btn-danger">Editer un tags</button>
                  <?php
                  }else{
                  ?>
                      <button type="submit" name="tagAdd" class="btn btn-danger">Ajouter un tags</button>
                  <?php
                  }
                  ?>

                  <a href="<?= $router->routeGenerate('tags') ?>" class="btn btn-link">Anuler</a>

            </form>

            </div>
          </div>
        </div>
      </div>
  </div>
</div>
</div>
<!-- Blog Section End -->