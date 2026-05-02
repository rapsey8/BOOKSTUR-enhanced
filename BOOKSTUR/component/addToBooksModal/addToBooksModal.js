document.querySelector("#addToBooksModalForm").addEventListener("submit", function (e) {
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
            window.location.href = "../../pages/library/library.php";
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

function openAppendModal() {
  const modal = document.getElementById("appendModal");
  if (modal) {
    modal.style.display = "flex";
  } else {
    console.error("Modal element 'appendModal' not found in the DOM.");
  }
}

function closeAppendModal() {
  const modal = document.getElementById("appendModal");
  if (modal) {
    modal.style.display = "none";

    const form = document.getElementById("appendForm");
    if (form) form.reset();

    const preview = document.getElementById("preview_container");
    if (preview)
      preview.innerHTML = '<span id="placeholder_text">No Image</span>';
  }
}

function previewProductImage(input) {
  const container = document.getElementById("preview_container");

  if (input.files && input.files[0]) {
    const reader = new FileReader();

    reader.onload = function (e) {
      container.innerHTML = `
                <img src="${e.target.result}" 
                     style="max-width: 100%; max-height: 100%; object-fit: contain;">
            `;
    };

    reader.readAsDataURL(input.files[0]);
  } else {
    container.innerHTML = '<span id="placeholder_text">No Image</span>';
  }
}
