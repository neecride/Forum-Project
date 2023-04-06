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
            <div class="col-xs-6 col-md-4">
              <div class="card h-100">
                <div class="card-header text-center">
                  <h6>Tags</h6>
                </div>
                <div class="card-body text-center">
                  <a style="font-size:xxx-large;" href="<?= $router->routeGenerate('tags') ?>">
                    <i class="fas fa-tag"></i> 
                  </a>
                </div>
                <div class="card-footer"></div>
              </div>
            </div>

            <div class="col-xs-6 col-md-4">
              <div class="card h-100">
                <div class="card-header text-center">
                  <h6>Ajoutez des tags</h6>
                </div>
                <div class="card-body text-center">
                  <a style="font-size:xxx-large;" href="<?= $router->routeGenerate('tags-add') ?>">
                    <i class="fas fa-tags"></i> 
                  </a>

                </div>
                <div class="card-footer"></div>
              </div>

            </div>
            <div class="col-xs-6 col-md-4">
              <div class="card h-100">
                <div class="card-header text-center">
                  <h6>Membres</h6>
                </div>
                <div class="card-body text-center">
                  <a style="font-size:xxx-large;" href="<?= $router->routeGenerate('user') ?>">
                    <i class="fas fa-users"></i> 
                  </a>
                </div>
                <div class="card-footer"></div>
              </div>
            </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header">
              <h6>Paramètres global</h6>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-md-12 mt-3">
                    <form method="post">
                        <div class="form-group">
                          <label for="siteName">Titre du slogan</label>
                          <input id="siteName" type="text" class="form-control Hoinput" value="<?= $_POST['siteName'] ?? $GetParams->GetParam(0,'param_name'); ?>" placeholder="<?= $GetParams->GetParam(0,'param_name'); ?>" name="siteName">
                          <small class="text-muted">Le titre peut contenir 50 caractère max (accent compris et espaces) et des (,.!?)</small>
                        </div>
                        <div class="form-group">
                          <label for="siteSlogan">Contenue du slogan </label>
                          <div class="editor-area-forum">
                              <?= $Parsing->MarkDownEditor('siteSlogan', $GetParams->GetParam(0 , 'param_value')) ?>
                          </div>
                          <small class="text-muted">Le slogan peut contenir entre 30 et 300 caractères</small>
                          <?= $session->csrfInput(); ?>
                        </div>
                        <button type="submit" name="btnSiteSlogan" class="btn btn-danger btn-block">Editer</button>
                    </form> 
                    <hr class="mb-3 mt-4">
                </div>
              <div class="col-md-4 mt-3">
                  <form method="post">
                      <div class="form-group">
                      <label for="">Nombre de topic par page</label>
                      <select id="forumpager" class="form-control Hoinput" name="forumpager">
                          <option <?= !empty($GetParams->GetParam(2) == 10) ? 'selected="selected"' : '' ; ?> value="10">10</option>
                          <option <?= !empty($GetParams->GetParam(2) == 15) ? 'selected="selected"' : '' ; ?> value="15">15</option>
                          <option <?= !empty($GetParams->GetParam(2) == 20) ? 'selected="selected"' : '' ; ?> value="20">20</option>
                      </select>
                      <small class="text-muted">Réglage actuel (<span style="color:#dc3545;"><?= $GetParams->GetParam(2); ?></span>)</small>
                      <?= $session->csrfInput(); ?>
                      </div>
                      <button type="submit" name="btnTopicPerPage" class="btn btn-danger btn-block">Editer</button>
                  </form>
              </div>
              <div class="col-md-4 mt-3">
                  <form method="post">
                      <div class="form-group">
                          <label for="theme_name">Choisissez un thème</label>
                          <select id="theme_name" class="form-control Hoinput form-control" name="themeforlayout">
                              <?= $Parsing->checkFilesOptions($GetParams->GetParam(3)) ?>
                          </select>
                          <small>Theme actuel - <span style="color:#dc3545;"><?= !empty($GetParams->GetParam(3)) ? '('.$GetParams->GetParam(3).')' :'(Aucun)'; ?></span></small>
                          <?= $session->csrfInput(); ?>
                      </div>
                      <button type="submit" name="btnThemeName" class="btn btn-danger btn-block">Editer</button>
                  </form>
              </div>
              <div class="col-md-4 mt-3">
                    <form method="post">
                      <div class="form-group">
                        <label for="activSlogan">Acitivation du block slogan</label>
                          <select id="activSlogan" class="form-control Hoinput" name="activSlogan">
                              <option <?= !empty($GetParams->GetParam(0,'param_activ') == 'oui') ? 'selected="selected"' : '' ; ?> value="oui">oui</option>
                              <option <?= !empty($GetParams->GetParam(0,'param_activ') == 'non') ? 'selected="selected"' : '' ; ?> value="non">non</option>
                          </select>
                        <small class="text-muted">Réglage actuel (<span style="color:#dc3545;"><?= $GetParams->GetParam(0,'param_activ') ?></span>)</small>
                        <?= $session->csrfInput(); ?>
                      </div>
                      <button type="submit" name="btnBlockSlogan" class="btn btn-danger btn-block">Editer</button>
                  </form>
                </div>
              </div><!-- row -->
            </div>
            <div class="card-footer"></div>
          </div>
        </div>
        <div class="col-xs-6 col-md-12 mt-3">
              <div class="card">
                <div class="card-header" id="accordion"><h6>Parametre du widget alert</h6></div>
                  <div class="card-body">
                    <div class="row">
                      <div class="col-md-6 mt-3 mb-3">
                          <form method="post">
                              <div class="form-group">
                              <label for="activAlert">Acitivation de l'alert</label>
                              <select id="activAlert" class="form-control Hoinput" name="activAlert">
                                  <option <?= !empty($GetParams->GetParam(4,'param_activ') == 'oui') ? 'selected="selected"' : '' ; ?> value="oui">oui</option>
                                  <option <?= !empty($GetParams->GetParam(4,'param_activ') == 'non') ? 'selected="selected"' : '' ; ?> value="non">non</option>
                              </select>
                              <small class="text-muted">Réglage actuel (<span style="color:#dc3545;"><?= $GetParams->GetParam(4,'param_activ') ?></span>)</small>
                              <?= $session->csrfInput(); ?>
                              </div>
                              <button type="submit" name="btnActivAlert" class="btn btn-danger btn-block">Editer</button>
                          </form>
                      </div>
                      <div class="col-md-6 mt-3 mb-3">
                          <form method="post">
                              <div class="form-group">
                              <label for="alertColor">Couleur de l'alert</label>
                              <select id="alertColor" class="form-control Hoinput" name="alertColor">
                                  <option <?= !empty($GetParams->GetParam(4, 'param_color') == 'turquoise') ? 'selected="selected"' : '' ; ?> value="turquoise">turquoise</option>
                                  <option <?= !empty($GetParams->GetParam(4, 'param_color') == 'jaune') ? 'selected="selected"' : '' ; ?> value="jaune">jaune</option>
                                  <option <?= !empty($GetParams->GetParam(4, 'param_color') == 'gris') ? 'selected="selected"' : '' ; ?> value="gris">gris</option>
                                  <option <?= !empty($GetParams->GetParam(4, 'param_color') == 'rouge') ? 'selected="selected"' : '' ; ?> value="rouge">rouge</option>
                                  <option <?= !empty($GetParams->GetParam(4, 'param_color') == 'orange') ? 'selected="selected"' : '' ; ?> value="orange">orange</option>
                                  <option <?= !empty($GetParams->GetParam(4, 'param_color') == 'marine') ? 'selected="selected"' : '' ; ?> value="marine">marine</option>
                                  <option <?= !empty($GetParams->GetParam(4, 'param_color') == 'bleu') ? 'selected="selected"' : '' ; ?> value="bleu">bleu</option>
                                  <option <?= !empty($GetParams->GetParam(4, 'param_color') == 'violet') ? 'selected="selected"' : '' ; ?> value="violet">violet</option>
                                  <option <?= !empty($GetParams->GetParam(4, 'param_color') == 'vert') ? 'selected="selected"' : '' ; ?> value="vert">vert</option>
                              </select>
                              <small class="text-muted">Réglage actuel (<span style="color:#dc3545;"><?= $GetParams->GetParam(4,'param_color') ?></span>)</small>
                              <?= $session->csrfInput(); ?>
                              </div>
                              <button type="submit" name="btnAlertColor" class="btn btn-danger btn-block">Editer</button>
                          </form>
                      </div>
                    </div>
                    <hr>
                    <div class="row">
                      <div class="col-md-12 mt-3">
                        <form method="post">
                            <div class="form-group">
                              <label for="alertTitle">Titre de l'alert</label>
                              <input id="alertTitle" type="text" class="form-control Hoinput" value="<?= $_POST['alertTitle'] ?? $GetParams->GetParam(4,'param_name'); ?>" placeholder="<?= $GetParams->GetParam(4,'param_name'); ?>" name="alertTitle">
                              <small class="text-muted">Le titre peut contenir 50 caractère max (accent compris et espaces) et des (,.!?)</small>
                            </div>
                            <div class="form-group">
                              <label for="alertContent">Contenue de l'alert</label>
                              <div class="editor-area-forum">
                                  <?= $Parsing->MarkDownEditor('alertContent', $GetParams->GetParam(4),'editor2') ?>
                              </div>
                              <small class="text-muted">Le message d'alert peut contenir entre 30 et 500 caractères</small>
                              <?= $session->csrfInput(); ?>
                            </div>
                            <button type="submit" name="btnAlertForm" class="btn btn-danger btn-block">Editer</button>
                        </form>
                    </div>
                    </div>
                  </div>
                <div class="card-footer"></div>
              </div>

        </div>
      </div><!-- row end -->
  </div>
</div>
<?= $Response->checkError() ?>
<!-- Blog Section End -->