function sortTask(colIndex) {
    const table = document.getElementById("allTasks");
    const rows = Array.from(table.rows).slice(1); // skip header
    const asc = table.getAttribute("data-sort-dir") !== "asc"; // toggle

    table.querySelectorAll("th").forEach(th => th.classList.remove("asc", "desc"));
    table.rows[0].cells[colIndex].classList.add(asc ? "asc" : "desc");

    rows.sort((a, b) => {
        const A = a.cells[colIndex].textContent.trim().toLowerCase();
        const B = b.cells[colIndex].textContent.trim().toLowerCase();

        const isNum = !isNaN(Date.parse(A)) || !isNaN(A);

        if (isNum) {
            return asc ? (A > B ? 1 : -1) : (A < B ? 1 : -1);
        }

        return asc ? A.localeCompare(B) : B.localeCompare(A);
    });

    // Append sorted rows
    rows.forEach(row => table.tBodies[0].appendChild(row));

    // Store current sort direction
    table.setAttribute("data-sort-dir", asc ? "asc" : "desc");
}