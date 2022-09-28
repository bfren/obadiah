/**
 * Copy some text to the clipboard.
 *
 * @param {string} text                 The text to copy
 * @returns
 */
function copy(text) {
    navigator.clipboard.writeText(text)
        .then(() => {
            alert("Copied!");
        })
        .catch(() => {
            alert("Something went wrong - try copying this: " + text);
        });
    return false;
}
