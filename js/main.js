$(document).ready(() => {
  const urlParams = new URLSearchParams(window.location.search);
  const page = urlParams.get("page");

  let request;
  let isPlanUpdate = false;
  let dosagePlanID = 0;

  let btnDeleteDosagePlan = document.querySelectorAll("#btnDeleteDosagePlan");
  let btnUpdateDosagePlan = document.querySelectorAll("#btnUpdateDosagePlan");
  let btnDeleteMedicine = document.querySelectorAll("#btnDeleteMedicine");
  let btnUpdateMedicine = document.querySelectorAll("#btnUpdateMedicine");

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
                  const page = urlParams.get("page");
                  location.assign(
                    `${
                      page != null ? `/dosage.php?page=${page}` : `/dosage.php`
                    }`
                  );
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
          const { date_taken, medicine_id, plan_id, time_taken, user_id } =
            data;

          $("#date_taken").val(date_taken);
          $("#time_taken").val(time_taken);
          $("#medicine_id").val(medicine_id);
          $("#plan_id").val(plan_id);
          $("#user_id").val(user_id);
          isPlanUpdate = true;
          $("#plannerModal").modal("show");
        },
        failure: function (response) {
          console.log(response);
        },
      });
    });
  });

  //get called when the delete medicine button is being pressed
  btnDeleteMedicine.forEach((btn) => {
    btn.addEventListener("click", (e) => {
      swal({
        title: "Are you sure you want to delete this medicine?",
        text: "Once deleted, you will not be able to recover this imaginary file!",
        icon: "warning",
        buttons: true,
        dangerMode: true,
      }).then((willDelete) => {
        if (willDelete) {
          let medicine_id = e.target.getAttribute("data-id");

          $.ajax({
            url: `../delete.php`,
            method: "POST",
            data: { medicine_id },
            success: function (response) {
              let data = JSON.parse(response);
              if (data.success) {
                swal(`${data.success}`, {
                  icon: "success",
                }).then(() => {
                  const page = urlParams.get("page");
                  location.assign(
                    `${page != null ? `/home.php?page=${page}` : `/home.php`}`
                  );
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

  //gets called when the update medicine button is being called
  btnUpdateMedicine.forEach((btn) => {
    btn.addEventListener("click", (e) => {
      let medicine_id = e.target.getAttribute("data-id");
      alert(medicine_id);
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

    //check whether it is an update or a new record being inserted
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
        //no error
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
          let data = JSON.parse(response);
          if (data.success) {
            swal(`${data.success}`, {
              icon: "success",
            }).then(() => {
              $form.trigger("reset");
              const page = urlParams.get("page");
              location.assign(
                `${page != null ? `/dosage.php?page=${page}` : `/dosage.php`}`
              );
            });
          } else if (data.error) {
            swal(`${data.error}`, {
              icon: "error",
            });
          } else {
            const { errors } = data;
            console.log(errors);
          }
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

        const medicine_id = $("#medicine_id").val();
        const date_taken = $("#date_taken").val();
        const time_taken = $("#time_taken").val();
        const plan_id = $("#plan_id").val();
        const user_id = $("#user_id").val();

        const data = {
          medicine_id,
          date_taken,
          time_taken,
          plan_id,
          isPlanUpdate,
        };

        //let serializedData = JSON.stringify(data);

        $inputs.prop("disabled", true);

        $.ajax({
          url: "../update.php",
          method: "POST",
          data: data,
          success: function (response) {
            let data = JSON.parse(response);
            const { success, error, errors } = data;

            if (success) {
              swal(`${success}`, {
                icon: "success",
              }).then(() => {
                isPlanUpdate = false;
                const page = urlParams.get("page");
                location.assign(
                  `${page != null ? `/dosage.php?page=${page}` : "/dosage.php"}`
                );
              });
            } else if (error) {
              swal(`${error}`, {
                icon: "error",
              });
            } else {
              console.log(data);
            }
          },
          failure: (response) => {
            console.log(response);
            $inputs.prop("disabled", false);
            isPlanUpdate = false;
          },
        });

        // request.done(function (response, textStatus, jqHXR) {
        //   $form.trigger("reset");
        //   console.log(response);
        // });

        // request.fail(function (jqXHR, textStatus, errorThrown) {
        //   // Log the error to the console
        //   console.error(errorThrown);
        //   $inputs.prop("disabled", false);
        // });

        // request.always(function () {
        //   // Reenable the inputs
        //   $inputs.prop("disabled", false);
        // });
      }
    }
  });
});
