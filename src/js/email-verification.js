export const useMailVerfication = () => {
    const form = document.querySelector("#email-verification-form");
    const numberInput = document.querySelector("input[name='code']");
    const resendButton = document.querySelector("#resend-code");
    const spinner = document.querySelector(".spinner");

    const codeResentMsg = document.querySelector(".code-resent-message");
    if (!form) return;
    let messageError = document.querySelector(".error-msg");

    const toggleSpinner = () => {
        spinner.classList.toggle("!flex");
        resendButton.disabled = !resendButton.disabled;
    }

    const removeErrorMsg = () => {
        messageError.classList.add("hidden");
    }

    form.addEventListener("submit", async (event) => {
        event.preventDefault();
        const formData = new FormData(form);
        const email = formData.get("email");
        const code = formData.get("code");
        
        toggleSpinner();
        const response = await fetch(`/wp-json/wuxiadvanced/v1/resources/verify-mail`, {
            method: "POST",
            body: JSON.stringify({ email, code }),
            headers: {
                'Content-Type': 'application/json'
            }
        });
        const data = await response.json();
        console.log(data);
        if (!data.verified) {
            toggleSpinner();
            messageError.textContent = "The code is incorrect. Please try again.";
            messageError.classList.remove("hidden");
            numberInput.value = "";
            numberInput.focus();
            numberInput.addEventListener("input", removeErrorMsg);
            return;
        }

        
        const fetchToken = await fetch(`/wp-json/wuxiadvanced/v1/resources/get-token?email=${email}`, {
            method: "POST",
        });
        toggleSpinner();
        location.href = "/resources";
    });

    resendButton.addEventListener("click", async (event) => {
        event.preventDefault();
        // get mail from url
        const email = new URLSearchParams(window.location.search).get("email");
        const response = await fetch(`/wp-json/wuxiadvanced/v1/resources/resend-mail-code?mail=${email}`, {
            method: "POST",
            headers: {
                'Content-Type': 'application/json'
            }
        });
        const data = await response.json();
        if (!data.mail) return;
        codeResentMsg.classList.remove("hidden");
    })

}