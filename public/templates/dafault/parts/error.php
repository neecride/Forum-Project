<div class="row">
        <div class="col-md-12">
                <div class="section-title">
                        <h5>Error</h5>
                </div>
        </div>
        <div class="col-md-12">
                <div class="card">
                        <div class="card-header"></div>
                                <div class="card-body">
                                        Bonjour <?= isset($_SESSION['auth']->username) ? $_SESSION['auth']->username.' Votre IP : '. $_SERVER["REMOTE_ADDR"] : 'visiteur votre IP' . $_SERVER["REMOTE_ADDR"] ; ?><br>
                                        Vous-vous trouvez sur cette page car vous ête tomber soit sur une page qui n'existe pas, soit une erreur dans un formulaire type erreur <a href="https://fr.wikipedia.org/wiki/Cross-Site_Request_Forgery" target="_blank">CSFR</a>.
                                        <br />
                                        Un email nous a été envoyez consernant cette erreur CSRF avec vos donnée en session ainsi que votre adresse IP afin de prendre des mesures en cas de possible hack de notre site.
                                </div>
                        <div class="card-footer"></div>
                </div>
        </div>
</div>