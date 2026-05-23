<main id="main" class="main">
  <div class="pagetitle">
    <h1>Usuarios</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= URL ?>/admin">Home</a></li>
        <li class="breadcrumb-item active">usuarios</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->

  <!-- contactos -->
  <div class="col-lg-12 ">
    <div class="row ">

      <div class="col-12">
        <div class="card recent-sales overflow-auto">


          <div class="card-body ">
            <!-- Modal trigger create -->
            <button class="btn btn-primary m-2" data-bs-toggle="modal" data-bs-target="#modalCad">+Cadastrar</button>


            <!-- Modal Body -->
            <!-- if you want to close by clicking outside the modal, delete the last endpoint:data-bs-backdrop and data-bs-keyboard -->
            <div class="modal fade" id="modalCad" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered " role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="modalTitleId">Cadastrar Usuario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <form action="<?= URL ?>/admin/cadastrar/usuario" method="post" id="formCat">
                    <div class="modal-body">
                      <div class="mb-3">
                        <label for="nome" class="form-label">Nome</label>
                        <input type="text" class="form-control" id="nome" name="nome" required>
                        <div class="invalid-feedback" id="error_nome"></div>
                      </div>
                      <div class="mb-3">
                        <label for="desc" class="form-label">Email</label>
                        <input type="email" class="form-control" id="desc" name="email" required>
                        <div class="invalid-feedback" id="error_desc"></div>
                      </div>
                      <div class="mb-3">
                        <label for="senha" class="form-label">Senha</label>
                        <input type="password" class="form-control" id="senha" name="senha" required minlength="6">
                        <div class="invalid-feedback" id="error_senha"></div>
                      </div>
                      <div class="mb-3">
                        <label for="select" class="form-label">Departamentos</label>
                        <select class="form-select" aria-label="departaments" id="departCad" name="depart">
                        <option value=''>Sem departamento (apenas admin)</option>
                          <?php
                          if ($departs) :
                            foreach ($departs as $key => $value) : ?>
                              <option value='<?= $value['id_departaments'] ?>'><?= $value['name'] ?></option>
                          <?php
                            endforeach;
                          endif; ?>
                        </select>
                      </div>
                      <div class="mb-3">
                        <label for="select" class="form-label">Nivel</label>
                        <select class="form-select" aria-label="Default select example" id="nivelCad" name="nivel" required>
                          <option value='' disabled selected>Escolha o nivel do usuario</option>
                          <option value='1'>Medico</option>
                          <option value="0">Admin</option>
                        </select>
                      </div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                      <button type="submit" class="btn btn-primary">Salvar</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>



            <table class="table table-borderless datatable">
              <thead>
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">Nome</th>
                  <th scope="col">Email</th>
                  <th scope="col">Departamento</th>
                  <th scope="col">Tipo</th>
                  <th scope="col">Acções</th>
                  <!-- <th scope="col">Status</th> -->
                </tr>
              </thead>
              <tbody>
                <?php if ($users) : $i = 0; ?>
                  <?php foreach ($users as $key => $value) : ?>
                    <tr>
                      <th scope="row"><?= $i += 1 ?></th>
                      <td><?= $value['nameU'] ?></td>
                      <td><?= $value['email'] ?></td>
                      <td><?= $value['nameD'] ?></td>
                      <td><?= $value['type_user'] == '0' ? 'admin' : 'doctor' ?></td>
                      <td class="d-flex gap-3">

                        <button class="badge bg-success border-0 p-2" data-bs-toggle="modal" data-bs-target="#modalE<?= $i ?>">Editar</button>
                        <button class="badge bg-danger border-0 p-2" data-bs-toggle="modal" data-bs-target="#modalD<?= $i ?>">Deletar</button>

                      </td>
                    </tr>

                    <!-- Modal edit -->

                    <div class="modal fade" id="modalE<?= $i ?>" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
                      <div class="modal-dialog modal-dialog-centered " role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="modalTitleId">Editar o usuario: <?= $value['nameU'] ?></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                          </div>
                          <form action="<?= URL ?>/admin/editar/usuario/<?= $value['id_users'] ?>" method="post" id="formEdit">
                            <div class="modal-body">
                              <div class="mb-3">
                                <label for="nome" class="form-label">Nome</label>
                                <input type="text" class="form-control" id="nomeE" name="nome" value="<?= $value['nameU'] ?>">
                                <div class="invalid-feedback" id="error_nomeE"></div>
                              </div>
                              <div class="mb-3">
                                <label for="desc" class="form-label">Email</label>
                                <input type="email" class="form-control" id="descE" name="email" value="<?= $value['email'] ?>">
                                <div class="invalid-feedback" id="error_desc"></div>
                              </div>
                              <div class="mb-3">
                                <label for="select" class="form-label">Departamentos</label>
                                <select class="form-select" aria-label="Default select example" id="select" name="depart">
                                <option value='<?= $value['id_departament'] ?? '' ?>'><?= htmlspecialchars($value['nameD'] ?? 'Sem departamento') ?></option>
                                  <?php
                                  if ($departs) :
                                    foreach ($departs as $key => $depart) : ?>
                                      <option value='<?= $depart['id_departaments'] ?>'><?= $depart['name'] ?></option>
                                  <?php
                                    endforeach;
                                  endif; ?>
                                </select>
                              </div>
                              <div class="mb-3">
                                <label for="select" class="form-label">Nivel</label>
                                <select class="form-select" aria-label="Default select level" id="select" name="nivel">
                                  <option selected value='<?=$value['type_user']?>'><?= $value['type_user'] == '0' ? 'admin' : 'doctor' ?></option>
                                  <option value='1'>doctor</option>
                                  <option value="0">admin</option>
                                </select>
                              </div>
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                              <button type="submit" class="btn btn-primary">Salvar</button>
                            </div>
                          </form>
                        </div>
                      </div>
                    </div>
                    <!-- Modal Delete -->

                    <div class="modal fade" id="modalD<?= $i ?>" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
                      <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered " role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="modalTitleId"><?= $value['name'] ?></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                          </div>
                          <div class="modal-body">
                            Tem certeza que deseja deletar?
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                            <form action="<?= URL ?>/admin/delete/usuario/<?= $value['id_users'] ?>/<?= $value['type_user'] ?>" method="post">
                              <button type="submit" class="btn btn-primary">Deletar</button>
                            </form>
                          </div>
                        </div>
                      </div>
                    </div>



                  <?php endforeach; ?>
                <?php endif; ?>

              </tbody>
            </table>

          </div>

        </div>
      </div>
      <!-- End contactos -->
    </div>
  </div>

</main>
<script>
  const formCat = document.querySelector('#formCat');
  const nivelCad = document.querySelector('#nivelCad');
  const departCad = document.querySelector('#departCad');

  if (nivelCad && departCad) {
    nivelCad.addEventListener('change', function() {
      departCad.required = this.value === '1';
    });
  }

  if (formCat) {
    formCat.addEventListener('submit', function(e) {
      const nivel = nivelCad ? nivelCad.value : '';
      const depart = departCad ? departCad.value : '';

      if (!nivel) {
        e.preventDefault();
        alert('Selecione o nivel do usuario.');
        return;
      }

      if (nivel === '1' && depart === '') {
        e.preventDefault();
        alert('Selecione o departamento para o medico.');
      }
    });
  }
</script>