$(document).ready(function () {
  // On form submission
  $("#login-form").submit(function (event) {
    // Prevent the form from submitting via the browser
    event.preventDefault();
    // Get the form data
    var formData = $(this).serialize();
    // Send an AJAX request to the PHP script
    $.ajax({
      type: "POST",
      url: "php/login.php",
      data: formData,
      dataType: "json",
      success: function (data) {
        if (data.response) {
          document.location.replace("./profile.html");
        } else if (data.error) {
          $("#error-message").text(data.error);
        } else {
          console.log(data);
          $("#error-message").text("Unknown error occurred");
        }
      },
      error: function (error) {
        console.log(error);
        $("#error-message").text("AJAX error occurred");
      },
    });
  });
});
