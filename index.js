document.getElementById('contactForm').addEventListener('submit', function(e) {
  e.preventDefault();

  const form = e.target;
  const formData = new FormData(form);

  const name = formData.get('name').trim();
  const email = formData.get('email').trim();
  const message = formData.get('message').trim();

  if (!name || !email || !message) {
    document.getElementById("formStatus").textContent = "All fields are required.";
    document.getElementById("formStatus").style.color = "red";
    return;
  }

  fetch('contact_form.php', {
    method: 'POST',
    body: formData
  })
  .then(res => res.text())
  .then(result => {
    if (result === "SUCCESS") {
      document.getElementById("formStatus").textContent = "Message sent successfully!";
      document.getElementById("formStatus").style.color = "green";
      form.reset();
    } else {
      document.getElementById("formStatus").textContent = "Error: " + result;
      document.getElementById("formStatus").style.color = "red";
    }
  })
  .catch(err => {
    document.getElementById("formStatus").textContent = "Error submitting form: " + err.message;
    document.getElementById("formStatus").style.color = "red";
  });
});
