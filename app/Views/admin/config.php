<main id="main" class="main">
  <div class="pagetitle">
    <h1>Alterar Conta</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= URL ?>/admin">Home</a></li>
        <li class="breadcrumb-item active">Config</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->
  <div class="col-xl-8">

    <div class="card">
      <div class="card-body pt-3">
        <!-- Bordered Tabs -->
        <ul class="nav nav-tabs nav-tabs-bordered">

          <li class="nav-item">
            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profile-change-password">Mudar a password</button>
          </li>
          <li class="nav-item">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-edit">Editar nome</button>
          </li>



        </ul>
        <div class="tab-content pt-2">

          <div class="tab-pane fade show active pt-3" id="profile-change-password">
            <!-- Change Password Form -->
            <form action="<?= URL ?>/admin/config" method="post">

              <div class="row mb-3">
                <label for="currentPassword" class="col-md-4 col-lg-3 col-form-label">Senha Corrente</label>
                <div class="col-md-8 col-lg-9">
                  <input value="<?= $dados['senha'] ?>" name="password" type="password" class="form-control <?= $dados['err_senha'] ? 'is-invalid' : '' ?>" id="currentPassword">
                  <div class="invalid-feedback">
                    <?= $dados['err_senha'] ?>
                  </div>
                </div>
              </div>

              <div class="row mb-3">
                <label for="newPassword" class="col-md-4 col-lg-3 col-form-label">Nova Senha</label>
                <div class="col-md-8 col-lg-9">
                  <input name="newpassword" type="password" class="form-control <?= $dados['err_newpass'] ? 'is-invalid' : '' ?>" id="newPassword" value="<?= $dados['novasenha'] ?>">
                  <div class="invalid-feedback">
                    <?= $dados['err_newpass'] ?>
                  </div>
                </div>
              </div>

              <div class="row mb-3">
                <label for="renewPassword" class="col-md-4 col-lg-3 col-form-label">Repita Nova Senha</label>
                <div class="col-md-8 col-lg-9">
                  <input value="<?= $dados['rnovasenha'] ?>" name="renewpassword" type="password" class="form-control <?= $dados['err_renewpass'] ? 'is-invalid' : '' ?>" id="renewPassword">
                  <div class="invalid-feedback">
                    <?= $dados['err_renewpass'] ?>
                  </div>
                </div>
              </div>

              <div class="text-center">
                <button type="submit" name="cad" value="s" class="btn btn-primary">Mude Agora</button>
              </div>
            </form><!-- End Change Password Form -->

          </div>
          <div class="tab-pane fade profile-edit pt-3" id="profile-edit">

            <!-- Profile Edit Form -->
            <form action="<?= URL ?>/admin/changename" method="post">
              <div class="row mb-3">
                <label for="nome" class="col-md-4 col-lg-3 col-form-label ">Nome</label>
                <div class="col-md-8 col-lg-9 mb-3">
                  <input name="nome" type="text" class="form-control <?= $dados['err_nome'] ? 'is-invalid' : ''; ?>" id="nome" value="<?= $dados['name'] ?>">
                  <div class="invalid-feedback">
                    <?= $dados['err_nome'] ?>
                  </div>
                </div>
                <label for="email" class="col-md-4 col-lg-3 col-form-label ">Email</label>
                <div class="col-md-8 col-lg-9">
                  <input name="email" type="email" class="form-control <?= $dados['err_email'] ? 'is-invalid' : ''; ?>" id="email" value="<?= $dados['email'] ?>">
                  <div class="invalid-feedback">
                    <?= $dados['err_email'] ?>
                  </div>
                </div>
              </div>

              <div class="text-center">
                <button type="submit" name="btn" value="s" class="btn btn-primary">Salvar</button>
              </div>
            </form><!-- End Profile Edit Form -->

          </div>


        </div><!-- End Bordered Tabs -->

      </div>

    </div>

  </div>
</main>