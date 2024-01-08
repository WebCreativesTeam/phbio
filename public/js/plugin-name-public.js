function adjustTextareaHeight(className) {
  var textareas = document.getElementsByClassName(className);
  for (var i = 0; i < textareas.length; i++) {
    textareas[i].style.height = ""; // Reset the height
    textareas[i].style.height = textareas[i].scrollHeight + "px";
  }
}

// Initial adjustment for all textareas with the specified class
adjustTextareaHeight("dynamic-textarea");

// Adjust height whenever window is resized
window.addEventListener("resize", function () {
  adjustTextareaHeight("dynamic-textarea");
});
