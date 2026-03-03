/// <reference path="./axios.min.js.map" />

document.querySelectorAll(".settings").forEach($el => {
    $el.addEventListener("submit", async $e => {
        // stop default submit behaviour
        $e.preventDefault();

        // save data
        const submit = $el.querySelector("button[type=submit]");
        submit.setAttribute("disabled", true);
        submit.textContent = "Saving, please wait...";
        await axios
            .post(`/api/ajax/settings_${$el.getAttribute("id")}`, $el, {
                headers: { "Content-Type": "application/json" }
            })
            .then(r => {
                console.log("response", r);
            })
            .catch(e => {
                console.log("error", e);
                $el.querySelector(".error").textContent = "Something went wrong."
            })
            .finally(function () {
                submit.removeAttribute("disabled");
                submit.textContent = "Save";
            });

        return;
    })
})