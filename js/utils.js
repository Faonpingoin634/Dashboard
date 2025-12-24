
export function formatDate(dateString) {
    const d = new Date(dateString);
    if (isNaN(d.getTime())) return dateString;
    return d.toLocaleDateString("fr-FR", { day: "2-digit", month: "2-digit" });
}

export function showToast(message, type = "success") {
    const container = document.querySelector(".toast-container");
    if (!container) return;
    
    const icon = type === "danger" ? "bi-trash" : "bi-check-circle-fill";
    const colorClass = type === "danger" ? "text-danger" : "text-premium";
    
    const toastHTML = `
      <div class="toast align-items-center text-bg-dark border-0 show fade-in">
        <div class="d-flex">
          <div class="toast-body">
            <i class="bi ${icon} ${colorClass} me-2"></i> ${message}
          </div>
          <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
      </div>`;
      
    container.insertAdjacentHTML("beforeend", toastHTML);
    setTimeout(() => {
        if (container.lastElementChild) container.lastElementChild.remove();
    }, 4000);
}

// Gestion du tri
export function sortData(data, col, currentSort) {
    if (currentSort.col === col) currentSort.asc = !currentSort.asc;
    else {
        currentSort.col = col;
        currentSort.asc = true;
    }
    
    return data.sort((a, b) => {
        let valA = a[col], valB = b[col];
        if (col === "date" || col === "dueDate") {
            valA = new Date(valA);
            valB = new Date(valB);
        }
        if (valA < valB) return currentSort.asc ? -1 : 1;
        if (valA > valB) return currentSort.asc ? 1 : -1;
        return 0;
    });
}