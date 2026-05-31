document.addEventListener("DOMContentLoaded", function () {

  /* =========================
     Mobile Navigation
     ========================= */
  const navToggle = document.querySelector(".nav-toggle");
  const siteNav = document.querySelector(".site-nav");

  if (navToggle && siteNav) {
    navToggle.addEventListener("click", function () {
      const isOpen = siteNav.classList.toggle("open");
      navToggle.setAttribute("aria-expanded", isOpen ? "true" : "false");
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
    userMenuBtn.addEventListener("click", function (e) {
      e.preventDefault();
      e.stopPropagation();
      const isExpanded = userMenuBtn.getAttribute("aria-expanded") === "true";
      userMenuBtn.setAttribute("aria-expanded", !isExpanded);
      dropdownMenu.classList.toggle("show");
    });

    // Close menu when clicking outside
    document.addEventListener("click", function (e) {
      if (!userMenuBtn.contains(e.target) && !dropdownMenu.contains(e.target)) {
        userMenuBtn.setAttribute("aria-expanded", "false");
        dropdownMenu.classList.remove("show");
      }
    });

    // Close menu with Escape key
    document.addEventListener("keydown", function (e) {
      if (e.key === "Escape" && dropdownMenu.classList.contains("show")) {
        userMenuBtn.setAttribute("aria-expanded", "false");
        dropdownMenu.classList.remove("show");
      }
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
    // Scroll to the latest message on page load
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
   * Shows an error message for one form field.
   */
  function showError(input, text) {
    input.classList.add("error");
    const msg = contactForm.querySelector('.error-message[data-for="' + input.id + '"]');
    if (msg) {
      msg.textContent = text;
    }
  }

  /**
   * Clears the error state for one form field.
   */
  function clearError(input) {
    input.classList.remove("error");
    const msg = contactForm.querySelector('.error-message[data-for="' + input.id + '"]');
    if (msg) {
      msg.textContent = "";
    }
  }

  /**
   * Clears all field errors and the success message.
   */
  function clearErrors() {
    Object.keys(fields).forEach(function (id) {
      const input = contactForm.elements[id];
      if (input) {
        clearError(input);
      }
    });

    if (successMessage) {
      successMessage.textContent = "";
    }
  }

  /**
   * Validates one field by id and returns true if it passes.
   */
  function validateField(id) {
    const config = fields[id];
    const input = contactForm.elements[id];

    if (!config || !input) {
      return true;
    }

    const value = input.value.trim();

    if (config.required && value === "") {
      showError(input, config.message);
      return false;
    }

    if (config.minLength && value !== "" && value.length < config.minLength) {
      showError(input, config.message);
      return false;
    }

    if (config.validate && !config.validate(value)) {
      showError(input, config.message);
      return false;
    }

    clearError(input);
    return true;
  }

  /**
   * Validates the contact form fields and displays user-friendly errors.
   * @returns {boolean} True when every field passes validation.
   */
  function validateForm() {
    let isValid = true;

    Object.keys(fields).forEach(function (id) {
      if (!validateField(id)) {
        isValid = false;
      }
    });

    return isValid;
  }

  /**
   * Sends form data to the PHP handler and handles the server response.
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
          return { ok: res.ok, text: text };
        });
      })
      .then(function (result) {
        if (result.ok && result.text.trim() === "success") {
          contactForm.reset();

          if (successMessage) {
            successMessage.textContent =
              "Thank you for your message. We will get back to you soon!";
          }

          // Reload after a short delay so the user can read the success message
          setTimeout(function () {
            window.location.reload();
          }, 3000);
          return;
        }

        if (successMessage) {
          successMessage.textContent =
            result.text.trim() || "Something went wrong. Please try again.";
        }

        if (submitButton) {
          submitButton.disabled = false;
        }
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

  // Validate individual fields when the user leaves an input
  Object.keys(fields).forEach(function (id) {
    const input = contactForm.elements[id];

    if (!input) {
      return;
    }

    input.addEventListener("blur", function () {
      validateField(id);
    });

    input.addEventListener("input", function () {
      clearError(input);
    });
  });

  contactForm.addEventListener("submit", function (event) {
    event.preventDefault();
    clearErrors();

    if (!validateForm()) {
      return;
    }

    sendForm();
  });
});
