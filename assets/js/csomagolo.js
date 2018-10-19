window.addEventListener('load', function () {
    
    var btnSelectAll = document.getElementById('btnSelectAll');
    var btnDeselect = document.getElementById('btnDeselect');
    var btnShowRetail = document.getElementById('btnShowRetail');
    var btnShowAll = document.getElementById('btnShowAll');
    var btnPrintAll = document.getElementById('btnPrintAll');
    var btnIssueInvoice = document.getElementById('btnIssueInvoice');
    var btnPrintInvoice = document.getElementById('btnPrintInvoice');
    var btnStateToGLS = document.getElementById('btnStateToGLS');
    var btnStateToDelivered = document.getElementById('btnStateToDelivered');
    var btnGLSExport = document.getElementById('btnGLSExport');

    // Select every checkbox in first column
    btnSelectAll.addEventListener('click', function (e) {
        document.getElementsByName('cbSelect').forEach(cb => {
            if (!cb.parentElement.parentElement.hasClass('tss-hidden')) {
                cb.checked = true;
            } // if
        }); // getElementsByNAme
    }); // btnSelectAll.click

    // Deselect every checkbox in first column
    btnDeselect.addEventListener('click', function (e) {
        document.getElementsByName('cbSelect').forEach(cb => {
            if (!cb.parentElement.parentElement.hasClass('tss-hidden')) {
                cb.checked = false;
            } // if
        }); // getElementsByNAme
    }); // btnDeselect.click


    // Filter the list to show only retail customers
    btnShowRetail.addEventListener('click', function (e) {

        let rows = document.querySelector(".orderTable").rows;
        for (let i = 2; i < rows.length; i++) {
            let cb = rows[i].getElementsByTagName('td')[3].firstElementChild;
            if (!cb.checked) {
                rows[i].classList.add('tss-hidden');
            } else {
                rows[i].classList.remove('tss-hidden');
            } // if
        } // for

    }); // btnShowRetail.click

    // Remove retail filtering
    btnShowAll.addEventListener('click', function (e) {
        let rows = document.querySelector(".orderTable").rows;
        for (let i = 1; i < rows.length; i++) {
            rows[i].classList.remove('tss-hidden');
        } // for
    }); // btnShowAll.click

    btnPrintAll.addEventListener('click', function (e) {

        let oids = [];
        let rows = document.querySelector(".orderTable").rows;
        for (let i = 2; i < rows.length; i++) {
            let cb = rows[i].getElementsByTagName('td')[0].firstElementChild;
            if (cb.checked) {
                let oid = rows[i].getElementsByTagName('td')[1].firstElementChild.innerText;
                oids.push(oid);
            } // if cb.checked
        } // for   

        if (oids.length > 0) {
            window.open('index.php?option=com_virtuemart&view=csomagolo&task=printorders&ordernumbers=' + oids.join(','), 'win2', 'status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=640,height=480,directories=no,location=no');
        } // if

    }); // btnPrintAll.click

    // TODO implement 
    btnIssueInvoice.addEventListener('click', function (e) {
        alert(e.srcElement.id);
    }); // btnIssueInvoice.click

    // TODO implement 
    btnPrintInvoice.addEventListener('click', function (e) {
        alert(e.srcElement.id);

    }); // btnPrintInvoice.click

    // Change the status of the selected orders to 'GLS futárra vár'
    btnStateToGLS.addEventListener('click', function (e) {
        let oids = [];
        let rows = document.querySelector(".orderTable").rows;
        for (let i = 2; i < rows.length; i++) {
            let cb = rows[i].getElementsByTagName('td')[7].firstElementChild;
            if (cb.checked) {
                if (rows[i].getElementsByTagName('td')[8].firstElementChild.value == "C") {
                    let oid = rows[i].getElementsByTagName('td')[1].firstElementChild.innerText;
                    oids.push(oid);
                } // if value == "C"
            } // if cb.checked
        } // for   

        if (oids.length > 0) {
            changeState(oids.join(','), "L");
        } // if
    }); // btnStateToGLS.click

    btnStateToDelivered.addEventListener('click', function (e) {
        let oids = [];
        let rows = document.querySelector(".orderTable").rows;
        for (let i = 2; i < rows.length; i++) {
            let cb = rows[i].getElementsByTagName('td')[7].firstElementChild;
            if (cb.checked) {
                if (rows[i].getElementsByTagName('td')[8].firstElementChild.value == "L") {
                    let oid = rows[i].getElementsByTagName('td')[1].firstElementChild.innerText;
                    oids.push(oid);
                } // if value == "L"
            } // if cb.checked
        } // for   

        if (oids.length > 0) {
            changeState(oids.join(','), "C");
        } // if
    }); // btnStateToDelivered.click


    // TODO implement 
    btnGLSExport.addEventListener('click', function (e) {


        let url = 'index.php?option=com_virtuemart&view=csomagolo&task=glsexport&format=json';
        window.open(url, '_blank');

    }); // btnGLSExport.click

    // Function to change state of the selected orders
    function changeState(data, stateCode) {

        let xhr = new XMLHttpRequest();
        let method = 'POST';
        let url = 'index.php?option=com_virtuemart&view=csomagolo&task=statechange&format=json'; //&ordernumbers=' + oids.join(',');

        xhr.open(method, url, true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

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
                location.reload();
            } // if
        } // onload

        let params = "ordernumbers=" + data + "&newstate=" + stateCode;
        xhr.send(params);

    } // changeState


    // handling Dropdown box change event
    let dropboxes = document.getElementsByClassName('db-state');
    Array.prototype.forEach.call(dropboxes, function (dbox) {
        dbox.addEventListener('change', function () {
            console.log('changed');
        });
    });

});