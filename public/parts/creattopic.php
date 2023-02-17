
      Soyez correct et inspiré ne postez pas du vide <small class="text-muted">(merci)</small>
      <form method="post">
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
                <input class="HoTagsI form-control" placeholder="Titre du topic" value="<?= isset($_POST['f_topic_name']) && !empty($_POST['f_topic_name']) ? htmlentities($_POST['f_topic_name']) : "" ; ?>" id="f_topic_name" type="text" name="f_topic_name" required>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <select class="js-choice" name="tags[]" multiple>
                  <option value="">Choisissez vos tags</option>
              <?php foreach($tag as $tags){ ?>
                  
                  <option value="<?= intval($tags->id) ?>"><?= htmlentities($tags->name) ?></option>

              <?php } ?>

              </select>
            </div>
          </div>
          <div class="col-md-12">
            <div class="form-group">
              <div class="editor-area-forum">
                <?= BootstrapMde('f_topic_content', isset($_POST['f_topic_name']) && !empty($_POST['f_topic_name']) ? htmlentities($_POST['f_topic_name']) : $Parsing->JustDemo()); ?>
                <?php if(in_array($_SESSION["auth"]->authorization, [2,3])): ?>
                      <input type="checkbox" name="sticky" value="1"> <small style="color:#912c1a;">(Personne ne pourra répondre a par les admin) </small> 
                <?php endif; ?>
              </div>
            </div>
          </div>
          <div class="col-md-12">
            <?= csrfInput(); ?>
            <button type="submit" name="topics" class="btn btn-danger mr-3">Envoyez <i class="fas fa-paper-plane"></i></button>
            <a href="<?= $router->generate('forum') ?>#forum" class="btn btn-link">Anuler</a>
          </div>
        </div>
    </form>