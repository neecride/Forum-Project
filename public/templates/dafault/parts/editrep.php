<?= $response->checkError() ?>
<div class="row">
  <div class="col-md-12">
    <div class="card">
      <div class="card-header">
        <h6><?= $response->getEditReponseReq()->f_rep_name ?></h6>
      </div>
      <div class="card-body">
        <form method="POST">
            <div class="editor-area-forum">
              <?= $Parsing->MarkDownEditor('f_topic_content', $response->getEditReponseReq()->f_topic_reponse) ?>
            </div>
            <button type="submit" name="topics" class="btn btn-danger mr-3 mt-3">Envoyez <i class="fas fa-paper-plane"></i></button>
            <?php $page = isset($_GET['page']) ? '?page='.$_GET['page'] : null ; ?>
            <a href="<?= $router->routeGenerate('viewtopic', ['id' => $response->getEditReponseReq()->f_topic_id . $page . '#rep-' . $match['params']['id']]) ?>" class="btn btn-link mt-3">Anuler</a>
            <?= $session->csrfInput(); ?>
        </form>
      </div>
    </div>
  </div>
</div>