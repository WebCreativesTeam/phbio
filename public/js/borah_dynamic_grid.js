document.addEventListener("DOMContentLoaded", function () {
  var gridItem = document.querySelector(
    ".borah_dynamic_grid .jet-listing-grid__items"
  );
  var childCount = gridItem ? gridItem.children.length : 0;

  if (gridItem) {
    gridItem.style.setProperty("--columns", childCount);
  }
});
