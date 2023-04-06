<div class="row">
    <div class="col-md-12">
        <div class="row">
          <div class="col-md-12">
            <div class="section-title">
              <h5>
                Administration
              </h5>
            </div>
          </div>
        </div>
        <div class="anime__details__review">
      <div class="row">
        <div class="col-xs-6 col-md-12">
          <div class="card">
            <div class="card-header">
              <h6>Gestion des utilisateurs</h6>
            </div>
			<div class="card-body">
				<table class="table table-striped">
					<thead>
						<tr>
							<th>Pseudo</th>
							<th>eMail</th>
							<th>Activation</th>
							<th>Confirm AT</th>
							<th>slug</th>
							<th class="text-center">Action</th>
						</tr>
					</thead>
					<tbody>
						<?php
                        foreach ($User->getUsers() as $user){
							
                        ?>
						<tr>
							<td><?= $user->username; ?></td>
							<td><?= $user->email; ?></td>
							<td>
								<?= $user->activation ?> 
								<?php 
									if($user->activation == 0){ echo 'Ban'; }
								?>
							</td>
							<td>
								<?php 
								if($user->confirmed_at != null): 
									echo 'activé depuis : '.$GetParams->AppDate($user->confirmed_at);
								else:
									echo 'non activé';
								endif;
								?>
							</td>
							<td><?= $user->slug; ?></td>
								<td class="text-center">
									<?php if(!in_array($user->authorization ,[3])){ ?>
										<a href="<?= $router->routeGenerate('user-delete',['del' => $user->id, 'rank' => $user->authorization, 'getcsrf' => $session->csrf()]) ?>" onclick="return confirm('Sur de sur ?');" data-toggle="tooltip" data-placement="top" title="supprimer l'utilisateur">
											<i class="far fa-trash-alt"></i>
										</a>&nbsp;
									<?php if($user->activation == 1){ ?>
										<a href="<?= $router->routeGenerate('user-desactive',['unactiv' => $user->id, 'rank' => $user->authorization, 'getcsrf' => $session->csrf()]) ?>" data-toggle="tooltip" data-placement="top" title="Desactiver l'utilisateur">
											<i class="fas fa-ban"></i>
										</a>&nbsp;
									<?php }else if($user->activation == 0){ ?>
										<a href="<?= $router->routeGenerate('user-active',['activ' => $user->id, 'rank' => $user->authorization, 'getcsrf' => $session->csrf()]) ?>" data-toggle="tooltip" data-placement="top" title="Activer l'utilisateur">
											<i class="fas fa-check"></i>
										</a>&nbsp;
									<?php } ?>
										<a href="<?= $router->routeGenerate('user-edit',['id' => $user->id, 'getcsrf' => $session->csrf()]) ?>" data-toggle="tooltip" data-placement="top" title="Editer l'utilisateur">
											<i class="fas fa-edit"></i>
										</a>
									<?php } ?>
								</td>
						</tr>
						<?php } ?>
					</tbody>
				</table>
				<a href="<?= $router->routeGenerate('admin') ?>" class="btn btn-danger">Retour index admin</a>
			</div>
          </div>
        </div>
      </div>
  </div>
</div>
</div>
<!-- Blog Section End -->