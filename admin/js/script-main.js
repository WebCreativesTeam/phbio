jQuery(function () {
  jQuery.fn.select2.amd.define(
    "customSingleSelectionAdapter",
    ["select2/utils", "select2/selection/single"],
    function (Utils, SingleSelection) {
      const adapter = SingleSelection;
      adapter.prototype.update = function (data) {
        if (data.length === 0) {
          this.clear();
          return;
        }
        var selection = data[0];
        var $rendered = this.$selection.find(".select2-selection__rendered");
        var formatted = this.display(selection, $rendered);
        $rendered.empty().append(formatted);
        $rendered.prop("title", selection.title || selection.text);
      };
      return adapter;
    }
  );
  function iformat(icon) {
    var originalOption = icon.element;
    return jQuery(
      '<span><i class="fa ' +
        jQuery(originalOption).data("icon") +
        '"></i> ' +
        icon.text +
        "</span>"
    );
  }
  url = document.getElementById("font-awesome-icons-list-css").href;
  fetch(url)
    .then((resp) => resp.text())
    .then((data) => {
      var cssText = data;
      var classRegex = /\.([\w-]+)/g;
      var classes = [];
      var match;
      while ((match = classRegex.exec(cssText)) !== null) {
        classes.push(match[1]);
      }
      var classes = classes.filter((cls) => cls.substring(0, 3) == "fa-");
      var toRemove = new Set([
        "fa-lg",
        "fa-2x",
        "fa-3x",
        "fa-4x",
        "fa-5x",
        "fa-fw",
        "fa-ul",
        "fa-ul",
        "fa-li",
        "fa-li",
        "fa-lg",
        "fa-border",
        "fa-pull-left",
        "fa-pull-right",
        "fa-pull-left",
        "fa-pull-right",
        "fa-spin",
        "fa-pulse",
        "fa-rotate-90",
        "fa-rotate-180",
        "fa-rotate-270",
        "fa-flip-horizontal",
        "fa-flip-vertical",
        "fa-rotate-90",
        "fa-rotate-180",
        "fa-rotate-270",
        "fa-flip-horizontal",
        "fa-flip-vertical",
        "fa-stack",
        "fa-stack-1x",
        "fa-stack-2x",
        "fa-stack-1x",
        "fa-stack-2x",
        "fa-inverse",
      ]);
      var classes = classes.filter((x) => !toRemove.has(x));
      // var sel = document.querySelector("#fontAwesomeIconList");
      var sel = document.getElementById("fontAwesomeIconList");
      if (sel !== null) {
        for (i = 0; i < classes.length; ++i) {
          let option = document.createElement("option");
          option.value = classes[i];
          option.setAttribute("data-icon", classes[i]);
          if (fontAwesomeIconList.includes(classes[i]))
            option.setAttribute("selected", "selected");
          option.innerText = classes[i];
          sel.appendChild(option);
        }
        jQuery("#fontAwesomeIconList")
          .select2({
            width: "500px",
            templateResult: iformat,
            allowHtml: true,
          })
          .on("change", function (e) {
            event.preventDefault();
            // var form = jQuery('#fontAwesomeIconListForm').serialize();
            var fontAwesomeIconList = jQuery("#fontAwesomeIconList").val();
            var action = "fontAwesomeIconListUpdate";
            jQuery.post(
              fontAwesomeIconListAjaxURL,
              {
                fontAwesomeIconList: fontAwesomeIconList,
                action: action,
              },
              function (data, textStatus, xhr) {}
            );
          });
      }
    });
  jQuery("#fontAwesomeIconListUser")
    .select2({
      width: "500px",
      templateSelection: iformat,
      templateResult: iformat,
      allowHtml: true,
      selectionAdapter: jQuery.fn.select2.amd.require(
        "customSingleSelectionAdapter"
      ),
    })
    .on("change", function (e) {
      event.preventDefault();
      var icon = jQuery(this).val();
      var action = "fontAwesomeIconListUserUpdate";
      jQuery.post(
        fontAwesomeIconListAjaxURL,
        {
          fontAwesomeIconListUser: icon,
          action: action,
        },
        function (data, textStatus, xhr) {}
      );
    });
});
