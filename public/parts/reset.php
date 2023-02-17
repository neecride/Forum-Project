<div class="col-md-8">

<aside id="recent-comments-2" class="block box-shadow widget_recent_comments">
           <div class="droiteTitre text-shadow">Réinitialiser mots de pass</div>
               <div class="droiteContenu">
                <form method="post" id="pass" action="">
                    <div class="form-group">
                       <?= newInput('password','Mot de pass <span id="passwd"></span>',

                            [
                                'type' => 'password',
                                'placeholder' => 'password',
                                'class' => 'form-control',
                                'id' => 'password',
                                'style' => 'width:100%;',
                                'required' => 'required'
                            ]

                           );
                        ?>
                    </div>

                    <div class="form-group">
                       <?= newInput('password_confirm','Mot de pass confirmation <span id="passwdc"></span>',

                            [
                                'type' => 'password',
                                'placeholder' => 'password&nbsp;confirm',
                                'class' => 'form-control',
                                'id' => 'password_confirm',
                                'style' => 'width:100%;',
                                'required' => 'required'
                            ]

                           );
                        ?>
                    </div>
                    <button type="submit" name="pwd" class="btn btn-xs btn-primary">Confirmer votre nouveau password</button>
                    <?= csrfInput(); ?>
                </form>

               </div>
        </aside>
</div>
