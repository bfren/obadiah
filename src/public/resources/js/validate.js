// Example starter JavaScript for disabling form submissions if there are invalid fields
(() => {
    'use strict'

    // Fetch all the forms we want to apply custom Bootstrap validation styles to
    const forms = document.querySelectorAll('.needs-validation')

    // Loop over them and prevent submission
    Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
            if (!form.checkValidity()) {
                event.preventDefault()
                event.stopPropagation()
            }

            form.classList.add('was-validated')
        }, false)
    })
})();

// check before doing something (e.g. deleting)
document.querySelectorAll(".check-first").forEach((e) => {
    e.addEventListener("click", (e) => {
        let answer = confirm("Are you sure you want to do this?");
        console.log(answer);
        if (answer !== true) {
            e.preventDefault();
        }
    })
});
