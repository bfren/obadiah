var clipboard = new ClipboardJS(".copy");

clipboard.on("success", function(e) {
    alert("Copied!");

    console.info("Action:", e.action);
    console.info("Text:", e.text);
    console.info("Trigger:", e.trigger);

    e.clearSelection();
});

clipboard.on("error", function(e) {
    alert("Something went wrong, plese try copying manually: " + e.text);

    console.error("Action:", e.action);
    console.error("Trigger:", e.trigger);
});