export const linkManager = () => ({
  isOpen: false,
  removeImage: function (index) {
    if (!confirm("Are you sure you want to remove this image?")) return;

    let data = new FormData();
    data.append("action", "handle_remove_gallery_image");
    data.append("index", index);

    fetch(plugin.ajax_url, {
      method: "POST",
      body: data,
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          location.reload();
        } else {
          alert("Failed to remove image");
        }
      })
      .catch((err) => {
        console.log(err);
      });
  },
});
