document
  .querySelector("#addToOtherModalForm")
  .addEventListener("submit", function (e) {
    e.preventDefault();

    let formData = new FormData(this);

    fetch(this.action, {
      method: "POST",
      body: formData,
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.status === "success") {
          Swal.fire({
            icon: "success",
            title: "Saved!",
            text: data.msg,
            showConfirmButton: false,
            timerProgressBar: true,
            timer: 1500,
          }).then(() => {
            window.location.href = "../../pages/other/other.php";
          });
        } else {
          Swal.fire({
            icon: "error",
            title: "Oops...",
            text: data.msg,
            showConfirmButton: false,
            timerProgressBar: true,
            timer: 1500,
          });
        }
      })
      .catch((error) => {
        console.error("Error:", error);
        Swal.fire({
          icon: "error",
          title: "System Error",
          text: "Error connecting to the server.",
        });
      });
  });

function openOtherModal() {
  const modal = document.getElementById("addToOtherModal");
  if (modal) {
    modal.style.display = "flex";
  } else {
    console.error("Modal element 'addToOtherModal' not found in the DOM.");
  }
}

function closeOtherModal() {
  const modal = document.getElementById("addToOtherModal");
  if (modal) {
    modal.style.display = "none";

    const form = document.getElementById("#addToOtherModalForm");
    if (form) form.reset();

    const preview = document.getElementById("preview_container");
    if (preview)
      preview.innerHTML = '<span id="placeholder_text">No Image</span>';
  }
}
