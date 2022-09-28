/**
 * Copy some text to the clipboard.
 *
 * @param {string} text                 The text to copy
 * @returns
 */
async function copy(text) {
    await navigator.clipboard
        .writeText(text)
        .then(
            () => {
                alert("Copied!");
            }, () => {
                alert("Something went wrong - try copying this: " + text);
            }
        );
    return false;
}
