$(document).ready(() => {
  let request;
  let isPlanUpdate = false;
  let dosagePlanID = 0;

  let btnDeleteDosagePlan = document.querySelectorAll("#btnDeleteDosagePlan");
  let btnUpdateDosagePlan = document.querySelectorAll("#btnUpdateDosagePlan");

  btnDeleteDosagePlan.forEach((btn) => {
    btn.addEventListener("click", (e) => {
      swal({
        title: "Are you sure you want to delete dosage plan?",
        text: "Once deleted, you will not be able to recover this imaginary file!",
        icon: "warning",
        buttons: true,
        dangerMode: true,
      }).then((willDelete) => {
        if (willDelete) {
          let plan_id = e.target.getAttribute("data-id");

          $.ajax({
            url: `../delete.php`,
            method: "POST",
            data: { plan_id },
            success: function (response) {
              let data = JSON.parse(response);
              if (data.success) {
                swal(`${data.success}`, {
                  icon: "success",
                }).then(() => {
                  location.assign("/dosage.php");
                });
              } else if (data.error) {
                swal(`${data.error}`, {
                  icon: "error",
                });
              } else {
                const { errors } = data;
                console.log(errors);
              }
            },
            failure: function (response) {
              console.log(response);
            },
          });
        } else {
          // swal("Your imaginary file is safe!");
        }
      });
    });
  });

  btnUpdateDosagePlan.forEach((btn) => {
    btn.addEventListener("click", (e) => {
      dosagePlanID = e.target.getAttribute("data-id");

      $.ajax({
        url: `../update.php`,
        method: "GET",
        data: { edit_id: dosagePlanID },
        success: function (response) {
          let data = JSON.parse(response);
          const { date_taken, medicine_id, plan_id, time_taken } = data;
          $("#date_taken").val(date_taken);
          $("#time_taken").val(time_taken);
          $("#medicine_id").val(medicine_id);
          isPlanUpdate = true;
          $("#plannerModal").modal("show");
        },
        failure: function (response) {
          console.log(response);
        },
      });
    });
  });

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
        html += `  <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <strong>${err}</strong>
                        </div>
`;
        document.getElementById("error-div").innerHTML = html;
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

      request.done(function (response) {
        $form.trigger("reset");

        swal(`${response}`, {
          icon: "success",
        }).then(() => {
          location.assign("/home.php");
        });
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

  //events that gets fired when the dosage form is been submitted
  $("#dosagePlanForm").submit(function (e) {
    e.preventDefault();
    e.stopPropagation();
    let errors = [];
    if (!isPlanUpdate) {
      if ($("#medicine_id").val().length <= 0) {
        errors.push("Please select medicine");
      }

      if ($("#date_taken").val().length <= 0) {
        errors.push(
          "Please Select Daate the medicine was taken or should be taken"
        );
      }
      if ($("#time_taken").val().length <= 0) {
        errors.push("Please Indicate time taken");
      }

      if (errors.length > 0) {
        let html = "";
        errors.forEach((err) => {
          html += `  <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                      <span aria-hidden="true">&times;</span>
                                  </button>
                                  <strong>${err}</strong>
                              </div>
      `;
        });
        document.getElementById("dosage-errors").innerHTML = html;
      } else {
        let $form = $(this);

        if (request) {
          request.abort();
        }

        let $inputs = $form.find("input,select,button, textarea");

        let serializedData = $form.serialize();

        $inputs.prop("disabled", true);

        request = $.ajax({
          url: "../dosageplanner.php",
          type: "POST",
          data: serializedData,
        });

        request.done(function (response, textStatus, jqHXR) {
          $form.trigger("reset");
          console.log(JSON.parse(response));
        });

        request.fail(function (jqXHR, textStatus, errorThrown) {
          // Log the error to the console
          console.error(errorThrown);
          $inputs.prop("disabled", false);
        });

        request.always(function () {
          // Reenable the inputs
          $inputs.prop("disabled", false);
        });
      }
    } else {
      //update dosage plan

      if ($("#medicine_id").val().length <= 0) {
        errors.push("Please select medicine");
      }

      if ($("#date_taken").val().length <= 0) {
        errors.push(
          "Please Select Daate the medicine was taken or should be taken"
        );
      }
      if ($("#time_taken").val().length <= 0) {
        errors.push("Please Indicate time taken");
      }

      if (errors.length > 0) {
        let html = "";
        errors.forEach((err) => {
          html += `  <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                      <span aria-hidden="true">&times;</span>
                                  </button>
                                  <strong>${err}</strong>
                              </div>
      `;
        });
        document.getElementById("dosage-errors").innerHTML = html;
      } else {
        let $form = $(this);

        if (request) {
          request.abort();
        }

        let $inputs = $form.find("input,select,button, textarea");

        let serializedData = $form.serialize();

        $inputs.prop("disabled", true);

        request = $.ajax({
          url: "../update.php",
          type: "POST",
          data: { serializedData, isPlanUpdate, dosagePlanID },
        });

        request.done(function (response, textStatus, jqHXR) {
          $form.trigger("reset");
          console.log(response);
        });

        request.fail(function (jqXHR, textStatus, errorThrown) {
          // Log the error to the console
          console.error(errorThrown);
          $inputs.prop("disabled", false);
        });

        request.always(function () {
          // Reenable the inputs
          $inputs.prop("disabled", false);
        });
      }
    }
  });
});
