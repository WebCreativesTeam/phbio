function checkUsernameAvailability() {
  let username = document.getElementById("username").value;

  let formData = new FormData();
  formData.append("action", "callback");
  formData.append("username", username);
  formData.append("nonce", plugin.nonce);

  // Use AJAX to send this data to server to check
  fetch(plugin.ajax_url, {
    method: "POST",
    body: formData,
  })
    .then((response) => response.json())
    .then((data) => {
      console.log(data);
      if (data.available) {
        alert("Username is available.");
      } else {
        alert("Username is already taken.");
      }
    })
    .catch((error) => {
      console.error("Error:", error);
    });
}
