<?php

use App\Helpers\Sessao;
use App\Helpers\Valida;

?>
<main id="main" class="main">
  <div class="pagetitle">
    <h1>Todas Consultas</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= URL ?>/admin">Home</a></li>
        <li class="breadcrumb-item active">Consultas</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->

  <!-- todas consultas -->
    <section class="section dashboard">
  <div class="col-12">
    <div class="card recent-sales overflow-auto pt-3">

      <div class="card-body">
        

        <table class="table table-borderless datatable">
          <thead>
            <tr>
              <th scope="col">#</th>
              <th scope="col">Nome</th>
              <th scope="col">Email</th>
              <th scope="col">Departamento</th>
              <th scope="col">status</th>
              <th scope="col">Data</th>
              <th scope="col">Acção</th>
            </tr>
          </thead>
          <tbody>

            <?php
            if ($consultas) : $i = 0; ?>
              <?php foreach ($consultas as $key => $value) : ?>
                <tr>
                  <th scope="row"><?= $i += 1 ?></th>
                  <td><?= $value['nameC'] ?></td>
                  <td><?= $value['emailC'] ?></td>
                  <td><?= $value['nameD'] ?></td>
                  <td class="<?= 
                  $value['status'] == "pendente" ? "text-primary" : 
                  ($value['status'] == "cancelada" ? "text-danger" : 
                  ($value['status'] == "confirmada" ? "text-warning" : "text-success"))
                  ?> "><?= $value['status'] ?></td>
                  <td><?= Valida::dataExtenso($value['datetime']) ?></td>
                  <td class="d-flex gap-3">
                    <?php if ($value['status'] == "pendente"): ?>

                      <button class="badge bg-primary border-0 p-2" data-bs-toggle="modal" data-bs-target="#modalE<?= $i ?>"><i class="bi bi-three-dots"></i></button>

                    <?php elseif ($value['status'] == "cancelada") : ?>

                      <button class="badge bg-danger border-0 p-2"><i class="bi bi-x-square-fill"></i></button>

                    <?php elseif ($value['status'] == "confirmada"): ?>

                      <button class="badge bg-success border-0 p-2" data-bs-toggle="modal" data-bs-target="#modalD<?= $i ?>"><i class="bi bi-check-square"></i></button>

                    <?php else: ?>

                      <button class="badge bg-success border-0 p-2" 
                      title="Já foi realizada"><i class="bi bi-check-square-fill"></i></button>

                    <?php endif; ?>

                  </td>
                </tr>

                <!-- Modal edit -->

                <div class="modal fade" id="modalE<?= $i ?>" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered " role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="modalTitleId">Consulta Agendada por: <?= $value['nameC'] ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <form action="<?= URL ?>/admin/consults/<?= $value['id_consults'] ?>" method="post" id="formEdit">
                        <div class="modal-body">

                          <div class="mb-3">
                            <label for="nome" class="form-label">Descrição do paciente</label>
                            <textarea class="form-control" disabled><?= $value['descrC'] ?></textarea>
                          </div>

                          <div class="mb-3">
                            <label for="desc" class="form-label">Motivo (Caso Cancele)</label>
                            <textarea class="form-control" name="desc" id="desc" rows="3">
                                      </textarea>
                          </div>

                          <div class="mb-3 d-none">
                            <input type="text" name="emailC" value="<?= $value['emailC'] ?>">
                            <input type="text" name="emailU" value="<?= $value['emailU'] ?>">
                            <input type="text" name="nomeU" value="<?= $value['nomeU'] ?>">
                            <input type="text" name="nomeC" value="<?= $value['nomeC'] ?>">
                          </div>

                          <div class="mb-3">
                            <div class="form-check form-check-inline">
                              <input
                                class="form-check-input"
                                type="radio"
                                name="radio"
                                id="radio1<?= $i ?>"
                                value="cancelada"
                                required />
                              <label class="form-check-label" for="radio1<?= $i ?>">Cancelar</label>
                            </div>
                            <div class="form-check form-check-inline">
                              <input
                                class="form-check-input"
                                type="radio"
                                name="radio"
                                id="radio2<?= $i ?>"
                                value="confirmada" />
                              <label class="form-check-label" for="radio2<?= $i ?>">Confirmar</label>
                            </div>
                          </div>



                        </div>

                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                          <input type="submit" class="btn btn-primary" name="btn1" value="Salvar" />
                        </div>
                      </form>

                    </div>
                  </div>
                </div>
                <!-- Modal Confirme -->

                <div class="modal fade" id="modalD<?= $i ?>" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered " role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="modalTitleId">Consulta Agendada por: <?= $value['nameC'] ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body">
                        <div class="mb-3">
                          <label for="nome" class="form-label">Descrição do paciente</label>
                          <textarea class="form-control" disabled><?= $value['descrC'] ?></textarea>
                        </div>
                        <span class="text-danger">
                          Deseja marcar a consulta como realizada?
                        </span>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                        <form action="<?= URL ?>/admin/consultsR/<?= $value['idC'] ?>" method="post">
                          <button type="submit" class="btn btn-primary">realizada</button>
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
  </section>

</main>