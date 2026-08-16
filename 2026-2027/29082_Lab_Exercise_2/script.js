/* ================================================================
   Login page interactions
   ================================================================ */

document.addEventListener("DOMContentLoaded", () => {
  // These credentials are intentionally public and are only for this front-end demo.
  const DEMO_CREDENTIALS = Object.freeze({
    username: "demo",
    password: "auralis123",
  });

  // Cache frequently used interface elements.
  const loginForm = document.querySelector("#loginForm");
  const usernameInput = document.querySelector("#username");
  const passwordInput = document.querySelector("#password");
  const passwordToggle = document.querySelector("#passwordToggle");
  const submitButton = document.querySelector("#submitButton");
  const formStatus = document.querySelector("#formStatus");
  const welcomeModalElement = document.querySelector("#welcomeModal");
  const currentYear = document.querySelector("#currentYear");

  // Keep the footer year current without requiring manual updates.
  currentYear.textContent = new Date().getFullYear();

  // Allow users to reveal or hide their password safely.
  passwordToggle.addEventListener("click", () => {
    const isHidden = passwordInput.type === "password";

    passwordInput.type = isHidden ? "text" : "password";
    passwordToggle.setAttribute("aria-label", isHidden ? "Hide password" : "Show password");
    passwordToggle.setAttribute("aria-pressed", String(isHidden));
    passwordToggle.querySelector("i").className = isHidden ? "bi bi-eye-slash" : "bi bi-eye";
    passwordInput.focus();
  });

  // Validate the form, then compare it with the public demo credentials.
  loginForm.addEventListener("submit", (event) => {
    event.preventDefault();
    event.stopPropagation();

    formStatus.textContent = "";
    formStatus.classList.remove("is-error");
    loginForm.classList.add("was-validated");

    if (!loginForm.checkValidity()) {
      const firstInvalidField = loginForm.querySelector(":invalid");
      firstInvalidField?.focus();
      return;
    }

    setLoadingState(true);

    // The short delay demonstrates a realistic loading state.
    window.setTimeout(() => {
      setLoadingState(false);

      const isDemoUser =
        usernameInput.value.trim() === DEMO_CREDENTIALS.username &&
        passwordInput.value === DEMO_CREDENTIALS.password;

      if (!isDemoUser) {
        formStatus.classList.add("is-error");
        formStatus.textContent = "Incorrect username or password. Use the demo access details above.";
        usernameInput.focus();
        return;
      }

      // Bootstrap manages focus trapping, keyboard dismissal, and the backdrop.
      const welcomeModal = window.bootstrap.Modal.getOrCreateInstance(welcomeModalElement);
      welcomeModal.show();

      loginForm.reset();
      loginForm.classList.remove("was-validated");
    }, 1100);
  });

  // Clear the success message when users edit their credentials.
  loginForm.addEventListener("input", () => {
    formStatus.textContent = "";
    formStatus.classList.remove("is-error");
  });

  /** Toggle the submit button's busy state and accessible label. */
  function setLoadingState(isLoading) {
    const buttonLabel = submitButton.querySelector(".button-label");
    const buttonArrow = submitButton.querySelector(".button-arrow");
    const spinner = submitButton.querySelector(".spinner-border");

    submitButton.disabled = isLoading;
    buttonLabel.textContent = isLoading ? "Signing in..." : "Sign in";
    buttonArrow.classList.toggle("d-none", isLoading);
    spinner.classList.toggle("d-none", !isLoading);
  }
});
