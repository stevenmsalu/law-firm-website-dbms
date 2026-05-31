document.addEventListener("DOMContentLoaded", function () {
  const navToggle = document.querySelector(".nav-toggle");
  const siteNav = document.querySelector(".site-nav");

  if (navToggle && siteNav) {
    navToggle.addEventListener("click", function () {
      const isOpen = siteNav.classList.toggle("open");
      navToggle.setAttribute("aria-expanded", isOpen ? "true" : "false");
    });
  }

  const footerYear = document.getElementById("year");
  if (footerYear) {
    footerYear.textContent = new Date().getFullYear().toString();
  }

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

    document.addEventListener("click", function (e) {
      if (!userMenuBtn.contains(e.target) && !dropdownMenu.contains(e.target)) {
        userMenuBtn.setAttribute("aria-expanded", "false");
        dropdownMenu.classList.remove("show");
      }
    });

    document.addEventListener("keydown", function (e) {
      if (e.key === "Escape" && dropdownMenu.classList.contains("show")) {
        userMenuBtn.setAttribute("aria-expanded", "false");
        dropdownMenu.classList.remove("show");
      }
    });
  }

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

  const messageThread = document.querySelector(".message-thread");
  if (messageThread) {
    messageThread.scrollTop = messageThread.scrollHeight;
  }

  const contactForm = document.getElementById("contact-form");
  if (!contactForm) {
    return;
  }

  const successEl = document.getElementById("form-success");
  const submitBtn = contactForm.querySelector('button[type="submit"]');

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

  function showError(input, text) {
    input.classList.add("error");
    const msg = contactForm.querySelector('.error-message[data-for="' + input.id + '"]');
    if (msg) {
      msg.textContent = text;
    }
  }

  function clearError(input) {
    input.classList.remove("error");
    const msg = contactForm.querySelector('.error-message[data-for="' + input.id + '"]');
    if (msg) {
      msg.textContent = "";
    }
  }

  function clearAllErrors() {
    Object.keys(fields).forEach(function (id) {
      const input = contactForm.elements[id];
      if (input) {
        clearError(input);
      }
    });
    if (successEl) {
      successEl.textContent = "";
    }
  }

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
    clearAllErrors();

    let isValid = true;
    Object.keys(fields).forEach(function (id) {
      if (!validateField(id)) {
        isValid = false;
      }
    });

    if (!isValid) {
      return;
    }

    if (submitBtn) {
      submitBtn.disabled = true;
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
          if (successEl) {
            successEl.textContent =
              "Thank you for your message. We will get back to you soon!";
          }
          setTimeout(function () {
            window.location.reload();
          }, 3000);
          return;
        }

        if (successEl) {
          successEl.textContent =
            result.text.trim() || "Something went wrong. Please try again.";
        }
        if (submitBtn) {
          submitBtn.disabled = false;
        }
      })
      .catch(function () {
        if (successEl) {
          successEl.textContent = "Server error. Try again later.";
        }
        if (submitBtn) {
          submitBtn.disabled = false;
        }
      });
  });
});
