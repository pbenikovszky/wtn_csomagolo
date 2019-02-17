function GetFormattedDate() {
    let date = new Date();
    let year = date.getFullYear();
    let month = date.getMonth();
    let day = date.getDate();
    let hour = date.getHours();
    let minute = date.getMinutes();
    let seconds = date.getSeconds();
    let months = [
        "Január",
        "Február",
        "Március",
        "Április",
        "Május",
        "Június",
        "Július",
        "Augusztus",
        "Szeptember",
        "Október",
        "November",
        "December"
    ];

    if (hour < 10) {
        hour = "0" + hour;
    }

    if (minute < 10) {
        minute = "0" + minute;
    }

    if (seconds < 10) {
        seconds = "0" + seconds;
    }

    return (
        year +
        ". " +
        months[month] +
        " " +
        day +
        ". " +
        hour +
        ":" +
        minute +
        ":" +
        seconds
    );
}