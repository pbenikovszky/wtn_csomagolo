window.addEventListener("load", function () {
  var btnSelectAll = document.getElementById("btnSelectAll");
  var btnDeselect = document.getElementById("btnDeselect");
  var btnShowRetail = document.getElementById("btnShowRetail");
  var btnShowAll = document.getElementById("btnShowAll");
  var btnPrintAll = document.getElementById("btnPrintAll");
  var btnIssueInvoice = document.getElementById("btnIssueInvoice");
  var btnPrintInvoice = document.getElementById("btnPrintInvoice");
  var btnStateToGLS = document.getElementById("btnStateToGLS");
  var btnStateToShipped = document.getElementById("btnStateToShipped");
  var btnGLSExport = document.getElementById("btnGLSExport");

  var lastSelectedStates = [];

  addStateChangeEventListeners();

  addManualInvoiceEventListeners();

  // Select every checkbox in first column
  btnSelectAll.addEventListener("click", function (e) {
    document.getElementsByName("cbSelect").forEach(cb => {
      if (!cb.parentElement.parentElement.classList.contains("tss-hidden")) {
        cb.checked = true;
      } // if
    }); // getElementsByNAme
  }); // btnSelectAll.click

  // Deselect every checkbox in first column
  btnDeselect.addEventListener("click", function (e) {
    DeselectAll();
  }); // btnDeselect.click

  // Filter the list to show only retail customers
  btnShowRetail.addEventListener("click", function (e) {
    let rows = document.querySelector(".orderTable").rows;
    for (let i = 2; i < rows.length; i++) {
      let cb = rows[i].getElementsByTagName("td")[3].firstElementChild;
      if (!cb.checked) {
        rows[i].classList.add("tss-hidden");
      } else {
        rows[i].classList.remove("tss-hidden");
      } // if
    } // for
  }); // btnShowRetail.click

  // Remove retail filtering
  btnShowAll.addEventListener("click", function (e) {
    let rows = document.querySelector(".orderTable").rows;
    for (let i = 1; i < rows.length; i++) {
      rows[i].classList.remove("tss-hidden");
    } // for
  }); // btnShowAll.click

  btnPrintAll.addEventListener("click", function (e) {
    let oids = [];
    let rows = document.querySelector(".orderTable").rows;
    for (let i = 2; i < rows.length; i++) {
      let cb = rows[i].getElementsByTagName("td")[0].firstElementChild;
      if (cb.checked) {
        if (!cb.parentElement.parentElement.classList.contains("tss-hidden")) {
          let oid = rows[i].getElementsByTagName("td")[4].firstElementChild
            .innerText;
          oids.push(oid);
        }
      } // if cb.checked
    } // for

    if (oids.length > 0) {
      window.open(
        "index.php?option=com_virtuemart&view=csomagolo&task=printorders&ordernumbers=" +
        oids.join(","),
        "win2",
        "status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=640,height=480,directories=no,location=no"
      );
      DeselectAll();
    } // if
  }); // btnPrintAll.click

  btnIssueInvoice.addEventListener("click", function (e) {
    let oids = [];
    let rows = document.querySelector(".orderTable").rows;
    for (let i = 2; i < rows.length; i++) {
      let cb = rows[i].getElementsByTagName("td")[8].firstElementChild;
      if (!cb.checked) {
        if (!cb.parentElement.parentElement.classList.contains("tss-hidden")) {
          if (
            rows[i].getElementsByTagName("td")[0].firstElementChild.checked &&
            !rows[i].getElementsByTagName("td")[8].firstElementChild.checked &&
            rows[i].getElementsByTagName("td")[9].firstElementChild.value !=
            "V" &&
            rows[i].dataset.invoice == 0
          ) {
            let oid = rows[i].getElementsByTagName("td")[4].firstElementChild
              .innerText;
            oids.push(oid);
          } // ifs
        } // if classList contains tss-hidden
      } // if cb.checked
    } // for
    if (oids.length > 0) {
      DeselectAll();
      issueInvoices(oids.join(","));
    }
  }); // btnIssueInvoice.click

  // Change the status of the selected orders to 'GLS futárra vár' (L)
  btnStateToGLS.addEventListener("click", function (e) {
    let oids = [];
    let rows = document.querySelector(".orderTable").rows;
    for (let i = 2; i < rows.length; i++) {
      if (!rows[i].classList.contains("tss-hidden")) {
        if (
          rows[i].getElementsByTagName("td")[9].firstElementChild.value == "C" &&
          rows[i].getElementsByTagName("td")[0].firstElementChild.checked &&
          rows[i].dataset.invoice == '1'
        ) {
          let oid = rows[i].getElementsByTagName("td")[4].firstElementChild
            .innerText;
          oids.push(oid);
        } // if value == "C"
      } // if classList contains tss-hidden
    } // for

    if (oids.length > 0) {
      changeState(oids.join(","), "G");
    } // if
  }); // btnStateToGLS.click

  // Change the status of the selected orders to 'Kiszállítva' (S)
  btnStateToShipped.addEventListener("click", function (e) {
    let oids = [];
    let rows = document.querySelector(".orderTable").rows;
    for (let i = 2; i < rows.length; i++) {
      if (!rows[i].classList.contains("tss-hidden")) {
        if (
          rows[i].getElementsByTagName("td")[9].firstElementChild.value == "G"
        ) {
          let oid = rows[i].getElementsByTagName("td")[4].firstElementChild
            .innerText;
          oids.push(oid);
        } // if value == "G"
      } // if classList contains tss-hidden
    } // for

    if (oids.length > 0) {
      // Ask the user to confirm the state change request
      if (
        confirm("Biztosan Kiszállítottra állítod a kijelölt megrendeléseket?")
      ) {
        changeState(oids.join(","), "S");
      } // if confirm
    } // if length > 0
  }); // btnStateToDelivered.click

  btnGLSExport.addEventListener("click", function (e) {
    let url =
      "index.php?option=com_virtuemart&view=csomagolo&task=glsexport&format=json";
    window.open(url, "_blank");
  }); // btnGLSExport.click

  // Function to issue the invoices
  function issueInvoices(data) {
    let xhr = new XMLHttpRequest();
    let method = "POST";
    let url =
      "index.php?option=com_virtuemart&view=csomagolo&task=createinvoice&format=json";

    xhr.open(method, url, true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    xhr.onload = function () {
      // display error message and exit function
      // in case of any error
      if (xhr.status != 200) {
        alert("Ajjaj, hiba történt");
        loader.classList.add("tss-hidden");
        return;
      } // if

      // create a result object by parsing the returned JSON data
      let result = JSON.parse(xhr.response);

      // Reload the page after the successful change
      if (result.result === "SUCCESS") {
        console.log(result.data);
        location.reload();
      } else {
        loader.classList.add("tss-hidden");
        alert("Something went wrong");
      } // if
    }; // onload

    let params = "invoiceorderids=" + data;
    let loader = document.getElementById("loader");
    xhr.send(params);
    loader.classList.remove("tss-hidden");
  } // issueInvoices

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
        alert("Ajjaj, hiba történt");
        loader.classList.add("tss-hidden");
        return;
      } // if

      // create a result object by parsing the returned JSON data
      let result = JSON.parse(xhr.response);

      // Reload the page after the successful change
      if (result.result === "SUCCESS") {
        if (isFromDropDown) {
          location.reload();
          //loader.classList.add("tss-hidden");
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
        alert("Ajjaj, hiba történt");
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
      let dbox = rows[i].getElementsByTagName("td")[9].firstElementChild;
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
    if (!cb.parentElement.parentElement.classList.contains("tss-hidden")) {
      cb.checked = false;
    } // if
  }); // getElementsByNAme
}