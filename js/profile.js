$(document).ready(function () {
  // retrieve user data
  $.ajax({
    url: "./php/getUserData.php",
    type: "POST",
    dataType: "JSON",
    success: function (data) {
      if (data.success) {
        $("#name").val(data.name);
        $("#email").val(data.email);
        $("#age").val(data.age);
        $("#dob").val(data.dob);
        $("#contact").val(data.contact);
      } else {
        alert(data.message);
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      alert("Error retrieving user data: " + errorThrown);
    },
  });

  // update profile form submission
  $("#updateProfileForm").on("submit", function (event) {
    event.preventDefault();
    var formData = $(this).serialize();
    $.ajax({
      url: "./php/updateProfile.php",
      type: "POST",
      data: formData,
      dataType: "JSON",
      success: function (data) {
        alert(data.message);
      },
      error: function (jqXHR, textStatus, errorThrown) {
        alert("Error updating profile: " + errorThrown);
      },
    });
  });
});
