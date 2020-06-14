class FormValidator {
    constructor() {
        this.validate = false;
        this.passwordRegex = new RegExp("^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*])(?=.{8,})");
    }

    emailValidator(emailField, text) {
        if (emailField.value.length < 5 || !emailField.value.includes("@") || !emailField.value.includes(".")) {
            document.querySelector(`#${emailField.name} + .invalid-feedback`).textContent = `${text}`;
            emailField.classList.add("is-invalid");
            this.validate = false;
            return false;
        } else {
            document.querySelector(`#${emailField.name} + .invalid-feedback`).textContent = "";
            emailField.classList.remove("is-invalid");
            emailField.classList.add("is-valid");
            this.validate = true;
            return true;
        }
    }

    textValidator(textField, text) {
        if (textField.value.length < 2 || parseInt(textField.value)) {
            document.querySelector(`#${textField.name} + .invalid-feedback`).textContent = `${text}`;
            textField.classList.add("is-invalid");
            this.validate = false;
            return false;
        } else {
            document.querySelector(`#${textField.name} + .invalid-feedback`).textContent = "";
            textField.classList.remove("is-invalid");
            textField.classList.add("is-valid");
            this.validate = true;
            return true;
        }
    }

    passwordValidator(passwordField, text) {
        if (!this.passwordRegex.test(passwordField.value)) {
            document.querySelector(`#${passwordField.name} + .invalid-feedback`).textContent = `${text}`;
            passwordField.classList.add("is-invalid");
            this.validate = false;
            return false;
        } else {
            document.querySelector(`#${passwordField.name} + .invalid-feedback`).textContent = "";
            passwordField.classList.remove("is-invalid");
            passwordField.classList.add("is-valid");
            this.validate = true;
            return true;
        }
    }

    setInvalidField(fieldName, text) {
        document.querySelector(`#${fieldName.name} + .invalid-feedback`).textContent = `${text}`;
        fieldName.classList.add("is-invalid");
    }
}
