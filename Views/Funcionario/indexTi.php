<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard</title>
  <link rel="stylesheet" href="../../Assets/css/tema.css">
</head>
<body class="home">

  <div class="container">

    <div class="header">Dashbord</div>

    <div class="status-boxes">
      <button class="box" onclick="window.location.href='../Cliente_pendente/'">
        <span>03</span>
        <label>Pendente</label>
      </button>
      <button class="box" onclick="window.location.href='../Cliente_fazendo/'">
        <span>05</span>
        <label>Fazendo</label>
      </button>
      <button class="box" onclick="window.location.href='../Cliente_finalizado/'">
        <span>08</span>
        <label>Finalizado</label>
      </button>
    </div>

    <div class="section-title">
      <div>Pendente</div>
      <button class="btn-tarefa" onclick="window.location.href='../Cliente_addchamado/'">+</button>    </div>

    <div class="card">
      <div class="card-text">
        <div class="card-title">Chamados 1</div>
        <div class="card-desc">Manutenção de Equipamentos</div>
      </div>
      <button class="btn-tarefa"onclick="window.location.href='../Cliente_aceitar/'">Aceitar</button>
    </div>

    <div class="card">
      <div class="card-text">
        <div class="card-title">Chamado 2</div>
        <div class="card-desc">Manutenção de Equipamentos</div>
      </div>
      <button class="btn-tarefa" onclick="window.location.href='../Cliente_aceitar/'">Aceitar</button>
    </div>

    <div class="card">
      <div class="card-text">
        <div class="card-title">Empresa 3</div>
        <div class="card-desc">Manutenção de Equipamentos</div>
      </div>
      <button class="btn-tarefa"onclick="window.location.href='../Cliente_aceitar/'">Aceitar</button>
    </div>

  </div>

  <!-- Início do arquivo modalEditarChamado (Aqui será editado para transformar em um modal) -->

    <!-- <section>
      <form>
        <h2>Editar</h2>

        <label for="titulo">Chamado</label>
        <input type="text" id="titulo" placeholder="Manutenção de Equipamentos">

        <label for="funcionarios">Funcionarios</label>
        <input type="text" id="funcionarios" placeholder="02">

        <label for="detalhes">Detalhes</label>
        <input type="text" id="detalhes" placeholder="Equipamentos da empresa necessitam de reparos e manutenção">

        <label for="localizacao">Localização</label>
        <input type="text" id="localizacao" placeholder="Escritório X Sala 6A">

        <div style="display: flex; gap: 1.6rem; margin-top: 2rem;">
          <button class="btn" type="button" onclick="window.location.href='../Cliente_pendente/'">Aceitar</button>
          <button class="btn" type="button" onclick="alert('Chamado excluído')">Excluir</button>
        </div>
      </form>
    </section> -->

  <!-- Fim do arquivo modalEditarChamado -->

</body>
</html>
