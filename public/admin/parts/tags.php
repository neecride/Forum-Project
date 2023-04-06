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
              <h6>Gestion des Tags</h6>
            </div>
            <div class="card-body">
              <table class="table table-striped">
                  <thead>
                      <tr>
                          <th>ID</th>
                          <th>Nom</th>
                          <th>Slug</th>
                          <th class="text-center">Actions</th>
                      </tr>
                  </thead>
                  <tbody>
                      <?php
                      foreach($tags->getTags() as $tags){
                      ?>
                      <tr>
                          <td><?= $tags->ordre ?></td>
                          <td><?= $tags->name ?></td>
                          <td><?= $tags->slug ?></td>
                          <td class="text-center">
                              <a href="<?= $router->routeGenerate('tags-edit',['editid' => $tags->id, 'getcsrf' => $session->csrf()]) ?>" style="padding:5px;" class="label label-warning" data-toggle="tooltip" data-placement="top" title="Editer">
                              <i class="fas fa-edit"></i>
                              </a>
                          </td>
                      </tr>
                      <?php } ?>
                  </tbody>
              </table>
                <a href="<?= $router->routeGenerate('admin') ?>" class="btn btn-danger">Retour index admin</a>
            </div>
          </div>
        </div>
      </div>
  </div>
</div>
</div>
<!-- Blog Section End -->