document.addEventListener("DOMContentLoaded", function () {

  /* =========================
     Mobile Navigation
     ========================= */
  const navToggle = document.querySelector(".nav-toggle");
  const siteNav = document.querySelector(".site-nav");

  if (navToggle && siteNav) {
    navToggle.addEventListener("click", function () {
      siteNav.classList.toggle("open");
    });
  }

  /* =========================
     Footer Year
     ========================= */
  const footerYear = document.getElementById("year");

  if (footerYear) {
    footerYear.textContent = new Date().getFullYear().toString();
  }

  /* =========================
     User Dropdown (logged-in sessions)
     ========================= */
  const userMenuBtn = document.querySelector(".user-menu-btn");
  const dropdownMenu = document.querySelector(".dropdown-menu");

  if (userMenuBtn && dropdownMenu) {
    userMenuBtn.addEventListener("click", function () {
      dropdownMenu.classList.toggle("show");
    });
  }

  /* =========================
     Hero Slideshow
     ========================= */
  const slides = document.querySelectorAll(".slideshow-slide");

  if (slides.length > 0) {
    let currentSlide = 0;

    setInterval(function () {
      slides[currentSlide].classList.remove("active");
      currentSlide++;

      if (currentSlide >= slides.length) {
        currentSlide = 0;
      }

      slides[currentSlide].classList.add("active");
    }, 5000);
  }

  /* =========================
     Case Message Thread
     ========================= */
  const messageThread = document.querySelector(".message-thread");

  if (messageThread) {
    messageThread.scrollTop = messageThread.scrollHeight;
  }

  /* =========================
     Contact Form
     ========================= */
  const contactForm = document.getElementById("contact-form");

  if (!contactForm) {
    return;
  }

  const successMessage = document.getElementById("form-success");
  const submitButton = contactForm.querySelector('button[type="submit"]');

  /**
   * Validation rules for each contact form field.
   * required: field must not be empty
   * minLength: minimum character count
   * validate: custom check function (optional)
   * message: error text shown to the user
   */
  const fields = {
    name: {
      required: true,
      minLength: 2,
      message: "Please enter your full name (at least 2 characters).",
    },
    email: {
      required: true,
      validate: function (value) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value);
      },
      message: "Please enter a valid email address.",
    },
    phone: {
      required: false,
      validate: function (value) {
        return value === "" || /^[0-9+\-\s()]{6,}$/.test(value);
      },
      message: "Please enter a valid phone number.",
    },
    subject: {
      required: true,
      minLength: 4,
      message: "Please provide a subject (at least 4 characters).",
    },
    message: {
      required: true,
      minLength: 10,
      message: "Please provide some details (at least 10 characters).",
    },
  };

  /**
   * Marks one field as invalid and shows its error message.
   */
  function showError(input, text) {
    input.classList.add("error");
    const msg = contactForm.querySelector('.error-message[data-for="' + input.id + '"]');
    if (msg) {
      msg.textContent = text;
    }
  }

  /**
   * Clears all field errors and the success message before a new submit attempt.
   */
  function clearErrors() {
    contactForm.querySelectorAll(".error-message").forEach(function (el) {
      el.textContent = "";
    });
    contactForm.querySelectorAll("input, textarea").forEach(function (field) {
      field.classList.remove("error");
    });
    if (successMessage) {
      successMessage.textContent = "";
    }
  }

  /**
   * Validates every field in the form. Returns true only if all checks pass.
   */
  function validateForm() {
    let isValid = true;

    Object.keys(fields).forEach(function (id) {
      const config = fields[id];
      const input = contactForm.elements[id];

      if (!config || !input) {
        return;
      }

      const value = input.value.trim();

      if (config.required && value === "") {
        showError(input, config.message);
        isValid = false;
        return;
      }

      if (config.minLength && value.length < config.minLength) {
        showError(input, config.message);
        isValid = false;
        return;
      }

      if (config.validate && !config.validate(value)) {
        showError(input, config.message);
        isValid = false;
      }
    });

    return isValid;
  }

  /**
   * Sends the form to the PHP handler and updates the page based on the response.
   */
  function sendForm() {
    if (submitButton) {
      submitButton.disabled = true;
    }

    fetch("handlers/contact_handler.php", {
      method: "POST",
      body: new FormData(contactForm),
    })
      .then(function (res) {
        return res.text().then(function (text) {
          const responseText = text.trim();

          if (res.ok && responseText === "success") {
            // Reset the form and show success message
            contactForm.reset();
            if (successMessage) {
              successMessage.textContent =
                "Thank you for your message. We will get back to you soon!";
              
              // Clear the success message after 5 seconds
              setTimeout(function () {
                successMessage.textContent = "";
              }, 5000);
            }
            if (submitButton) {
              submitButton.disabled = false;
            }
            return;
          }

          if (successMessage) {
            successMessage.textContent =
              responseText || "Something went wrong. Please try again.";
          }
          if (submitButton) {
            submitButton.disabled = false;
          }
        });
      })
      .catch(function () {
        if (successMessage) {
          successMessage.textContent = "Server error. Try again later.";
        }
        if (submitButton) {
          submitButton.disabled = false;
        }
      });
  }

  contactForm.addEventListener("submit", function (event) {
    event.preventDefault();
    clearErrors();

    if (!validateForm()) {
      return;
    }

    sendForm();
  });
});