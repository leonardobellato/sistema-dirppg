<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Layout com Sidebar e Header</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<style>
/* Estilo para o corpo da página */
body {
  display: flex;
  min-height: 100vh;
  flex-direction: column;
}

/* Container principal com o sidebar e o conteúdo */
.main-container {
  display: flex;
  flex-grow: 1;
}

/* Sidebar fixo */
#sidebar {
  position: sticky;
  top: 0; /* Cola no topo da viewport */
  align-self: flex-start; /* Alinha no início para evitar esticar */
  height: 100vh; /* Ocupa a altura total da viewport */
  overflow-y: auto; /* Adiciona scroll se o conteúdo for maior que a tela */
}

/* Header fixo */
header {
  position: sticky;
  top: 0;
  z-index: 1050;
  background-color: #fff;
  border-bottom: 1px solid #ddd;
  display: flex;
  align-items: center;
  padding: 0 1rem;
}

/* Conteúdo principal que rola */
main {
  flex-grow: 1;
  padding: 1rem;
  overflow-y: auto; /* Adiciona scroll ao conteúdo principal */
}
</style>
</head>
<body>
<header>
  <a href="#" data-bs-target="#sidebar" data-bs-toggle="collapse" class="border rounded-3 p-1 text-decoration-none">
    <i class="bi bi-list bi-lg py-2 p-1"></i> Menu
  </a>
  <h5 class="mb-0">Meu Sistema</h5>
</header>

<div class="main-container">
  <div id="sidebar" class="collapse collapse-horizontal show border-end">
    <div id="sidebar-nav" class="list-group border-0 rounded-0 text-sm-start">
      <a href="#" class="list-group-item border-end-0 d-inline-block text-truncate" data-bs-parent="#sidebar"><i class="bi bi-bootstrap"></i> <span>Item</span> </a>
      <a href="#" class="list-group-item border-end-0 d-inline-block text-truncate" data-bs-parent="#sidebar"><i class="bi bi-film"></i> <span>Item</span></a>
      </div>
  </div>

  <main>
    <div class="page-header pt-3">
      <h2>Bootstrap 5 Sidebar Menu - Simple</h2>
    </div>
    <p class="lead">A offcanvas "push" vertical nav menu example.</p>
    <hr>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>