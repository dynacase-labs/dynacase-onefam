(function() {
  var setFirst;

  setFirst = function setFirst() {
    var first = $("#preffirstfam").val(),
      firstDiv = $("#div_" + first),
      secondDiv;

    secondDiv = firstDiv.children(":not(input)").clone();
    $("#openfirst").empty().append(secondDiv).children("label")
      .removeAttr("for")
      .removeClass("css-fam-selected")
      .css("backgroundColor", '')
      .attr("title", '');
  };

  $(document).ready(function() {
    $(".js-selection-label").on({
      mouseover: function() {
        var $this = $(this);
        if ($("#" + $this.prop("for")).prop("checked")) {
          $(this).addClass("css-fam-selected")
            .css("backgroundColor", window.onefamPref.core_bgcolorhigh)
            .attr("title", window.onefamPref.textFirstView);
        }
      },
      mouseout: function() {
        $(this).removeClass("css-fam-selected")
          .css("backgroundColor", '')
          .attr("title", '');
      },
      click: function() {
        var $this = $(this),
          $input = $("#" + $this.prop("for"));
        if ($input.prop("checked")) {
          $("#preffirstfam").val($input.val());
          setFirst();
          return false;
        }
      }
    });

    $(".js-remove-first").on("click", function() {
      $("#preffirstfam").val('');
      setFirst();
      return false;
    });

    $(".js-fam-checkbox").on("change", function() {
      var $this = $(this),
        $preffirstfam = $("#preffirstfam");
      if (!$this.prop("checked") && $this.val() === $preffirstfam.val()) {
        $preffirstfam.val("");
        setFirst();
      }
    });

    $("#close").on("click", function() {
      if (window === window.top) {
        window.close();
      } else {
        window.location.href = "Images/1x1.gif";
      }
    });

    $("#mainForm").on("submit", function() {
      var $mainForm = $("#mainForm"),
        data = $("#mainForm").serializeArray();
      if (window !== window.top) {
        $.post($mainForm.attr("action"), data, function() {
          window.location.href = "Images/1x1.gif";
        });
        return false;
      } else {
        window.setTimeout(function() {
          window.close();
        }, 500);
      }
    });

    setFirst();
  });
}());