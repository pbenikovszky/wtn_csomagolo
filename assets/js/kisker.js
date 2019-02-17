window.addEventListener("load", function () {

    const btnChangeState = document.getElementById("btnChangeState");


    btnChangeState.addEventListener("click", function (e) {

        let oids = [];
        let rows = document.querySelector(".kiskerTable").rows;
        for (let i = 2; i < rows.length; i++) {
            if (
                rows[i].getElementsByTagName("td")[0].firstElementChild.checked
            ) {
                let oid = rows[i].getElementsByTagName("td")[3].firstElementChild
                    .innerText;
                oids.push(oid);
            } // if
        } // for

        if (oids.length > 0) {
            // Ask the user to confirm the state change request
            if (
                confirm("Biztosan 'Kisker fizetett'-re állítod a kijelölt megrendeléseket?")
            ) {
                changeState(oids.join(","), "W");
            } // if confirm        
        } // if
    }); // btnChangeState.click

    // Function to change state of the selected orders
    function changeState(data, stateCode, isFromDropDown = false) {
        let xhr = new XMLHttpRequest();
        let method = "POST";
        let url =
            "index.php?option=com_virtuemart&view=csomagolo&task=statechange&job=state-change&format=json";

        xhr.open(method, url, true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

        xhr.onload = function () {
            // display error message and exit function
            // in case of any error
            if (xhr.status != 200) {
                alertError(xhr.status, xhr.statusText);
                return;
            } // if

            // create a result object by parsing the returned JSON data
            let endPos = xhr.responseText.indexOf("}") + 1;
            let jsonResponse = xhr.responseText.substr(0, endPos);
            let result = JSON.parse(jsonResponse);

            // Reload the page after the successful change
            if (result.result === "SUCCESS") {
                if (isFromDropDown) {
                    location.reload();
                } else {
                    location.reload();
                }
                console.log(result.resultState);
            } else {
                loader.classList.add("tss-hidden");
                alert("Something went wrong");
            } // if
        }; // onload

        let params = "ordernumbers=" + data + "&newstate=" + stateCode;
        let loader = document.getElementById("loader");
        xhr.send(params);
        loader.classList.remove("tss-hidden");
    } // changeState



});