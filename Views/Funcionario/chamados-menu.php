<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel de Empresas</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL; ?>assets/css/tema.css">
</head>

<body class="home">
    <aside>
        <div class="menu-title">🔵MENU</div>
        <div class="user">
            <div class="user-logo">
                𖡌
            </div>
            <div class="user-type">
                Cliente
            </div>
        </div>
        <a class="active" href="<?= BASE_URL; ?>Funcionario/">Dashboard</a>
        <a href="<?= BASE_URL; ?>Login/" style="margin-top: auto;">Logout</a>
    </aside>

    <main class="main">
        <section id="dashboard">
            <div class="header-section">
                <h2>Dashboard</h2>
            </div>
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
            <div class="title-section">
                <h2>Fazendo</h2>
                <button class="btn" onclick="window.location.href='<?= BASE_URL; ?>AddCliente/'">+</button>
            </div>
            <div class="task-container">
                <div class="task">
                    <div class="task-text">
                        <div class="task-title">Chamados 1</div>
                        <div class="task-desc">Manutenção de Equipamentos</div>
                    </div>
                    <button class="btn"onclick="window.location.href='../Cliente_editar/'">Editar</button>
                </div>
                <div class="task">
                    <div class="task-text">
                        <div class="task-title">Chamados 2</div>
                        <div class="task-desc">Manutenção de Equipamentos</div>
                    </div>
                    <button class="btn"onclick="window.location.href='../Cliente_editar/'">Editar</button>
                </div>
                <div class="task">
                    <div class="task-text">
                        <div class="task-title">Chamados 3</div>
                        <div class="task-desc">Manutenção de Equipamentos</div>
                    </div>
                    <button class="btn"onclick="window.location.href='../Cliente_editar/'">Editar</button>
                </div>
            </div>
        </section>
    </main>
    
  <!-- Início parte estava em um arquivo separardo  (Aqui será editado para transformar em um modal) -->

    <!-- <section>
      <form>
        <h2>Novo Chamada</h2>

        <label for="titulo">Chamado</label>
        <input type="text" id="titulo" placeholder="Manutenção de Equipamentos">

        <label for="funcionarios">Funcionarios</label>
        <input type="text" id="funcionarios" placeholder="02">

        <label for="detalhes">Detalhes</label>
        <input type="text" id="detalhes" placeholder="Equipamentos da empresa necessitam de reparos e manutenção">

        <label for="localizacao">Localização</label>
        <input type="text" id="localizacao" placeholder="Escritório X Sala 6A">

        <div style="display: flex; gap: 1.6rem; margin-top: 2rem;">
          <button class="btn" type="button" onclick="window.location.href='../Cliente_pendente/'">Fazer Chamada</button>
        </div>
      </form>
    </section> -->

  <!-- Fim do arquivo modalAddChamado  -->


  <!-- Início do modalEditarChamado (Aqui será editado para transformar em um modal)-->

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
          <button class="btn" type="button" onclick="window.location.href='../Cliente_pendente/'">Salvar</button>
          <button class="btn" type="button" onclick="alert('Chamado excluído')">Excluir</button>
        </div>
      </form>
    </section> -->

  <!-- Fim do arquivo modalEditarChamado -->


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