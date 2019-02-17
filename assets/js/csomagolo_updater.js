let confirmedCounter = document.getElementById("confirmed-counter");
let myInterval = setInterval(UpdateConfirmedCounter, 300000);
let count = 1;



function UpdateConfirmedCounter() {
    //

    let xhr = new XMLHttpRequest();
    let method = "GET";
    let url =
        "index.php?option=com_virtuemart&view=csomagolo&task=getconfirmedcount&format=json";

    xhr.open(method, url, true);
    // xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded")

    xhr.onload = function () {
        // display error message and exit function
        // in case of any error
        if (xhr.status != 200) {
            alertError(xhr.status, xhr.statusText);
            return;
        } // if

        // create a result object by parsing the returned JSON data
        console.log(xhr.response);
        let result = JSON.parse(xhr.response);

        // Reload the page after the successful change
        if (result.result === "SUCCESS") {
            let confirmedCounter = document.getElementById("confirmed-counter");
            let lastUpdatedText = document.getElementById("last-updated-time");

            confirmedCounter.innerText = result.data;
            lastUpdatedText.innerText = GetFormattedDate();
        } else {
            console.error("Cannot get new counter value.");
        } // if
    }; // onload

    xhr.send();
}