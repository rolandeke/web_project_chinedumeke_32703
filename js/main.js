$(document).ready(() => {
  let request;
  $("#medicineForm").submit(function (e) {
    e.preventDefault();
    e.stopPropagation();

    let errors = [];
    if ($("#medicine_name").val().length <= 0) {
      errors.push("Please enter medicine name");
    }

    if ($("#dosage_qty").val().length <= 0) {
      errors.push("Please enter dosage quantity");
    }
    if ($("#dosage_unit").val().length <= 0) {
      errors.push("Please enter dosage unit");
    }
    if ($("#milligrams").val().length <= 0) {
      errors.push("Please enter milligrams");
    }
    if ($("#frequency_qty").val().length <= 0) {
      errors.push("Please enter frequency quantity");
    }

    if ($("#frequency_unit").val().length <= 0) {
      errors.push("Please enter frequency quantity");
    }

    if (errors.length > 0) {
      let html = "";
      errors.forEach((err) => {
        html = `  <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <strong>${err}</strong>
                        </div>
`;
        //$("error-div").innerHTML = html;
      });
    } else {
      let $form = $(this);

      if (request) {
        request.abort();
      }

      let $inputs = $form.find("input,select,button, textarea");

      let serializedData = $form.serialize();

      $inputs.prop("disabled", true);

      request = $.ajax({
        url: "../medicine.php",
        type: "POST",
        data: serializedData,
      });

      request.done(function (response, textStatus, jqHXR) {
        $form.trigger("reset");
      });

      request.fail(function (jqXHR, textStatus, errorThrown) {
        // Log the error to the console
        console.error(errorThrown);
      });

      request.always(function () {
        // Reenable the inputs
        $inputs.prop("disabled", false);
      });
    }
  });
});
