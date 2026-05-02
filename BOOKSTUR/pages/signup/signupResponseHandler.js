document.querySelector("#signupForm").addEventListener("submit", function (e) {
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
          window.location.href = "../../index.php";
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
