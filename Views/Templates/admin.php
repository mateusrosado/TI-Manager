<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Administrativo</title>

    <link href="<?= BASE_URL; ?>Assets/css/light.css" rel="stylesheet">
    <link href="<?= BASE_URL; ?>Assets/css/sweetalert2.min.css" rel="stylesheet">

    <!-- FAVICON -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- CSS -->
    <?php if(isset($viewData['CSS'])){echo $viewData['CSS'];}; ?>
    <style type="text/css">
        
        .slideshow-container {
            max-width: 1000px;
            position: relative;
            margin: auto;
        }

    </style>
    <link rel="stylesheet" type="text/css" href="<?= BASE_URL; ?>Assets/css/style.css">
    
</head>

<body data-theme="colored">
    <div class="wrapper">
        <nav id="sidebar" class="sidebar">
            <div class="sidebar-content js-simplebar">
                <a class="sidebar-brand d-flex gap-2 align-items-center" href="#">
                    <div class="flex-shrink-0">
                        <img src="<?= BASE_URL . $viewData['logo'][0]['function_value'] ;?>" class="avatar img-fluid rounded me-1" alt="logo">
                    </div>
                    <span class="align-middle">TI.manager</span>
                </a>
                <ul class="sidebar-nav">
                    <!--  -->
                    <li class="sidebar-item px-3 <?= (isset($viewData['nivel-1']) && $viewData['nivel-1'] == "Dashboard")?'active':''; ?>">
                        <a class="sidebar-link rounded-4" href="<?=BASE_URL.'Home';?>">
                            <i class="align-middle" data-feather="trending-up"></i> <span class="align-middle">DashBoard</span>
                        </a>
                    </li>

                    <!--  -->
                    <li class="sidebar-item px-3 <?= (isset($viewData['nivel-1']) && $viewData['nivel-1'] == "Configurações")?'active':''; ?> ">
                        <!-- Perfil -->
                        <a href="#perfil" data-bs-toggle="collapse" class="sidebar-link collapsed rounded-4">
                            <i class="align-middle" data-feather="settings"></i> 
                            <span class="align-middle">Configurações</span>
                        </a>
                        <ul id="perfil" class="sidebar-dropdown list-unstyled collapse <?= (isset($viewData['nivel-1']) && $viewData['nivel-1'] == "Configurações")?'show':''; ?>" data-bs-parent="#sidebar">
                            <li class="sidebar-item <?= (isset($viewData['nivel-2']) && $viewData['nivel-2'] == "Usuarios")?'active':''; ?>"><a class="sidebar-link" href="<?= BASE_URL.'Users';?>">Usuários</a></li>
                            <li class="sidebar-item <?= (isset($viewData['nivel-2']) && $viewData['nivel-2'] == "Settings")?'active':''; ?>"><a class="sidebar-link" href="<?= BASE_URL.'Settings';?>">Configurações</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>

        <div class="main">
            <nav class="navbar navbar-expand navbar-light navbar-bg">
                <a class="sidebar-toggle d-flex">
                    <i class="hamburger align-self-center"></i>
                </a>
                <div class="navbar-collapse collapse">
                    <ul class="navbar-nav navbar-align">
                        <li class="nav-item dropdown">
                            <a class="nav-icon dropdown-toggle d-inline-block d-sm-none" href="#" data-bs-toggle="dropdown">
                                <i class="align-middle" data-feather="settings"></i>
                            </a>

                            <a class="nav-link dropdown-toggle d-none d-sm-inline-block" href="#" data-bs-toggle="dropdown">
                                <span class="text-dark"><?= $viewData['name'];?></span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item" href="<?= BASE_URL.'Login/logout';?>">Sair</a>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>
            <?php $this->loadViewInTemplate($viewName, $viewData); ?>
        </div>
    </div>
    <script src="<?= BASE_URL; ?>Assets/js/jquery-3.5.1.js"></script>
    <script src="<?= BASE_URL; ?>Assets/js/jquery.mask.js"></script>
    <script src="<?= BASE_URL; ?>Assets/js/app.js"></script>
    <script type="text/javascript">
        const BASE_URL = '<?= BASE_URL;?>'
    </script>
    <?php if(isset($viewData['JS'])){echo $viewData['JS'];}; ?>
</body>
</html>