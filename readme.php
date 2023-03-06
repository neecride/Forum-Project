
Pense bête 
Utilisation de la pagination 

$pagination = new App\Pagination;

on compte le total et si on a des arguments la fonction fera un prepare sinon un query 
$Count = $pagination->CountIdForpagination('SELECT COUNT(id)','argument get id par exemple');

check si la page exist et que c'est bien un entier et si c'est négative 
sinon génére une redirection avec le lien dans le parametre
$pagination->isExistPage($router->generate($match['name'], ['id' => $params['id']]));

<nav>
<ul class="pagination mb-3 mt-3 pagination-sm">
<?= $pagination->pageFor() ?>
</ul>
</nav>

génére un lien qui renvois vers la page++ et la balise réponse #rep
<a href="<?= $pagination->userLinkPage($posts->topicid,$rep->idrep,$count->countid) ?>">
<?= $rep->username ?>
</a>

affiche une mini pagination avec une boucle for a coté du titre par exemple 
le lien est généré avec le nombre de topic en bdd et le lien vers la page 
si $i == 1 on afficche le lien sans page sinon on affiche la page 1/2/3 etc..
<div class="uri">
<?= $pagination->subLinkPage($posts->topicid,$count->countid) ?>
</div>
