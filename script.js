function applyBootstrapValidation(formId) {
  const form = document.getElementById(formId);
  if (!form) return;

  form.addEventListener("submit", function (event) {
    if (!form.checkValidity()) {
      event.preventDefault();
      event.stopPropagation();
    }
    form.classList.add("was-validated");
  }, false);
}

applyBootstrapValidation("createForm");
applyBootstrapValidation("updateForm");

document.querySelectorAll(".btn-delete").forEach(function(btn) {
  btn.addEventListener("click", function(e) {
    const user = btn.getAttribute("data-user") || "korisnika";
    const ok = confirm("Da li si siguran/na da želiš obrisati " + user + "?");
    if (!ok) {
      e.preventDefault();
    }
  });
});

const searchInput = document.getElementById("searchInput");
if (searchInput) {
  searchInput.addEventListener("input", function() {
    const q = searchInput.value.toLowerCase();
    document.querySelectorAll("#usersTable tbody tr").forEach(function(tr) {
      const text = tr.innerText.toLowerCase();
      tr.style.display = text.includes(q) ? "" : "none";
    });
  });
}
