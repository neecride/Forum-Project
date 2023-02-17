<div class="col-md-3">
    <div class="section-title-nav">
        <h5>Navigation</h5>
    </div>
    
    <ul class="list-group list-group-flush mt-5">
        <li class="list-group-item text-center <?= $router->match()['target'] == 'forum' ? 'active' : '' ; ?>">
            <a class="<?= $match['target'] == 'forum' ? 'active' : '' ; ?>" href="<?= $router->generate('forum') ?>">Tous les sujets</a>
        </li>
        <?php foreach(navLink() as $navlink): ?>
            <li class="list-group-item d-flex justify-content-between align-items-center <?= isset($params['slug']) && !empty($navlink->slug == $params['slug']) ? 'active' : '' ; ?>">
                <a href="<?= $router->generate('forum-tags', ['slug' => $navlink->slug, 'id' => $navlink->id]) ?>">
                    <?= trunque($navlink->name,20); ?> 
                </a>
                <span class="badge Hobadgnav badge-pill"><?= CounterTag($navlink->id)->nbid ?></span>
            </li>
        <?php endforeach; ?>
    </ul>
</div>