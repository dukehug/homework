/* ================================================================
   Login page interactions
   ================================================================ */

document.addEventListener("DOMContentLoaded", () => {
  // Cache frequently used interface elements.
  const loginForm = document.querySelector("#loginForm");
  const passwordInput = document.querySelector("#password");
  const passwordToggle = document.querySelector("#passwordToggle");
  const submitButton = document.querySelector("#submitButton");
  const formStatus = document.querySelector("#formStatus");
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

  // Use Bootstrap's validation styling before simulating a sign-in request.
  loginForm.addEventListener("submit", (event) => {
    event.preventDefault();
    event.stopPropagation();

    formStatus.textContent = "";
    loginForm.classList.add("was-validated");

    if (!loginForm.checkValidity()) {
      const firstInvalidField = loginForm.querySelector(":invalid");
      firstInvalidField?.focus();
      return;
    }

    setLoadingState(true);

    // Demo only: replace this timer with a real authentication request.
    window.setTimeout(() => {
      setLoadingState(false);
      formStatus.textContent = "Welcome back — your demo sign-in was successful.";
    }, 1100);
  });

  // Clear the success message when users edit their credentials.
  loginForm.addEventListener("input", () => {
    formStatus.textContent = "";
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
