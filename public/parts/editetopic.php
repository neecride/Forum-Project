
<div class="section-title">
  <h5>
    Edite topic 
  </h5>
</div>
  <form method="post">

      <?= isset($error) ? $error : '' ?>

        <div class="form-group">
          <div class="editor-area-forum">
            <?= BootstrapMde('f_topic_content', $topic->f_topic_content); ?>
          </div>
        </div>
        <button type="submit" name="topics" class="btn btn-danger mr-3 mt-3">Envoyez <i class="fas fa-paper-plane"></i></button>
        <a href="<?= $router->generate('viewtopic', ['id' => $match['params']['id'] . '#topic-' . $match['params']['id'] ]) ?>" class="btn btn-link mt-3">Anuler</a>
        <?= csrfInput(); ?>
    </form>

</div>
