function copy(text) {
    navigator.clipboard.writeText(text)
        .then(() => {
            alert("Copied " + text);
        })
        .catch(() => {
            alert("Something went wrong - try copying this: " + text);
        });
}