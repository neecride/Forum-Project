<?= $topic->checkError() ?>
<div class="row">
  <div class="col-md-12">
    <div class="card">
      <div class="card-header"><h6>Create topic</h6></div>
      <div class="card-body">
        Soyez correct et inspiré ne postez pas du vide <small class="text-muted">(merci)</small>
            <form method="post">
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                        <?= $Parsing->input('f_topic_name','text','Titre du topic','required="required"') ?>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <select class="js-choice" name="tags[]" multiple>
                          <option value="">Choisissez vos tags</option>
                      <?php foreach($forum->queryTags() as $tags){ ?>
                          <option value="<?= $tags->id ?>"><?= $tags->name ?></option>
                      <?php } ?>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <div class="editor-area-forum">
                    <?= $Parsing->MarkDownEditor('f_topic_content'); ?>
                    <?php if(in_array($_SESSION["auth"]->authorization, [2,3])): ?>
                          <input type="checkbox" name="sticky" value="1"> <small style="color:#912c1a;">(Personne ne pourra répondre a par les admin) </small> 
                    <?php endif; ?>
                  </div>
                </div>
                <?= $session->csrfInput(); ?>
                <button type="submit" name="topics" class="btn btn-danger mr-3 mt-3">Envoyez <i class="fas fa-paper-plane"></i></button>
                <a href="<?= $router->routeGenerate('forum') ?>#forum" class="btn btn-link mt-3">Anuler</a>
            </form>
      </div>
      <div class="card-footer"></div>
    </div>
  </div>
</div>