function validateForm() {
  const id = document.getElementById("id");
  const firstName = document.getElementById("firstName");
  const lastName = document.getElementById("lastName");

  const idError = document.getElementById("idError");
  const firstNameError = document.getElementById("firstNameError");
  const lastNameError = document.getElementById("lastNameError");

  // Clear previous error messages
  idError.innerHTML = "";
  firstNameError.innerHTML = "";
  lastNameError.innerHTML = "";

  let errors = [];

  if (id.value.trim() === "") {
    errors.push("ID is required.");
    idError.innerHTML = "Please enter ID.";   // Display an error message.
  }

  if (firstName.value.trim() === "") {
    errors.push("First Name is required.");
    firstNameError.innerHTML = "Please enter First Name.";   // Display an error message.
  }

  if (lastName.value.trim() === "") {
    errors.push("Last Name is required.");
    lastNameError.innerHTML = "Please enter Last Name.";   // Display an error message.
  }

  if (errors.length > 0) {
    let errorMsg = "You forgot to file in the following field(s):\n";   // First line display an error message.
    for (let i = 0; i < errors.length; i++) {
      errorMsg += `${i + 1}. ${errors[i]}\n`;
    }
    alert(errorMsg);
    return;
  }

  // No errors: display result
  const resultDiv = document.getElementById("result");
  resultDiv.innerHTML = `User ID: ${id.value} <br> First Name: ${firstName.value} <br> Last Name: ${lastName.value}`;

  // Clear the form
  id.value = "";
  firstName.value = "";
  lastName.value = "";
}
