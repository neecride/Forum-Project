<?php
$forum = new Action\ForumAction;
$activeWidget = 1;
$inpage = in_array($match['target'], ['home','forum','viewtopic','viewforums','survey']);
if($activeWidget == 1 && $inpage){
?>
    <ul class="list-group list-group-flush mb-3">
        <li class="list-group-item text-center <?= in_array($match['target'],['forum','survey','home']) ? 'active' : '' ; ?>">
            <a class="<?= $match['target'] == 'forum' ? 'active' : '' ; ?>" href="<?= $router->routeGenerate('forum') ?>">Tous les sujets</a>
        </li>
        <?php 
        foreach($forum->queryTags() as $navlink): 
            if($forum->CounterTag($navlink->id)->nbid >= 1):
        ?>
            <li class="list-group-item d-flex justify-content-between align-items-center <?= isset($match['params']['slug']) && $navlink->slug == $match['params']['slug'] ? 'active' : '' ; ?>">
                <a href="<?= $router->routeGenerate('forum-tags', ['slug' => $navlink->slug, 'id' => $navlink->id]) ?>">
                    <?= $app->trunque($navlink->name,20); ?> 
                </a>
                <span class="badge Hobadgnav badge-pill"><?= $forum->CounterTag($navlink->id)->nbid ?></span>
            </li>
        <?php
            endif; 
        endforeach; 
        ?>
    </ul>
<?php } ?>