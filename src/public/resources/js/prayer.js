if (document.querySelector(".admin-prayer-calendar-column") != null) {
    // enable dragging between people and day containers
    let drake = dragula({
        isContainer: function (el) {
            return el.classList.contains("people") || el.classList.contains("day");
        },
        revertOnSpill: true,
    });

    // save on drop
    drake.on("drop", () => save_prayer_calendar_data());
    save_prayer_calendar_data();

    // when a user types in the search box, show only people matching the search string
    // (if they have typed two or more characters)
    document.querySelector(".people-search > input").addEventListener("keyup", (e) => {
        // get search string and force it to lower case
        let search = new String(e.target.value).toLowerCase();

        // get all people buttons
        document.querySelectorAll(".people > button").forEach((e) => {
            // return true if search length is under two characters,
            // or if the data-name attribute contains the search string
            let match = search.length < 2 || e.getAttribute("data-name").includes(search);

            // set the display style to match
            if (match) {
                e.style.display = "inline-block";
            } else {
                e.style.display = "none";
            }
        });
    });
}

// save the month data
function save_prayer_calendar_data() {
    // create month object to hold the data
    let month = {
        id: prayer_calendar_month_id,
        days: [],
        people: []
    };

    // loop through each day to create a JSON object of the month
    for (let index = 1; index <= prayer_calendar_month_max_days; index++) {
        // get card for this day
        let card = document.querySelector("#day-" + index);

        // get date
        let date = card.getAttribute("data-date");

        // get hashes for each person
        let hashes = [];
        card.querySelectorAll(".btn").forEach((e) => hashes.push(e.getAttribute("data-hash")));

        // add to the month
        month.days.push({
            date: date,
            people: hashes
        });

        if (hashes.length > 0) {
            month.people.push(...hashes);
        }
    }

    // create axios request object
    let request = {
        method: 'post',
        url: prayer_calendar_save_url,
        data: {
            action: "month",
            data: month
        }
    };

    // post request with feedback to user
    let save = document.querySelector("#save");
    save.textContent = "Saving...";
    axios(request).catch((e) => console.log(e)).then((r) => {
        if (r.data.success) {
            save.textContent = "Saved.";
        } else {
            save.textContent = r.data.message;
        }
    });
}
