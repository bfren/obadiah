document.getElementById("reload-link")?.addEventListener("click", (e) => {
    e.preventDefault();

    let request = {
        method: "get",
        url: "/api/preload?debug=true"
    };

    let target = document.querySelector("#reload");
    target.textContent = "Loading, please wait...";
    axios(request).catch((e) => console.log(e)).then((r) => {
        target.textContent = JSON.stringify(r.data, null, 2);
    });
});
