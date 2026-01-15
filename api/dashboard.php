<?php

if (!isset($_COOKIE['auth_token'])) {
    header('Location: login.php');
    exit;
}

$userName = $_COOKIE['user_name'] ?? 'Admin';
?>

<!DOCTYPE html>
<html lang="fr" data-bs-theme="light">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard</title>
    <link
      href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap"
      rel="stylesheet"
    />
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css"
    />
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css" />
  </head>
  <body>
    <div class="toast-container position-fixed bottom-0 end-0 p-3"></div>

    <header class="sticky-top z-3">
      <nav class="navbar navbar-dark">
        <div class="container-fluid px-3 px-lg-4">
          <button class="btn btn-outline-light d-lg-none me-2 border-0" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu">
            <i class="bi bi-list fs-4"></i>
          </button>

          <a class="navbar-brand fw-light letter-spacing-2 me-auto" href="#">
            <span class="fw-bold text-premium">STUDIO</span>ONYX
          </a>

          <div class="d-flex align-items-center gap-3">
            
            <button class="btn btn-link text-premium p-0 rounded-circle d-flex align-items-center justify-content-center bg-white bg-opacity-10" 
                    id="themeToggleNav" 
                    style="width: 40px; height: 40px; text-decoration: none;">
              <i class="bi bi-circle-half fs-5" id="themeIconNav"></i>
            </button>

            <div class="dropdown">
              <a href="#" class="d-flex align-items-center text-decoration-none" data-bs-toggle="dropdown">
                <img src="https://ui-avatars.com/api/?name=<?php echo $userName; ?>&background=c5a059&color=fff" alt="user" width="35" class="rounded-circle border border-1 border-premium"/>
              </a>
              <ul class="dropdown-menu dropdown-menu-end shadow border-0 position-absolute">
                <li><h6 class="dropdown-header">Bonjour, <?php echo $userName; ?></h6></li>
                <li><hr class="dropdown-divider" /></li>
                <li><a class="dropdown-item text-danger" href="auth.php?action=logout">Déconnexion</a></li>
              </ul>
            </div>
          </div>
        </div>
      </nav>
    </header>

    <div class="layout-wrapper">
      <aside class="offcanvas-lg offcanvas-start bg-black" tabindex="-1" id="sidebarMenu">
        <div class="offcanvas-header border-bottom border-secondary border-opacity-25">
          <h5 class="offcanvas-title text-premium fw-bold">MENU</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body p-0 d-flex flex-column">
          <div class="py-4 w-100">
            <ul class="nav flex-column px-3 gap-1" id="mainNav">
              <li class="nav-item">
                <a href="#" class="nav-link active d-flex align-items-center rounded-3" data-section="section-dashboard">
                  <i class="bi bi-grid-1x2 me-3 fs-5"></i> Dashboard
                </a>
              </li>
              <li class="nav-item">
                <a href="#" class="nav-link d-flex align-items-center rounded-3" data-section="section-projets">
                  <i class="bi bi-stack me-3 fs-5"></i> Projets
                </a>
              </li>
              <li class="nav-item">
                <a href="#" class="nav-link d-flex align-items-center rounded-3" data-section="section-equipe">
                  <i class="bi bi-people me-3 fs-5"></i> Équipe
                </a>
              </li>
              <li class="nav-item">
                <a href="#" class="nav-link d-flex align-items-center rounded-3" data-section="section-parametres">
                  <i class="bi bi-sliders me-3 fs-5"></i> Paramètres
                </a>
              </li>
            </ul>
          </div>
        </div>
      </aside>

      <main class="main-content d-flex flex-column p-3 p-md-4 p-lg-5">
        <div class="flex-grow-1">
          <div id="section-dashboard" class="content-section fade-in">
            <h2 class="fw-bold mb-5">Dashboard</h2>
            
            <div class="row g-3 g-xl-4 mb-5">
              <div class="col-12 col-sm-6 col-xl-3">
                <div class="card stat-card p-3 p-xl-4 border-0 shadow-sm h-100">
                  <span class="text-muted small text-uppercase fw-bold">Projets</span>
                  <h3 class="fw-bold mb-0 mt-1" id="totalProjectsCount">0</h3>
                </div>
              </div>
              <div class="col-12 col-sm-6 col-xl-3">
                <div class="card stat-card p-3 p-xl-4 border-0 shadow-sm h-100">
                  <span class="text-muted small text-uppercase fw-bold">Tâches</span>
                  <h3 class="fw-bold mb-0 mt-1" id="totalTasksCount">0</h3>
                </div>
              </div>
              <div class="col-12 col-sm-6 col-xl-3">
                <div class="card stat-card p-3 p-xl-4 border-0 shadow-sm h-100">
                  <span class="text-muted small text-uppercase fw-bold">Équipe</span>
                  <h3 class="fw-bold mb-0 mt-1" id="totalTeamCount">0</h3>
                </div>
              </div>
              <div class="col-12 col-sm-6 col-xl-3">
                <div class="card stat-card p-3 p-xl-4 border-0 shadow-sm bg-premium text-white h-100">
                  <span class="text-white-50 small text-uppercase fw-bold">Budget</span>
                  <h3 class="fw-bold mb-0 mt-1 text-white" id="totalBudgetCount">0€</h3>
                </div>
              </div>
            </div>

            <div class="card border-0 shadow-sm p-4 mb-5">
               <div class="d-flex justify-content-between align-items-center mb-4">
                   <div>
                       <h6 class="fw-bold mb-1">Analyse de la Charge</h6>
                       <p class="text-muted small mb-0">Nombre de tâches par date d'échéance.</p>
                   </div>
                   <span class="badge bg-premium-soft text-premium">Temps Réel</span>
               </div>
               <div class="chart-container" style="height: 250px;">
                  <canvas id="lineChart"></canvas>
               </div>
            </div>

            <div class="row mb-5">
              <div class="col-lg-8 mb-4 mb-lg-0">
                 <div class="card border-0 shadow-sm p-4 h-100">
                    <h6 class="fw-bold mb-4">Répartition (Statut)</h6>
                    <div class="chart-container">
                      <canvas id="doughnutChart"></canvas>
                    </div>
                 </div>
              </div>
              
              <div class="col-lg-4">
                 <div class="card border-0 shadow-sm p-4 h-100 bg-black text-white d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h5 class="fw-bold mb-1 text-premium">Performance</h5>
                            <p class="text-white-50 small mb-0">Moyenne globale des projets.</p>
                        </div>
                        <i class="bi bi-activity fs-4 text-premium"></i>
                    </div>

                    <div class="my-auto py-3">
                        <div class="d-flex justify-content-between align-items-end mb-2">
                           <span class="fw-bold display-3" id="globalProgressDisplay">0%</span>
                        </div>
                        <div class="progress bg-white bg-opacity-10 mb-2" style="height: 6px;">
                           <div class="progress-bar bg-premium" id="globalProgressBar" style="width: 0%"></div>
                        </div>
                        <small class="text-white-50">Basé sur l'ensemble des tâches actives.</small>
                    </div>

                    <div class="mt-4 pt-3 border-top border-secondary border-opacity-50">
                        <div class="row text-center g-2">
                            <div class="col-4">
                                <div class="p-2 rounded-3 bg-white bg-opacity-10 border border-secondary border-opacity-25 h-100 d-flex flex-column justify-content-center">
                                    <i class="bi bi-graph-up-arrow text-success mb-2 fs-5 d-block"></i>
                                    <span class="small fw-bold d-block text-white mb-1">Positive</span>
                                    <span class="text-white-50" style="font-size: 10px; letter-spacing: 0.5px;">TENDANCE</span>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="p-2 rounded-3 bg-white bg-opacity-10 border border-secondary border-opacity-25 h-100 d-flex flex-column justify-content-center">
                                    <i class="bi bi-lightning-charge-fill text-premium mb-2 fs-5 d-block"></i>
                                    <span class="small fw-bold d-block text-white mb-1">Stable</span>
                                    <span class="text-white-50" style="font-size: 10px; letter-spacing: 0.5px;">VÉLOCITÉ</span>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="p-2 rounded-3 bg-white bg-opacity-10 border border-secondary border-opacity-25 h-100 d-flex flex-column justify-content-center">
                                    <i class="bi bi-shield-check-fill text-info mb-2 fs-5 d-block"></i>
                                    <span class="small fw-bold d-block text-white mb-1">Faible</span>
                                    <span class="text-white-50" style="font-size: 10px; letter-spacing: 0.5px;">RISQUE</span>
                                </div>
                            </div>
                        </div>
                    </div>
                 </div>
              </div>
            </div>

            <div class="card border-0 shadow-sm p-3 p-lg-4">
              <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
                <h6 class="fw-bold mb-0 text-nowrap">Tâches Récentes</h6>
                
                <div class="d-flex flex-column flex-sm-row gap-2 w-100 flex-md-grow-1 ms-md-4">
                  <div class="input-group flex-grow-1">
                    <span class="input-group-text bg-body-tertiary border-end-0 ps-3 rounded-start-pill">
                      <i class="bi bi-search text-muted"></i>
                    </span>
                    <input type="text" id="taskSearch" class="form-control bg-body-tertiary border-start-0 rounded-end-pill" placeholder="Chercher..." />
                  </div>
                  
                  <select id="priorityFilter" class="form-select rounded-pill bg-body-tertiary w-auto" style="min-width: 170px;">
                    <option value="all">Priorité (Tout)</option>
                    <option value="Haute">Haute</option>
                    <option value="Moyenne">Moyenne</option>
                    <option value="Urgent">Urgent</option>
                    <option value="Basse">Basse</option>
                  </select>
                  
                  <button class="btn btn-primary rounded-pill px-3" onclick="window.openNewTask()">
                    <i class="bi bi-plus-lg"></i>
                  </button>
                </div>
              </div>
              <div class="table-responsive">
                <table class="table align-middle table-hover text-nowrap" id="taskTable">
                  <thead>
                    <tr>
                      <th class="cursor-pointer" onclick="handleSort('name')">Tâche <i class="bi bi-arrow-down-up sort-icon"></i></th>
                      <th>Projet</th>
                      <th>Assigné</th>
                      <th>Statut</th>
                      <th class="cursor-pointer" onclick="handleSort('priorityVal')">Priorité <i class="bi bi-arrow-down-up sort-icon"></i></th>
                      <th class="cursor-pointer" onclick="handleSort('date')">Fin <i class="bi bi-arrow-down-up sort-icon"></i></th>
                      <th class="text-end">Actions</th>
                    </tr>
                  </thead>
                  <tbody id="taskTableBody"></tbody>
                </table>
                <div id="emptyTaskState" class="text-center py-5 d-none">
                    <i class="bi bi-clipboard-x fs-1 text-muted mb-3 d-block"></i>
                    <p class="text-muted">Aucune tâche trouvée.</p>
                </div>
              </div>
            </div>
          </div>

          <div id="section-projets" class="content-section d-none fade-in">
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-4 gap-3">
              <h2 class="fw-bold mb-0">Projets</h2>
              <button class="btn btn-primary rounded-pill px-4 w-100 w-sm-auto" onclick="window.openNewProject()">
                <i class="bi bi-plus-lg me-2"></i>Nouveau Projet
              </button>
            </div>
            <div class="row g-4" id="projectsGrid"></div>
            <div id="emptyProjectState" class="text-center py-5 d-none">
                <i class="bi bi-folder-x fs-1 text-muted mb-3 d-block"></i>
                <h5 class="text-muted">Aucun projet</h5>
                <p class="text-muted small">Créez votre premier projet pour commencer.</p>
            </div>
          </div>

          <div id="section-equipe" class="content-section d-none fade-in">
            <h2 class="fw-bold mb-4">Équipe</h2>
            <div class="row g-3 g-xl-4">
              <div class="col-12 col-sm-6 col-xl-4 text-center">
                <div class="card border-0 shadow-sm p-4 h-100 stat-card">
                  <img src="https://ui-avatars.com/api/?name=<?php echo $_SESSION['user']['name']; ?>&background=c5a059&color=fff" />
                  <h5 class="fw-bold mb-1">Alice</h5>
                  <p class="text-muted small">Lead Designer</p>
                </div>
              </div>
              <div class="col-12 col-sm-6 col-xl-4 text-center">
                <div class="card border-0 shadow-sm p-4 h-100 stat-card">
                  <img src="https://ui-avatars.com/api/?name=Marc&background=000&color=fff" class="rounded-circle mb-3 mx-auto" width="80" />
                  <h5 class="fw-bold mb-1">Marc</h5>
                  <p class="text-muted small">Dev Back-End</p>
                </div>
              </div>
              <div class="col-12 col-sm-6 col-xl-4 text-center">
                <div class="card border-0 shadow-sm p-4 h-100 stat-card">
                  <img src="https://ui-avatars.com/api/?name=Julie&background=c5a059&color=fff" class="rounded-circle mb-3 mx-auto" width="80" />
                  <h5 class="fw-bold mb-1">Julie</h5>
                  <p class="text-muted small">Cloud Ops</p>
                </div>
              </div>
              <div class="col-12 col-sm-6 col-xl-4 text-center">
                <div class="card border-0 shadow-sm p-4 h-100 stat-card">
                  <img src="https://ui-avatars.com/api/?name=Thomas&background=000&color=fff" class="rounded-circle mb-3 mx-auto" width="80" />
                  <h5 class="fw-bold mb-1">Thomas</h5>
                  <p class="text-muted small">Lead Front-End</p>
                </div>
              </div>
              <div class="col-12 col-sm-6 col-xl-4 text-center">
                <div class="card border-0 shadow-sm p-4 h-100 stat-card">
                  <img src="https://ui-avatars.com/api/?name=Sarah&background=c5a059&color=fff" class="rounded-circle mb-3 mx-auto" width="80" />
                  <h5 class="fw-bold mb-1">Sarah</h5>
                  <p class="text-muted small">Marketing Manager</p>
                </div>
              </div>
            </div>
          </div>

          <div id="section-parametres" class="content-section d-none fade-in">
            <h2 class="fw-bold mb-4">Paramètres</h2>
            <div class="card border-0 shadow-sm p-4">
              <div class="d-flex align-items-center justify-content-between p-3 border-bottom">
                  <div>
                    <h6 class="mb-1 fw-bold">Mode Sombre</h6>
                    <p class="text-muted small mb-0">Activer le thème nuit.</p>
                  </div>
                  <div class="form-check form-switch fs-4">
                    <input class="form-check-input custom-switch" type="checkbox" id="darkModeSwitch" />
                  </div>
              </div>
              <div class="d-flex align-items-center justify-content-between p-3 pt-4">
                  <div>
                    <h6 class="mb-1 fw-bold text-danger">Zone de Danger</h6>
                    <p class="text-muted small mb-0">Réinitialiser toutes les données.</p>
                  </div>
                  <button class="btn btn-outline-danger btn-sm rounded-pill" onclick="window.resetAllData()">
                      <i class="bi bi-exclamation-triangle me-1"></i> Réinitialiser
                  </button>
              </div>
            </div>
          </div>
        </div>

        <footer class="main-footer mt-5 pt-4 border-top">
          <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
            <div class="text-muted small">
              © <?php echo date('Y'); ?> <span class="fw-bold text-premium">STUDIO</span>ONYX. Tous droits réservés.
            </div>
            <ul class="list-inline mb-0 small">
              <li class="list-inline-item">
                <a href="#" class="text-decoration-none text-muted footer-link" data-bs-toggle="modal" data-bs-target="#contactModal">Support / Contact</a>
              </li>
            </ul>
          </div>
        </footer>
      </main>
    </div>

    <div class="modal fade" id="newProjectModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden">
          <div class="modal-header bg-black text-white border-0 py-4">
            <h5 class="modal-title fw-bold">Nouveau Projet</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
          </div>
          <form id="projectForm">
            <input type="hidden" id="projectIdInput" />
            <div class="modal-body p-4">
              <div class="mb-3">
                <label class="form-label small fw-bold">NOM DU PROJET</label>
                <input type="text" name="name" class="form-control rounded-pill bg-body-tertiary border-0" placeholder="Ex: Refonte Site Web" required />
              </div>
              <div class="mb-3">
                <label class="form-label small fw-bold">DESCRIPTION</label>
                <textarea name="desc" class="form-control bg-body-tertiary border-0 rounded-4" rows="3" placeholder="Détails du projet..." required></textarea>
              </div>
              <div class="mb-3">
                <label class="form-label small fw-bold">IMAGE URL (Optionnel)</label>
                <input type="text" name="img" class="form-control rounded-pill bg-body-tertiary border-0" placeholder="https://..." />
              </div>
              <div class="mb-3">
                <label class="form-label small fw-bold">DATE DE FIN</label>
                <input type="date" name="dueDate" class="form-control rounded-pill bg-body-tertiary border-0" required />
              </div>
              <div class="mb-3">
                <label class="form-label small fw-bold">BUDGET (€)</label>
                <input type="number" name="budget" class="form-control rounded-pill bg-body-tertiary border-0" placeholder="Ex: 4500" min="0" required />
              </div>
              <div class="alert alert-light border small text-muted">
                  <i class="bi bi-info-circle me-1"></i> La progression sera calculée automatiquement.
              </div>
            </div>
            <div class="modal-footer border-0 p-4 pt-0 d-flex justify-content-end gap-2">
              <button type="button" class="btn btn-link text-decoration-none text-muted" data-bs-dismiss="modal">Annuler</button>
              <button type="submit" class="btn btn-primary rounded-pill px-4">Enregistrer</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <div class="modal fade" id="taskModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden">
          <div class="modal-header bg-black text-white border-0 py-4">
            <h5 class="modal-title fw-bold" id="taskModalTitle">Nouvelle Tâche</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
          </div>
          <form id="taskForm">
            <input type="hidden" id="taskIdInput" />
            <div class="modal-body p-4">
              <div class="mb-3">
                <label class="form-label small fw-bold">PROJET ASSOCIÉ</label>
                <select name="taskProject" id="taskProjectInput" class="form-select rounded-pill bg-body-tertiary border-0" required></select>
              </div>
              <div class="mb-3">
                <label class="form-label small fw-bold">NOM DE LA TÂCHE</label>
                <input type="text" name="taskName" id="taskNameInput" class="form-control rounded-pill bg-body-tertiary border-0" required />
              </div>
              <div class="row">
                <div class="col-6 mb-3">
                  <label class="form-label small fw-bold">ASSIGNÉ À</label>
                  <select name="taskAssigned" id="taskAssignedInput" class="form-select rounded-pill bg-body-tertiary border-0">
                    <option value="Alice">Alice</option>
                    <option value="Marc">Marc</option>
                    <option value="Julie">Julie</option>
                    <option value="Thomas">Thomas</option>
                    <option value="Sarah">Sarah</option>
                  </select>
                </div>
                <div class="col-6 mb-3">
                  <label class="form-label small fw-bold">DATE</label>
                  <input type="date" name="taskDate" id="taskDateInput" class="form-control rounded-pill bg-body-tertiary border-0" required />
                </div>
              </div>
              <div class="row">
                <div class="col-6 mb-3">
                  <label class="form-label small fw-bold">PRIORITÉ</label>
                  <select name="taskPriority" id="taskPriorityInput" class="form-select rounded-pill bg-body-tertiary border-0">
                    <option value="Basse">Basse</option>
                    <option value="Moyenne">Moyenne</option>
                    <option value="Haute">Haute</option>
                    <option value="Urgent">Urgent</option>
                  </select>
                </div>
                <div class="col-6 mb-3">
                  <label class="form-label small fw-bold">STATUT</label>
                  <select name="taskStatus" id="taskStatusInput" class="form-select rounded-pill bg-body-tertiary border-0">
                    <option value="Attente">Attente</option>
                    <option value="En cours">En cours</option>
                    <option value="Fait">Fait</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="modal-footer border-0 p-4 pt-0 d-flex justify-content-end gap-2">
              <button type="button" class="btn btn-link text-decoration-none text-muted" data-bs-dismiss="modal">Annuler</button>
              <button type="submit" class="btn btn-primary rounded-pill px-4">Sauvegarder</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <div class="modal fade" id="contactModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 rounded-4 shadow-lg">
                <div class="modal-header bg-premium text-white border-0">
                    <h5 class="modal-title fw-bold">Support Elite</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <p class="text-muted small">Rencontrez-vous un problème ? Décrivez-le ci-dessous.</p>
                    <textarea id="contactMessage" class="form-control bg-body-tertiary border-0 rounded-4 mb-3" rows="4" placeholder="Votre message..."></textarea>
                    <button class="btn btn-dark w-100 rounded-pill" onclick="window.sendSupportMessage()">Envoyer la demande</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="module" src="js/main.js"></script>
  </body>
</html>