// js/main.js
import * as API from "./api.js";
import * as UI from "./ui.js";
import * as Utils from "./utils.js";

// État Global
let state = {
  projects: [],
  tasks: [],
  sort: { col: null, asc: true },
};

// --- INITIALISATION ---
document.addEventListener("DOMContentLoaded", async () => {
  setupTheme();
  setupNavigation();
  setupEventListeners();
  await loadData();
});

// --- LOGIQUE DATA ---
async function loadData() {
  UI.toggleLoading(true);
  try {
    const data = await API.fetchAllData();
    state.projects = data.projects || [];
    state.tasks = data.tasks || [];
    refreshUI();
  } catch (e) {
    Utils.showToast("Erreur de chargement (Mode hors ligne)", "danger");
    // Données par défaut si erreur
    state.projects = [
      {
        id: 1,
        title: "Projet démo",
        desc: "Local",
        budget: 0,
        dueDate: "2024-12-31",
        img: "https://picsum.photos/500/300",
      },
    ];
    refreshUI();
  } finally {
    UI.toggleLoading(false);
  }
}

async function syncData() {
  try {
    await API.saveDataToServer(state.projects, state.tasks);
    // On ne recharge pas tout pour garder la fluidité, mais on notifie
  } catch (e) {
    Utils.showToast("Erreur de sauvegarde", "danger");
  }
}

function refreshUI() {
  UI.renderProjects(state.projects, state.tasks);
  UI.renderTasks(state.tasks, state.projects);
  UI.updateStats(state.projects, state.tasks);
}

// --- FONCTIONS EXPOSÉES (Globale pour le HTML) ---
window.deleteProject = function (id) {
  Swal.fire({
    title: "Êtes-vous sûr ?",
    text: "Irréversible.",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#d33",
    confirmButtonText: "Oui",
  }).then(async (result) => {
    if (result.isConfirmed) {
      state.projects = state.projects.filter((p) => p.id !== id);
      refreshUI();
      await syncData();
      Utils.showToast("Projet supprimé");
    }
  });
};

window.deleteTask = function (id) {
  Swal.fire({
    title: "Supprimer ?",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#d33",
  }).then(async (result) => {
    if (result.isConfirmed) {
      state.tasks = state.tasks.filter((t) => t.id !== id);
      refreshUI();
      await syncData();
      Utils.showToast("Tâche supprimée");
    }
  });
};

window.openNewProject = () => {
  document.getElementById("projectForm").reset();
  document.getElementById("projectIdInput").value = "";
  document.querySelector("#newProjectModal .modal-title").textContent =
    "Nouveau Projet";
  new bootstrap.Modal(document.getElementById("newProjectModal")).show();
};

window.editProject = (id) => {
  const p = state.projects.find((p) => p.id === id);
  if (!p) return;
  const form = document.getElementById("projectForm");
  document.getElementById("projectIdInput").value = p.id;
  form.elements["name"].value = p.title;
  form.elements["desc"].value = p.desc;
  form.elements["img"].value = p.img;
  form.elements["dueDate"].value = p.dueDate;
  form.elements["budget"].value = p.budget || 0;
  document.querySelector("#newProjectModal .modal-title").textContent =
    "Modifier le Projet";
  new bootstrap.Modal(document.getElementById("newProjectModal")).show();
};

window.openNewTask = () => {
  document.getElementById("taskForm").reset();
  document.getElementById("taskIdInput").value = "";
  populateProjectSelect();
  new bootstrap.Modal(document.getElementById("taskModal")).show();
};

window.editTask = (id) => {
  const t = state.tasks.find((t) => t.id === id);
  if (!t) return;
  populateProjectSelect();
  document.getElementById("taskIdInput").value = t.id;
  document.getElementById("taskNameInput").value = t.name;
  document.getElementById("taskAssignedInput").value = t.assigned;
  document.getElementById("taskDateInput").value = t.date;
  document.getElementById("taskPriorityInput").value = t.priority;
  document.getElementById("taskStatusInput").value = t.status;
  document.getElementById("taskProjectInput").value = t.projectId || "";
  new bootstrap.Modal(document.getElementById("taskModal")).show();
};

window.handleSort = (col) => {
  state.tasks = Utils.sortData(state.tasks, col, state.sort);
  refreshUI();
};

window.sendSupportMessage = async () => {
  const msgInput = document.getElementById("contactMessage");
  const btn = document.querySelector("#contactModal button");

  if (!msgInput.value) return Utils.showToast("Message vide", "danger");

  btn.classList.add("btn-loading");
  try {
    const res = await API.sendSupportEmail(msgInput.value);
    bootstrap.Modal.getInstance(document.getElementById("contactModal")).hide();
    msgInput.value = "";
    Swal.fire("Envoyé !", res.message, "success");
  } catch (e) {
    Utils.showToast("Erreur d'envoi", "danger");
  } finally {
    btn.classList.remove("btn-loading");
  }
};

window.resetAllData = () => {
  Swal.fire({
    title: "Tout effacer ?",
    icon: "error",
    showCancelButton: true,
    confirmButtonColor: "#d33",
  }).then(async (res) => {
    if (res.isConfirmed) {
      state.projects = [];
      state.tasks = [];
      await syncData();
      location.reload();
    }
  });
};

// --- EVENTS LISTENERS ---
function setupEventListeners() {
  // Formulaire Projet
  document
    .getElementById("projectForm")
    .addEventListener("submit", async function (e) {
      e.preventDefault();
      const formData = new FormData(this);
      const id = document.getElementById("projectIdInput").value;
      const dataObj = {
        title: formData.get("name"),
        desc: formData.get("desc"),
        img:
          formData.get("img") ||
          `https://picsum.photos/500/300?random=${Math.random()}`,
        dueDate: formData.get("dueDate"),
        budget: Number(formData.get("budget")) || 0,
      };

      if (id) {
        const index = state.projects.findIndex((p) => p.id == id);
        if (index > -1)
          state.projects[index] = { ...state.projects[index], ...dataObj };
      } else {
        state.projects.unshift({ id: Date.now(), ...dataObj });
      }

      bootstrap.Modal.getInstance(
        document.getElementById("newProjectModal")
      ).hide();
      refreshUI();
      await syncData();
      Utils.showToast(id ? "Projet modifié" : "Projet créé");
    });

  // Formulaire Tâche
  document
    .getElementById("taskForm")
    .addEventListener("submit", async function (e) {
      e.preventDefault();
      const id = document.getElementById("taskIdInput").value;
      const priority = document.getElementById("taskPriorityInput").value;
      const priorityVal =
        priority === "Urgent"
          ? 4
          : priority === "Haute"
          ? 3
          : priority === "Moyenne"
          ? 2
          : 1;

      const newTask = {
        projectId: document.getElementById("taskProjectInput").value,
        name: document.getElementById("taskNameInput").value,
        assigned: document.getElementById("taskAssignedInput").value,
        date: document.getElementById("taskDateInput").value,
        priority: priority,
        priorityVal: priorityVal,
        status: document.getElementById("taskStatusInput").value,
      };

      if (id) {
        const index = state.tasks.findIndex((t) => t.id == id);
        if (index > -1)
          state.tasks[index] = { ...state.tasks[index], ...newTask };
      } else {
        state.tasks.unshift({ id: Date.now(), ...newTask });
      }

      bootstrap.Modal.getInstance(document.getElementById("taskModal")).hide();
      refreshUI();
      await syncData();
      Utils.showToast("Tâche sauvegardée");
    });

  // Recherche
  document
    .getElementById("taskSearch")
    .addEventListener("keyup", (e) => filterTasks(e.target.value));
}

// Helpers internes
function populateProjectSelect() {
  const select = document.getElementById("taskProjectInput");
  select.innerHTML = "";
  state.projects.forEach((p) => {
    const option = document.createElement("option");
    option.value = p.id;
    option.textContent = p.title;
    select.appendChild(option);
  });
}

function filterTasks(term) {
  const lowerTerm = term.toLowerCase();
  const filtered = state.tasks.filter(
    (t) =>
      t.name.toLowerCase().includes(lowerTerm) ||
      t.assigned.toLowerCase().includes(lowerTerm)
  );
  UI.renderTasks(filtered, state.projects);
}

function setupTheme() {
  const html = document.documentElement;
  const switchEl = document.getElementById("darkModeSwitch");
  const currentTheme = localStorage.getItem("theme") || "light";

  const apply = (theme) => {
    html.setAttribute("data-bs-theme", theme);
    localStorage.setItem("theme", theme);
    if (switchEl) switchEl.checked = theme === "dark";
    document.getElementById("themeIconNav").className =
      theme === "dark" ? "bi bi-sun-fill" : "bi bi-moon-stars-fill";
    refreshUI(); // Pour les charts
  };

  apply(currentTheme);
  document.getElementById("themeToggleNav").addEventListener("click", () => {
    apply(html.getAttribute("data-bs-theme") === "light" ? "dark" : "light");
  });
  if (switchEl)
    switchEl.addEventListener("change", () =>
      apply(switchEl.checked ? "dark" : "light")
    );
}

function setupNavigation() {
  const navLinks = document.querySelectorAll("#mainNav .nav-link");
  const sections = document.querySelectorAll(".content-section");

  navLinks.forEach((link) => {
    link.addEventListener("click", function (e) {
      e.preventDefault();
      navLinks.forEach((l) => l.classList.remove("active"));
      this.classList.add("active");
      sections.forEach((s) => {
        s.classList.add("d-none");
        s.classList.remove("fade-in");
      });

      const target = document.getElementById(this.getAttribute("data-section"));
      target.classList.remove("d-none");
      void target.offsetWidth; // Trigger reflow
      target.classList.add("fade-in");

      // Fermeture mobile
      const sidebar = document.getElementById("sidebarMenu");
      if (window.innerWidth < 992 && sidebar.classList.contains("show")) {
        const bsOffcanvas = bootstrap.Offcanvas.getInstance(sidebar);
        if (bsOffcanvas) bsOffcanvas.hide();
      }
    });
  });
}
