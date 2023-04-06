<?= $topic->checkError() ?>
<div class="row">
  <div class="col-md-12">
      <div class="section-title">
        <h5>
          Edit topic
        </h5>
      </div>
      <div class="card">
        <div class="card-header">
          <h6><?= $topic->firstTopic()->f_topic_name ?></h6>
        </div>
        <div class="card-body">
            <form method="post">
               <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                        <?= $Parsing->input('f_topic_name','text','Titre du topic','required="required"',$topic->firstTopic()->f_topic_name) ?>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <?php //var_dump( ) ?>
                    <div class="form-group">
                      <select class="js-choice" name="tags[]" multiple>
                          <option value="">Choisissez vos tags</option>
                              <?php foreach($forum->queryTags() as $tags){ ?>
                                  <option value="<?= $tags->id ?>" <?= $forum->choicesTagsSelected($tags->id) ?>><?= $tags->name ?></option>
                              <?php } ?>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <div class="editor-area-forum">
                      <div class="editor-area-forum">
                        <?= $Parsing->MarkDownEditor('f_topic_content', $topic->firstTopic()->f_topic_content); ?>
                      </div>
                  </div>
                </div>
                <button type="submit" name="topics" class="btn btn-danger mr-3">Envoyez <i class="fas fa-paper-plane"></i></button>
                <?php $page = isset($_GET['page']) ? '?page='.$_GET['page'] : null ; ?>
                <a href="<?= $router->routeGenerate('viewtopic', ['id' => $match['params']['id'] . $page . '#topic-' . $match['params']['id'] ]) ?>" class="btn btn-link">Anuler</a>
                <?= $session->csrfInput(); ?>
            </form>
        </div>
      <div class="card-footer"></div>
    </div>
  </div>
</div>