
<?php
use App\Helpers\Sessao;
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="<?= asset('assets/css/style1.css') ?>">
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;700&display=swap" rel="stylesheet" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title> formulario da consulta</title>
</head>
<body>
  <main>
    <section id="cadastro">
      <h1>Marcação de Consulta</h1>
      <form action="<?= URL ?>\do" method="post" id="formConsulta" novalidate>
        <span>
          <h2>Preencha os Dados</h2>
          <button class="bu"><a href="<?= URL ?>\home">↩ voltar</a></button>
        </span>
        <div class="linha">
          <input type="text" name="nome" id="nome" placeholder="Nome completo" class="inputform" required pattern="[^\d]+" title="O nome não pode conter números" autocomplete="name">
          <input type="tel" name="phone" id="phone" placeholder="Telefone (ex: 923456789)" class="inputform" required pattern="9[0-9]{8}" maxlength="9" minlength="9" inputmode="numeric" title="9 dígitos angolanos, começando com 9">
          <input type="datetime-local" name="data" id="data" placeholder="Data da consulta" class="inputform" required step="1800" />
        </div>
        <div class="linha">
          <input type="email" name="email" id="email" class="inputform" placeholder="E-mail valido (ex: nome@email.com)" required autocomplete="email">
          <select name="departamento" id="departamento" class="inputform" required>
            <option value="" selected disabled>Escolha o departamento</option>
            <?php foreach ($departs as $key => $value): ?>
              <option value="<?= $value['id'] ?>"><?= htmlspecialchars($value['name']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="linha">
            <textarea name="mensagem" id="mensagem" placeholder="Digite a mensagem (Opcional)"></textarea>
        </div>
        <div>
          <input type="submit" class="bu" name="bu" value="Registrar" />
        </div>
      </form>
    </section>
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('formConsulta');
        const inputNome = document.getElementById('nome');
        const inputEmail = document.getElementById('email');
        const inputPhone = document.getElementById('phone');
        const inputData = document.getElementById('data');

        inputNome.addEventListener('input', function() {
          this.value = this.value.replace(/\d/g, '');
        });

        inputNome.addEventListener('keypress', function(event) {
          if (/\d/.test(event.key)) {
            event.preventDefault();
          }
        });

        inputPhone.addEventListener('input', function() {
          this.value = this.value.replace(/\D/g, '').slice(0, 9);
        });

        function emailValido(valor) {
          return /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(valor.trim());
        }

        function telefoneValido(valor) {
          return /^9\d{8}$/.test(valor.replace(/\D/g, ''));
        }

        form.addEventListener('submit', function(event) {
          const email = inputEmail.value.trim();
          const telefone = inputPhone.value.replace(/\D/g, '');

          if (!emailValido(email)) {
            event.preventDefault();
            alert('Informe um e-mail valido (exemplo: nome@email.com).');
            inputEmail.focus();
            return;
          }

          if (!telefoneValido(telefone)) {
            event.preventDefault();
            alert('Informe um telefone angolano valido: 9 digitos iniciando com 9 (ex: 923456789).');
            inputPhone.focus();
            return;
          }

          inputPhone.value = telefone;
        });

        function setTodayAsMinDate() {
          const now = new Date();
          const year = now.getFullYear();
          const month = String(now.getMonth() + 1).padStart(2, '0');
          const day = String(now.getDate()).padStart(2, '0');
          const hours = String(now.getHours()).padStart(2, '0');
          const minutes = String(now.getMinutes()).padStart(2, '0');
          const currentDateTime = `${year}-${month}-${day}T${hours}:${minutes}`;
          inputData.min = currentDateTime;
          inputData.value = currentDateTime;
        }

        setTodayAsMinDate();
      });
    </script>
  </main>
</body>
</html>
