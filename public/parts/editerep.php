<div class="section-title">
  <h5>
    Edite réponse
  </h5>
</div>

<form method="POST">
  <?= isset($error) ? $error : '' ?>
    <div class="editor-area-forum">
      <?= BootstrapMde('f_topic_content', htmlentities($rep->f_topic_reponse)); ?>
    </div>
    <button type="submit" name="topics" class="btn btn-danger mr-3 mt-3">Envoyez <i class="fas fa-paper-plane"></i></button>
    <a href="<?= $router->generate('viewtopic', ['id' => $rep->repid . '#rep-' . $rep->repid]) ?>" class="btn btn-link mt-3">Anuler</a>
    <?= csrfInput(); ?>
</form>
 