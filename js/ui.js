// js/ui.js
import { formatDate } from "./utils.js";

// --- GESTION DES GRAPHIQUES ---
let doughnutChart = null;
let lineChart = null;

export function updateStats(projects, tasks) {
  // Compteurs simples
  document.getElementById("totalProjectsCount").textContent = projects.length;
  document.getElementById("totalTasksCount").textContent = tasks.length;

  const totalBudget = projects.reduce(
    (acc, curr) => acc + (curr.budget || 0),
    0
  );
  document.getElementById("totalBudgetCount").textContent =
    new Intl.NumberFormat("fr-FR", {
      style: "currency",
      currency: "EUR",
      maximumFractionDigits: 0,
    }).format(totalBudget);

  const uniqueMembers = new Set(tasks.map((t) => t.assigned));
  document.getElementById("totalTeamCount").textContent = uniqueMembers.size;

  // Barre de progression globale
  const totalTasks = tasks.length;
  const completedTasks = tasks.filter((t) => t.status === "Fait").length;
  const globalProgress = totalTasks
    ? Math.round((completedTasks / totalTasks) * 100)
    : 0;

  document.getElementById("globalProgressDisplay").textContent =
    globalProgress + "%";
  document.getElementById("globalProgressBar").style.width =
    globalProgress + "%";

  updateCharts(tasks);
}

function updateCharts(tasks) {
  // Doughnut Chart
  const stats = { "En cours": 0, Attente: 0, Fait: 0 };
  tasks.forEach((t) => {
    if (stats[t.status] !== undefined) stats[t.status]++;
  });
  const dataValues = [stats["En cours"], stats["Attente"], stats["Fait"]];

  const ctxDoughnut = document.getElementById("doughnutChart");
  if (ctxDoughnut) {
    if (doughnutChart) {
      doughnutChart.data.datasets[0].data = dataValues;
      doughnutChart.update();
    } else {
      doughnutChart = new Chart(ctxDoughnut.getContext("2d"), {
        type: "doughnut",
        data: {
          labels: ["En cours", "Attente", "Fait"],
          datasets: [
            {
              data: dataValues,
              backgroundColor: ["#c5a059", "#6c757d", "#198754"],
              borderWidth: 0,
            },
          ],
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          cutout: "70%",
          plugins: { legend: { position: "bottom" } },
        },
      });
    }
  }

  // Line Chart
  const dateCounts = {};
  [...tasks]
    .sort((a, b) => new Date(a.date) - new Date(b.date))
    .forEach((t) => {
      const d = new Date(t.date).toLocaleDateString("fr-FR", {
        day: "numeric",
        month: "short",
      });
      dateCounts[d] = (dateCounts[d] || 0) + 1;
    });

  const ctxLine = document.getElementById("lineChart");
  if (ctxLine) {
    const labels = Object.keys(dateCounts);
    const data = Object.values(dateCounts);

    if (lineChart) {
      lineChart.data.labels = labels;
      lineChart.data.datasets[0].data = data;
      lineChart.update();
    } else {
      lineChart = new Chart(ctxLine.getContext("2d"), {
        type: "line",
        data: {
          labels: labels,
          datasets: [
            {
              label: "Tâches",
              data: data,
              borderColor: "#c5a059",
              backgroundColor: "rgba(197, 160, 89, 0.1)",
              fill: true,
              tension: 0.4,
            },
          ],
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: { legend: { display: false } },
          scales: { x: { grid: { display: false } } },
        },
      });
    }
  }
}

// --- RENDU PROJETS ---
export function renderProjects(projects, tasks) {
  const container = document.getElementById("projectsGrid");
  container.innerHTML = "";

  if (projects.length === 0) {
    document.getElementById("emptyProjectState").classList.remove("d-none");
    return;
  }
  document.getElementById("emptyProjectState").classList.add("d-none");

  projects.forEach((p) => {
    const dateFormatted = new Date(p.dueDate).toLocaleDateString("fr-FR", {
      day: "numeric",
      month: "short",
      year: "numeric",
    });
    const pTasks = tasks.filter((t) => t.projectId == p.id);
    const pDone = pTasks.filter((t) => t.status === "Fait").length;
    let calculatedProgress = pTasks.length
      ? Math.round((pDone / pTasks.length) * 100)
      : 0;

    const html = `
        <div class="col-12 col-md-6 col-xl-4 fade-in">
            <div class="card project-card border-0 shadow-sm h-100 overflow-hidden">
                <div class="project-img-wrapper">
                    <img src="${p.img}" class="card-img-top" alt="${
      p.title
    }" loading="lazy">
                </div>
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h5 class="fw-bold mb-0 text-truncate" style="max-width: 70%;">${
                          p.title
                        }</h5>
                        <span class="badge bg-light text-black border shadow-sm">${
                          p.budget ? p.budget + " €" : "0 €"
                        }</span>
                    </div>
                    <p class="text-muted small mb-3 text-truncate">${p.desc}</p>
                    <div class="d-flex align-items-center text-muted small mb-2">
                        <i class="bi bi-calendar-event me-2"></i> Fin : ${dateFormatted}
                    </div>
                    <div class="progress mb-4" style="height: 6px" title="${calculatedProgress}%">
                        <div class="progress-bar bg-premium" style="width: ${calculatedProgress}%"></div>
                    </div>
                    <div class="d-flex gap-2">
                        <button onclick="window.editProject(${
                          p.id
                        })" class="btn btn-outline-dark btn-sm flex-grow-1 rounded-pill">
                            <i class="bi bi-pencil-square me-1"></i> Modifier
                        </button>
                        <button onclick="window.deleteProject(${
                          p.id
                        })" class="btn btn-outline-danger btn-sm rounded-pill px-3">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>`;
    container.insertAdjacentHTML("beforeend", html);
  });
}

// --- RENDU TACHES ---
export function renderTasks(tasks, projects) {
  const tbody = document.getElementById("taskTableBody");
  tbody.innerHTML = "";

  if (tasks.length === 0) {
    document.getElementById("emptyTaskState").classList.remove("d-none");
    document.getElementById("taskTable").classList.add("d-none");
    return;
  }
  document.getElementById("emptyTaskState").classList.add("d-none");
  document.getElementById("taskTable").classList.remove("d-none");

  const today = new Date();
  today.setHours(0, 0, 0, 0);

  tasks.forEach((t) => {
    const parentProject = projects.find((p) => p.id == t.projectId);
    const projectName = parentProject
      ? parentProject.title
      : "<span class='text-muted fst-italic'>Non assigné</span>";

    let priorityClass = "bg-secondary";
    if (t.priority === "Haute" || t.priority === "Urgent")
      priorityClass = "bg-danger";
    if (t.priority === "Moyenne") priorityClass = "bg-warning text-dark";
    if (t.priority === "Basse") priorityClass = "bg-info text-white";

    let statusBadge = `<span class="badge bg-secondary-subtle text-secondary">Attente</span>`;
    if (t.status === "En cours")
      statusBadge = `<span class="badge bg-premium-soft text-premium">En cours</span>`;
    if (t.status === "Fait")
      statusBadge = `<span class="badge bg-success-subtle text-success">Fait</span>`;

    let dateDisplay = formatDate(t.date);
    if (new Date(t.date) < today && t.status !== "Fait") {
      dateDisplay = `<span class="text-danger fw-bold"><i class="bi bi-exclamation-circle-fill me-1"></i> ${dateDisplay}</span>`;
    }

    const html = `
        <tr class="task-row fade-in">
            <td class="fw-semibold cursor-pointer" onclick="window.editTask(${t.id})">${t.name}</td>
            <td><small class="text-muted">${projectName}</small></td>
            <td><img src="https://ui-avatars.com/api/?name=${t.assigned}&size=24&background=random" class="rounded-circle me-2" />${t.assigned}</td>
            <td>${statusBadge}</td>
            <td><span class="badge ${priorityClass} rounded-pill">${t.priority}</span></td>
            <td>${dateDisplay}</td>
            <td class="text-end">
                <button onclick="window.editTask(${t.id})" class="btn btn-sm btn-link text-dark p-0 me-2"><i class="bi bi-pencil"></i></button>
                <button onclick="window.deleteTask(${t.id})" class="btn btn-sm btn-link text-danger p-0"><i class="bi bi-trash"></i></button>
            </td>
        </tr>`;
    tbody.insertAdjacentHTML("beforeend", html);
  });
}

export function toggleLoading(isLoading) {
  const main = document.querySelector(".main-content");
  let overlay = document.getElementById("globalLoadingOverlay");

  if (isLoading) {
    if (!overlay) {
      overlay = document.createElement("div");
      overlay.id = "globalLoadingOverlay";
      overlay.className = "loading-overlay";
      overlay.innerHTML = '<div class="spinner"></div>';
      main.style.position = "relative";
      main.appendChild(overlay);
    }
  } else {
    if (overlay) overlay.remove();
  }
}
