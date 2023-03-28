$(document).ready(function () {
  // Retrieve user data from Redis and populate form fields
  $.ajax({
    type: "GET",
    url: "php/redis.php",
    data: { key: "user" },
    success: function (data) {
      if (data) {
        var userData = JSON.parse(data);
        $("#name").text(userData.name);
        $("#email").text(userData.email);
        $("#age").val(userData.age);
        $("#dob").val(userData.dob);
        $("#contact").val(userData.contact);
      }
    },
  });

  // Edit button click event handler
  $("#editButton").click(function () {
    $("#age").prop("readonly", false);
    $("#dob").prop("readonly", false);
    $("#contact").prop("readonly", false);
    $("#submitButton").prop("disabled", false);
    $(this).hide();
  });

  // Submit updated user data to MongoDB
  $("#updateProfileForm").submit(function (event) {
    event.preventDefault();
    var age = $("#age").val();
    var dob = $("#dob").val();
    var contact = $("#contact").val();
    $.ajax({
      type: "POST",
      url: "profile.php",
      data: { age: age, dob: dob, contact: contact },
      success: function (data) {
        if (data === "success") {
          alert("Profile updated successfully!");
          location.reload();
        } else {
          alert("Profile update failed. Please try again later.");
        }
      },
    });
  });

  // Logout button click event handler
  $("#logoutButton").click(function () {
    $.ajax({
      type: "GET",
      url: "php/logout.php",
      success: function (data) {
        window.location.replace("./login.html");
      },
    });
  });
});
