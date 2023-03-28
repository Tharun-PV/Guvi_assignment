$(document).ready(function () {
  // On form submission
  $("#register-form").submit(function (event) {
    // Prevent the form from submitting via the browser
    event.preventDefault();
    // Get the form data
    var formData = $(this).serialize();
    // Send an AJAX request to the PHP script
    $.ajax({
      type: "POST",
      url: "php/register.php",
      data: formData,
      success: function (response) {
        // Redirect to the login page if registration is successful
        if (response == "success") {
          window.location.href = "login.html";
        } else {
          // Display an error message if registration fails
          $("#error-message").text(response);
        }
      },
    });
  });
});
