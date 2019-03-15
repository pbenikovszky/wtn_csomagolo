window.addEventListener("load", function () {
  document.getElementById("last-updated-time").innerText = GetFormattedDate();

  const btnSelectAll = document.getElementById("btnSelectAll");
  const btnDeselect = document.getElementById("btnDeselect");
  const btnStateToPackage = document.getElementById("btnStateToPackage");
  const btnActiveLink = document.getElementsByClassName('btn-toolbar')[0].children[0].children[0];
  btnActiveLink.classList.add('active-link-button');

  let lastSelectedStates = [];

  addStateChangeEventListeners();

  addManualInvoiceEventListeners();

  setIsDuplicated();

  // Select every checkbox in first column
  btnSelectAll.addEventListener("click", function (e) {
    document.getElementsByName("cbSelect").forEach(cb => {
      cb.checked = true;
    }); // getElementsByNAme
  }); // btnSelectAll.click

  // Deselect every checkbox in first column
  btnDeselect.addEventListener("click", function (e) {
    DeselectAll();
  }); // btnDeselect.click

  btnStateToPackage.addEventListener("click", function (e) {
    let oids = [];
    let rows = document.querySelector(".orderTable").rows;
    for (let i = 2; i < rows.length; i++) {
      if (rows[i].getElementsByTagName("td")[0].firstElementChild.checked) {
        let oid = rows[i].getElementsByTagName("td")[4].firstElementChild
          .innerText;
        oids.push(oid);
      } // if value == "C"
    } // for

    if (oids.length > 0) {
      changeState(oids.join(","), "B");
    } // if
  }); // btnStateToGLS.click

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

  function UpdateManualInvoiceFlag(data, flagValue) {
    let xhr = new XMLHttpRequest();
    let method = "POST";
    let url =
      "index.php?option=com_virtuemart&view=csomagolo&task=statechange&job=manualinvoice&format=json";
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
      let result = JSON.parse(xhr.response);

      // Reload the page after the successful change
      if (result.result === "SUCCESS") {
        loader.classList.add("tss-hidden");
      } else {
        loader.classList.add("tss-hidden");
        alert("Something went wrong");
      } // if
    }; // onload

    let params = "ordernumbers=" + data + "&flagvalue=" + flagValue;
    let loader = document.getElementById("loader");
    xhr.send(params);
    loader.classList.remove("tss-hidden");
  }

  // handling Dropdown box change event
  function addStateChangeEventListeners() {
    let rows = document.querySelector(".orderTable").rows;

    for (let i = 2; i < rows.length; i++) {
      let dbox = rows[i].getElementsByTagName("td")[8].firstElementChild;
      // store the selected elements in an array
      lastSelectedStates[i - 2] = dbox.selectedIndex;
      dbox.addEventListener("change", function (e) {
        onDropdownChange(e, i);
      });
    }
  }

  function addManualInvoiceEventListeners() {
    let cboxes = document.getElementsByName("cbManualInvoice");
    for (let i = 0; i < cboxes.length; i++) {
      cboxes[i].addEventListener("change", function (e) {
        onCheckBoxChange(e, i + 2);
      });
    }
  }

  function setIsDuplicated() {
    let orderTable = document.getElementById("order-table");
    isDuplicated = orderTable.dataset.duplicated;
  }

  function onCheckBoxChange(event, rowIndex) {
    let rows = document.querySelector(".orderTable").rows;
    let oNumber = rows[rowIndex].getElementsByTagName("td")[4].firstElementChild
      .innerText;
    let flagValue = event.target.checked ? 1 : 0;

    UpdateManualInvoiceFlag(oNumber, flagValue);
  }

  function onDropdownChange(event, rowIndex) {
    let rows = document.querySelector(".orderTable").rows;
    let oNumber = rows[rowIndex].getElementsByTagName("td")[4].firstElementChild
      .innerText;
    let newState = event.target.value;

    // Need user confirmation if new state is Shipped
    if (newState == "S") {
      if (
        !confirm("Biztosan Kiszállítottra állítod a kijelölt megrendeléseket?")
      ) {
        event.stopPropagation();
        // set the selected index back to the previous one
        event.target.selectedIndex = lastSelectedStates[rowIndex - 2];
        return false;
      } // if confirm
    } // if newSate == "S"

    // store the new selected index
    lastSelectedStates[rowIndex - 2] = event.target.selectedIndex;
    changeState(oNumber, newState, true);
  }
});

function DeselectAll() {
  document.getElementsByName("cbSelect").forEach(cb => {
    cb.checked = false;
  }); // getElementsByNAme
}

function alertError(errorCode, errorMessage) {
  let loader = document.getElementById("loader");
  loader.classList.add("tss-hidden");
  let errMsg = "Ajjaj, hiba történt!\n";
  errMsg += "Kód: " + errorCode + "\nÜzenet: " + errorMessage + "\n";
  errMsg += "Kérlek juttasd el ezt a hibaüzenetet az adminisztrátornak!";
  alert(errMsg);
}