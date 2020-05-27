var rowClickedId = null; //tracking which row is clicked

// generating the table by plopping in the table data/rows

var dragSrcRow = null; // Keep track of the source row
var srcTable = ''; // Global tracking of table being dragged for 'over' class setting
var rows = []; // Global rows for #example
var rows2 = []; // Global rows for #example

var groups_table;

$(document).foundation();

/******************************************** groups page drag events ****************************************/
function handleDragStart(e) {
    // this / e.target is the source node.

    // Set the source row opacity
    this.style.opacity = '0.4';

    // Keep track globally of the source row and source table id
    dragSrcRow = this;
    srcTable = this.parentNode.parentNode.id

    // Allow moves
    e.dataTransfer.effectAllowed = 'move';

    // Save the source row html as text
    e.dataTransfer.setData('text/plain', e.target.outerHTML);

}

function handleDragOver(e) {

    if (e.preventDefault) {
        e.preventDefault(); // Necessary. Allows us to drop.
    }

    // Allow moves
    e.dataTransfer.dropEffect = 'move';

    return false;
}

function handleDragEnter(e) {
    // this / e.target is the current hover target.  

    // Get current table id
    var currentTable = this.parentNode.parentNode.id

    // Don't show drop zone if in source table
    if (currentTable !== srcTable) {
        this.classList.add('over');
    }
}

function handleDragLeave(e) {
    // this / e.target is previous target element.

    // Remove the drop zone when leaving element
    this.classList.remove('over');
    console.log("handleDragLeave");
}

function handleDrop(e) {
    // this / e.target is current target element.

    // fix for firefox, stops the page from redirecting
    if (e.preventDefault) {
        e.preventDefault();
    }
    if (e.stopPropagation) {
        e.stopPropagation();
    }

    if (e.stopPropagation) {
        e.stopPropagation(); // stops the browser from redirecting.
    }

    // Get destination table id, row
    var dstTable = $(this.closest('table')).attr('id');
    var dstRow = $(this).closest('tr');

    // No need to process if src and dst table are the same
    if (srcTable !== dstTable) {

        // Get source transfer data
        var srcData = e.dataTransfer.getData('text/plain');

        // Add row to destination Datatable
        $('#' + dstTable).DataTable().row.add($(srcData)).draw();

        // Remove ro from source Datatable
        $('#' + srcTable).DataTable().row(dragSrcRow).remove().draw();

    }
    return false;
}

function handleDragEnd(e) {
    // this/e.target is the source node.

    // Reset the opacity of the source row
    this.style.opacity = '1.0';

    // Clear 'over' class from both tables
    // and reset opacity

    /* [].forEach.call(rows, function (row) {
        row.classList.remove('over');
        row.style.opacity = '1.0';
    }); */

    rows.forEach(row => {
        row.classList.remove('over');
        row.style.opacity = '1.0';
    });

    /* [].forEach.call(rows2, function (row) {
        row.classList.remove('over');
        row.style.opacity = '1.0';
    }); */

    rows2.forEach(row => {
        row.classList.remove('over');
        row.style.opacity = '1.0';
    });
}

/******************************************** end of groups page drag events ****************************************/


// callback functions for the table cell editting plugin
function myCallbackFunction(updatedCell, updatedRow, oldValue) {

    console.log(rowClickedId);
    // console.log(updatedRow);
    var dataArray = [updatedCell.data(), rowClickedId];
    // console.log(dataArray);
    var json = JSON.stringify(dataArray);

    console.log(json);

    $.ajax({
        type: 'POST',
        url: "services/update_group.php",
        data: {
            dataArray: json
        },
        dataType: "json",
        // contentType: false,
        // cache: false,
        // processData: false,

        beforeSend: function () {
            $('.my-confirm-class').attr("disabled", "disabled");
            $('#groups_table').css("opacity", "0.5");
        },

        success: function (data) {
            $('#groups_table').css("opacity", "");
            $(".my-confirm-class").removeAttr("disabled");
            // get_groups();
            console.log(data.error);
        }
    });

    // you can use updatedCell.data to call the ajax and update the database
    // console.log("The new value for the cell is: " + updatedCell.data());
    // console.log("The old value for that cell was: " + oldValue);
    // console.log("The values for each cell in that row are: " + updatedRow.data());
}


function get_asset_history(barcode) {
    // console.log(id);

    var getAssetHistory = $.ajax({
        url: "services/get_asset_history.php",
        type: "POST",
        dataType: "json",
        data: {
            barcode: barcode
        },
        // adding fade effect on window and cursor to clock while the page is laoding
        beforeSend: function () {
            $("body").css("cursor", "wait");
            $("body").css("opacity", "0.5");
        },
    });

    getAssetHistory.fail(function (jqXHR, textStatus) {
        alert("Something went Wrong! (getAssetHistory)" +
            textStatus);
    });

    getAssetHistory.done(function (data) {
        $("body").css("cursor", "default");
        $("body").css("opacity", "");

        var dataahArray = []; // the array which is used to populate the tables for asset history

        // in php, it's throwing back a string, the error number
        if (data.error.id == "0") {
            $.each(data.a_history, function (i, item) {
                dataahArray[i] = [];
                dataahArray[i].push(item.asset_description);
                // dataahArray[i].push(item.assets_notes);
                // dataahArray[i].push(item.assets_serial_number);
                dataahArray[i].push(item.assets_barcode);
                dataahArray[i].push(item.assets_logged_out_out_time);
                dataahArray[i].push(item.assets_logged_out_due_time);
                dataahArray[i].push(item.assets_logged_out_in_time);
                dataahArray[i].push(item.borrowers_first_name);
                dataahArray[i].push(item.borrowers_last_name);
                dataahArray[i].push(item.borrowers_student_id);
                dataahArray[i].push(item.borrowers_dc_email);
                dataahArray[i].push(item.borrowers_other_email);
                // databhArray[i].push(item.overdue);
            });

            console.log(dataahArray);
            /*************************** creating the tables for groups, output the php data to the html table ****************************/

            // generating the table by plopping in the table data/rows

            var dragSrcRow = null; // Keep track of the source row
            var srcTable = ''; // Global tracking of table being dragged for 'over' class setting
            var rows = []; // Global rows for #example


            // if ( $.fn.DataTable.isDataTable( '#borrower_history_table' ) ) {

            //   }

            var table = $('#assets_history_table').DataTable({

                data: dataahArray,
                "destroy": true,

                columnDefs: [{
                    targets: [8, 9],
                    className: "truncate"
                }],

                // Add HTML5 draggable class to each row
                createdRow: function (row, data, dataIndex, cells) {
                    // $(row).attr('draggable', 'true');

                    // adding truncate to the asset notes and shortening the text dispayed in the CSS
                    var td = $(row).find(".truncate");

                    td.attr("title", td.html()); //adding the title attribute so the whole text will show on hover

                    // adding the data-id to individual rows
                    // $(row).attr('data-id', group_id[dataIndex]);                    
                },

                drawCallback: function () {
                    // Add HTML5 draggable event listeners to each row
                    rows = document.querySelectorAll('#assets_history_table tbody tr');

                    // rows.forEach(row => {
                    //     row.addEventListener('dragstart', handleDragStart);
                    //     row.addEventListener('dragenter', handleDragEnter);
                    //     row.addEventListener('dragover', handleDragOver);
                    //     row.addEventListener('dragleave', handleDragLeave);
                    //     row.addEventListener('drop', handleDrop);
                    //     row.addEventListener('dragend', handleDragEnd);
                    // });
                }
            });

            // add place holder to the search filter input field
            $('#assets_history_table_filter label input').attr("placeholder", "Search");

        } else {
            alert("wrong");
        }

    });
}

// when clicking on the get button on print history page, populate the content in the two tables
function get_print_history(id) {
    // console.log(id);

    var getPrintHistory = $.ajax({
        url: "services/print_history.php",
        type: "POST",
        dataType: "json",
        data: {
            student_id: id
        },
        // adding fade effect on window and cursor to clock while the page is laoding
        beforeSend: function () {
            $("body").css("cursor", "wait");
            $("body").css("opacity", "0.5");
        },
    });


    getPrintHistory.fail(function (jqXHR, textStatus) {
        alert("Something went Wrong! (getPrintHistory)" +
            textStatus);
    });

    getPrintHistory.done(function (data) {
        $("body").css("cursor", "default");
        $("body").css("opacity", "");

        var dataDebitFunds = []; // the array which is used to populate the tables for debit funds
        var dataCreditFunds = []; // the array which is used to populate the tables for credit funds
        var totalAmount; // describe the available credit for borrower
        var totalDebitAmount; // describe the total debit amount
        var totalCreditAmount; // describe the total credit amount

        if (data.error.id == "0") {

            // store the debit funds table data into an array called dataDebitFunds
            $.each(data.print_dataD, function (i, item) {
                dataDebitFunds[i] = [];
                dataDebitFunds[i].push(item.datemeD);
                dataDebitFunds[i].push(item.dollarAmountD);
                dataDebitFunds[i].push(item.supportD);
            });

            console.log(dataDebitFunds);
            // store the credit funds table data into an array called dataCreditFunds
            $.each(data.print_dataC, function (i, item) {
                dataCreditFunds[i] = [];
                dataCreditFunds[i].push(item.datemeC);
                dataCreditFunds[i].push(item.dollarAmountC);
                dataCreditFunds[i].push(item.supportC);
                dataCreditFunds[i].push(item.invoice_id);
            });

            console.log(dataCreditFunds);
            // store the total debit amount 
            totalDebitAmount = data.print_data_totalD;

            // store the total credit amount
            totalCreditAmount = data.print_data_totalC;

            // store the total credit available data into totalAmount
            totalAmount = data.total;
            // display the available credit 

            $('.sumTotalAmount').text(totalAmount);

            $('.debitAmount').text(`$ ${totalDebitAmount}`);

            $('.creditAmount').text(`$ ${totalCreditAmount}`);

            $('.sumTotalAmount').text(totalAmount);
            $('.sumTotal').css('color', 'black');
            // if the total amount is negative, change the color into red
            if (totalAmount < 0) {
                $('.sumTotalAmount').css('color', 'red');
            } else {
                $('.sumTotalAmount').css('color', 'black');
            }

            // generating the table by plopping in the table data/rows

            var dragSrcRow = null; // Keep track of the source row
            var srcTable = ''; // Global tracking of table being dragged for 'over' class setting
            var rows = []; // Global rows for #example

            // create the table for debit funds

            var table = $('#print_history_table1').DataTable({

                data: dataDebitFunds,
                "destroy": true,

                columnDefs: [{
                    targets: 1,
                    className: "truncate"
                }],

                // Add HTML5 draggable class to each row
                createdRow: function (row, data, dataIndex, cells) {
                    // $(row).attr('draggable', 'true');

                    // adding truncate to the asset notes and shortening the text dispayed in the CSS
                    var td = $(row).find(".truncate");

                    td.attr("title", td.html()); //adding the title attribute so the whole text will show on hover

                    // adding the data-id to individual rows
                    // $(row).attr('data-id', group_id[dataIndex]);                    
                },

                drawCallback: function () {
                    // Add HTML5 draggable event listeners to each row
                    rows = document.querySelectorAll('#print_history_table1 tbody tr');

                    // rows.forEach(row => {
                    //     row.addEventListener('dragstart', handleDragStart);
                    //     row.addEventListener('dragenter', handleDragEnter);
                    //     row.addEventListener('dragover', handleDragOver);
                    //     row.addEventListener('dragleave', handleDragLeave);
                    //     row.addEventListener('drop', handleDrop);
                    //     row.addEventListener('dragend', handleDragEnd);
                    // });
                }
            });

            // create the table for credit funds
            var table = $('#print_history_table2').DataTable({

                data: dataCreditFunds,
                "destroy": true,

                columnDefs: [{
                    targets: 1,
                    className: "truncate"
                }],

                // Add HTML5 draggable class to each row
                createdRow: function (row, data, dataIndex, cells) {
                    $(row).attr('draggable', 'true');

                    // adding truncate to the asset notes and shortening the text dispayed in the CSS
                    var td = $(row).find(".truncate");

                    td.attr("title", td.html()); //adding the title attribute so the whole text will show on hover

                    // adding the data-id to individual rows
                    // $(row).attr('data-id', group_id[dataIndex]);                    
                },

                drawCallback: function () {
                    // Add HTML5 draggable event listeners to each row
                    rows = document.querySelectorAll('#print_history_table2 tbody tr');

                    rows.forEach(row => {
                        row.addEventListener('dragstart', handleDragStart);
                        row.addEventListener('dragenter', handleDragEnter);
                        row.addEventListener('dragover', handleDragOver);
                        row.addEventListener('dragleave', handleDragLeave);
                        row.addEventListener('drop', handleDrop);
                        row.addEventListener('dragend', handleDragEnd);
                    });
                }
            });

            // add place holder to the search filter input field
            $('#print_history_table1_filter label input').attr("placeholder", "Search");
            $('#print_history_table2_filter label input').attr("placeholder", "Search");



        } else {
            alert("wrong");
        }

    });
}

function get_borrower_history(id) {
    // console.log(id);

    var getBorrowerHistory = $.ajax({
        url: "services/get_borrower_history.php",
        type: "POST",
        dataType: "json",
        data: {
            id: id
        },
        // adding fade effect on window and cursor to clock while the page is laoding
        beforeSend: function () {
            $("body").css("cursor", "wait");
            $("body").css("opacity", "0.5");
        },
    });


    getBorrowerHistory.fail(function (jqXHR, textStatus) {
        alert("Something went Wrong! (getBorrowerHistory)" +
            textStatus);
    });

    getBorrowerHistory.done(function (data) {

        $("body").css("cursor", "default");
        $("body").css("opacity", "");

        var databhArray = []; // the array which is used to populate the tables for borrower history

        // in php, it's throwing back a string, the error number
        if (data.error.id == "0") {
            $.each(data.b_history, function (i, item) {
                databhArray[i] = [];
                databhArray[i].push(item.asset_description);
                databhArray[i].push(item.assets_notes);
                databhArray[i].push(item.assets_serial_number);
                databhArray[i].push(item.assets_barcode);
                databhArray[i].push(item.assets_logged_out_out_time);
                // databhArray[i].push(item.assets_logged_out_due_time);
                databhArray[i].push(item.assets_logged_out_in_time);
                // databhArray[i].push(item.borrowers_first_name);
                // databhArray[i].push(item.borrowers_last_name);
                // databhArray[i].push(item.borrowers_student_id);
                // databhArray[i].push(item.borrowers_dc_email);
                // databhArray[i].push(item.borrowers_other_email);
                // databhArray[i].push(item.overdue);

            });

            console.log(databhArray);
            /*************************** creating the tables for groups, output the php data to the html table ****************************/

            // generating the table by plopping in the table data/rows

            var dragSrcRow = null; // Keep track of the source row
            var srcTable = ''; // Global tracking of table being dragged for 'over' class setting
            var rows = []; // Global rows for #example


            // if ( $.fn.DataTable.isDataTable( '#borrower_history_table' ) ) {

            //   }

            var table = $('#borrower_history_table').DataTable({

                data: databhArray,
                "destroy": true,

                columnDefs: [{
                    targets: 1,
                    className: "truncate"
                }],

                // Add HTML5 draggable class to each row
                createdRow: function (row, data, dataIndex, cells) {
                    // $(row).attr('draggable', 'true');

                    // adding truncate to the asset notes and shortening the text dispayed in the CSS
                    var td = $(row).find(".truncate");

                    td.attr("title", td.html()); //adding the title attribute so the whole text will show on hover

                    // adding the data-id to individual rows
                    // $(row).attr('data-id', group_id[dataIndex]);                    
                },

                drawCallback: function () {
                    // Add HTML5 draggable event listeners to each row
                    rows = document.querySelectorAll('#borrower_history_table tbody tr');

                    // rows.forEach(row => {
                    //     row.addEventListener('dragstart', handleDragStart);
                    //     row.addEventListener('dragenter', handleDragEnter);
                    //     row.addEventListener('dragover', handleDragOver);
                    //     row.addEventListener('dragleave', handleDragLeave);
                    //     row.addEventListener('drop', handleDrop);
                    //     row.addEventListener('dragend', handleDragEnd);
                    // });
                }
            });

            // add place holder to the search filter input field
            $('#borrower_history_table_filter label input').attr("placeholder", "Search");

        } else {
            alert("wrong");
        }

    });
}


function get_assets_logged_out(array) {

    var getAssetsLoggedOut = $.ajax({
        url: "services/get_assets_logged_out.php",
        type: "POST",
        dataType: "json",
        data: {
            option: array[0]
        },
        // adding fade effect on window and cursor to clock while the page is laoding
        beforeSend: function () {
            $("body").css("cursor", "wait");
            $("body").css("opacity", "0.5");
        },
    });


    getAssetsLoggedOut.fail(function (jqXHR, textStatus) {
        alert("Something went Wrong! (getAssetsLoggedOut)" +
            textStatus);
    });

    getAssetsLoggedOut.done(function (data) {

        // bring back the window and the cursor
        $("body").css("cursor", "default");
        $("body").css("opacity", "");

        var dataArray = []; // the array which is used to populate the tables for groups
        // var group_id = []; //array which will hold all the group_id till they can be passed into the data-id of each row

        // in php, it's throwing back a string, the error number
        if (data.error.id == "0") {
            $.each(data.logs, function (i, item) {
                dataArray[i] = [];
                dataArray[i].push(item.asset_description);
                // dataArray[i].push(item.serial_number);
                dataArray[i].push(item.barcode);
                dataArray[i].push(item.out_time);
                dataArray[i].push(item.due_time);
                dataArray[i].push(item.in_time);
                dataArray[i].push(item.first_name);
                dataArray[i].push(item.last_name);
                dataArray[i].push(item.student_id);
                dataArray[i].push(item.dc_email);
                dataArray[i].push(item.other_email);
            });


            /********************************** output the php data to the html table  ******************************************/

            // generating the table by plopping in the table data/rows

            var dragSrcRow = null; // Keep track of the source row
            var srcTable = ''; // Global tracking of table being dragged for 'over' class setting
            var rows = []; // Global rows for #example


            var table = $(`${array[1]}`).DataTable({
                data: dataArray,
                "destroy": true,

                columnDefs: [{
                    targets: [8, 9],
                    className: "truncate"
                }],

                // Add HTML5 draggable class to each row
                createdRow: function (row, data, dataIndex, cells) {
                    // $(row).attr('draggable', 'true');
                    // adding truncate to the asset notes and shortening the text dispayed in the CSS
                    var td = $(row).find(".truncate");

                    td.attr("title", td.html()); //adding the title attribute so the whole text will show on hover
                    
                    // adding the data-id to individual rows
                    // $(row).attr('data-id', group_id[dataIndex]);
                },

                // // Add HTML5 draggable class to each row
                // createdRow: function (row, data, dataIndex, cells) {
                //     $(row).attr('draggable', 'true');

                //     // adding the data-id to individual rows
                //     $(row).attr('data-id', group_id[dataIndex]);
                // },

                drawCallback: function () {
                    // Add HTML5 draggable event listeners to each row
                    rows = document.querySelectorAll(`${array[1]} tbody tr`);

                    // rows.forEach(row => {
                    //     row.addEventListener('dragstart', handleDragStart);
                    //     row.addEventListener('dragenter', handleDragEnter);
                    //     row.addEventListener('dragover', handleDragOver);
                    //     row.addEventListener('dragleave', handleDragLeave);
                    //     row.addEventListener('drop', handleDrop);
                    //     row.addEventListener('dragend', handleDragEnd);
                    // });
                }
            });

            // add place holder to the search filter input field
            $('#assets_logged_out_table_filter label input').attr("placeholder", "Search");
            $('#assets_overdue_table_filter label input').attr("placeholder", "Search");
            $('#assets_overdue_10_days_table_filter label input').attr("placeholder", "Search");

            // set the focus of cursor
            $('#assets_logged_out_table_filter label input').focus();
            $('#assets_overdue_table_filter label input').focus();
            $('#assets_overdue_10_days_table_filter label input').focus();

        } else {
            alert("wrong");
        }

    });

}


function verify_barcode(barcode) {
    var verifyBarcode = $.ajax({
        url: "services/verify_barcode.php",
        type: "POST",
        dataType: "json",
        data: {
            barcode: barcode
        },
        // adding fade effect on window and cursor to clock while the page is laoding
        beforeSend: function () {
            $("body").css("cursor", "wait");
            $("body").css("opacity", "0.5");
        },
    });


    verifyBarcode.fail(function (jqXHR, textStatus) {
        alert("Something went Wrong! (verifyBarcode)" +
            textStatus);
    });

    verifyBarcode.done(function (data) {
        $("body").css("cursor", "default");
        $("body").css("opacity", "");

        //data.error.message == "Asset Scanned Successfully."
        
        if (data.error.id == "0") {
            $("#inventory_form .updateErrMess").fadeIn('fast').delay(6000).fadeOut('slow');
            $("#inventory_form .updateErrMess").text(data.error.message);
            get_inventories(0);
        } else {
        	alert(data.error.message);
        }
        
        /*
        else if (data.error.message == "Asset already scanned.") {
            alert(`Asset already scanned.`);
        } else if (data.error.message == "Asset not in the inventory.") {
            alert(`Asset not in the inventory.`);
        } else if(data.error.message == "Asset not found."){
            alert(`Asset not found.`);
        }
        */
        
        // empty the text input field, and put the focus back 
        $('.barcodeEntry').val('');
        $('.barcodeEntry').focus();
    });
}

function start_new_inventory() {
    var startNewInvent = $.ajax({
        url: "services/new_inventory.php",
        type: "POST",
        dataType: "json",
        // adding fade effect on window and cursor to clock while the page is laoding
        beforeSend: function () {
            $("body").css("cursor", "wait");
            $("body").css("opacity", "0.5");
        },
    });


    startNewInvent.fail(function (jqXHR, textStatus) {
        alert("Something went Wrong! (startNewInvent)" +
            textStatus);
    });

    startNewInvent.done(function (data) {
        $("body").css("cursor", "default");
        $("body").css("opacity", "");
        get_inventories(0);
    });
}

function get_inventory_details(){
    var getInventoryDetails = $.ajax({
        url: "services/get_inventory_detail.php",
        type: "POST",
        dataType: "json",
        // adding fade effect on window and cursor to clock while the page is laoding
        beforeSend: function () {
            $("body").css("cursor", "wait");
            $("body").css("opacity", "0.5");
        },
    });


    getInventoryDetails.fail(function (jqXHR, textStatus) {
        alert("Something went Wrong! (getInventoryDetails)" +
            textStatus);
    });

    getInventoryDetails.done(function (data) {
        $("body").css("cursor", "default");
        $("body").css("opacity", "");

        var dataArray = []; // the array which is used to populate the tables for inventory_table
        var group_id = []; //array which will hold all the group_id till they can be passed into the data-id of each row

        // in php, it's throwing back a string, the error number
        if (data.error.id == "0") {
            $.each(data.inventory_details, function (i, item) {
                dataArray[i] = [];
                dataArray[i].push(item.asset_desc);
                dataArray[i].push(item.category_name);
                dataArray[i].push(item.notes);
                dataArray[i].push(item.assets_count);
                dataArray[i].push(item.assets_lost);
                dataArray[i].push(item.assets_repair);
                dataArray[i].push(item.assets_retired);
                dataArray[i].push(item.assets_do_not_display);
                dataArray[i].push(item.assets_active);
                dataArray[i].push(item.assets_reserved);
                dataArray[i].push(item.assets_out);
                dataArray[i].push(item.assets_available);
                dataArray[i].push(item.assets_scanned_count);
                group_id.push(item.asset_desc); // pushing the id of the groups into the new array called group_id
            });


            /********************************** output the php data to the html table  ******************************************/

            // generating the table by plopping in the table data/rows

            var dragSrcRow = null; // Keep track of the source row
            var srcTable = ''; // Global tracking of table being dragged for 'over' class setting
            var rows = []; // Global rows for #example


            var table = $('#invent_detail_table1').DataTable({
                data: dataArray,
                "destroy": true,
                columnDefs: [{
                    targets: [2],
                    className: "truncate"
                }],
                // Add HTML5 draggable class to each row
                createdRow: function (row, data, dataIndex, cells) {
                    // $(row).attr('draggable', 'true');
                    // adding truncate to the asset notes and shortening the text dispayed in the CSS
                    var td = $(row).find(".truncate");

                    td.attr("title", td.html()); //adding the title attribute so the whole text will show on hover
                    // adding the data-id to individual rows, which is the asset desc
                    $(row).attr('data-id', group_id[dataIndex]);
                },

                drawCallback: function () {
                    // Add HTML5 draggable event listeners to each row
                    rows = document.querySelectorAll('#invent_detail_table1 tbody tr');

                    // rows.forEach(row => {
                    //     row.addEventListener('dragstart', handleDragStart);
                    //     row.addEventListener('dragenter', handleDragEnter);
                    //     row.addEventListener('dragover', handleDragOver);
                    //     row.addEventListener('dragleave', handleDragLeave);
                    //     row.addEventListener('drop', handleDrop);
                    //     row.addEventListener('dragend', handleDragEnd);
                    // });
                }
            });


            // add place holder to the search filter input field
            $('#invent_detail_table1_filter label input').attr("placeholder", "Search");

            // put the focus on the text entry field
            $('#invent_detail_table1_filter label input').focus();
             // adding the click event listener to the rows, which pass the asset_desc
            $('#invent_detail_table1').on('click', 'tr', function () {
                //KEEP THIS LINE FOR FUTURE REFERENCE // var data = table.row( this ).data();

                // getting the data-id of the row clicked to be updated.
                rowClickedId = this.dataset.id;
                console.log(rowClickedId);
                // file the ajax call to get_single_inventory_detail.php 
                get_single_inventory_detail(rowClickedId);
            });

        } else {
            alert("wrong");
        }

    });
}

function get_single_inventory_detail(rowClickedId) {
    var getSingleInventoryDetails = $.ajax({
        url: "services/get_single_inventory_detail.php",
        type: "POST",
        dataType: "json",
        data:{
            asset_desc: rowClickedId
        },
        // adding fade effect on window and cursor to clock while the page is laoding
        beforeSend: function () {
            $("body").css("cursor", "wait");
            $("body").css("opacity", "0.5");
        },
    });


    getSingleInventoryDetails.fail(function (jqXHR, textStatus) {
        alert("Something went Wrong! (getSingleInventoryDetails)" +
            textStatus);
    });

    getSingleInventoryDetails.done(function (data) {
        $("body").css("cursor", "default");
        $("body").css("opacity", "");

        var dataArray = []; // the array which is used to populate the tables for inventory_table
        var group_id = []; //array which will hold all the group_id till they can be passed into the data-id of each row

        // in php, it's throwing back a string, the error number
        if (data.error.id == "0") {
            $.each(data.inventory_single, function (i, item) {
                dataArray[i] = [];
                dataArray[i].push(item.barcode);
                dataArray[i].push(item.serial_number);
                dataArray[i].push(item.category_name);
                dataArray[i].push(item.action_name);
                dataArray[i].push(item.notes);
                dataArray[i].push(item.info);
                dataArray[i].push(item.out_time);
                dataArray[i].push(`${item.borrowers_id} ${item.borrowers_first_name} ${item.borrowers_last_name}`);
                dataArray[i].push(item.scan_date);
                // dataArray[i].push(item.assets_reserved);
                // dataArray[i].push(item.assets_out);
                // dataArray[i].push(item.assets_available);
                // dataArray[i].push(item.assets_scanned_count);
                group_id.push(item.asset_desc); // pushing the id of the groups into the new array called group_id
            });


            /********************************** output the php data to the html table  ******************************************/

            // generating the table by plopping in the table data/rows

            var dragSrcRow = null; // Keep track of the source row
            var srcTable = ''; // Global tracking of table being dragged for 'over' class setting
            var rows = []; // Global rows for #example


            var table = $('#invent_detail_table2').DataTable({
                data: dataArray,
                "destroy": true,
                columnDefs: [{
                    targets: [4],
                    className: "truncate"
                }],
                // Add HTML5 draggable class to each row
                createdRow: function (row, data, dataIndex, cells) {
                    // $(row).attr('draggable', 'true');
                    // adding truncate to the asset notes and shortening the text dispayed in the CSS
                    var td = $(row).find(".truncate");

                    td.attr("title", td.html()); //adding the title attribute so the whole text will show on hover
                    // adding the data-id to individual rows, which is the asset desc
                    $(row).attr('data-id', group_id[dataIndex]);
                },

                drawCallback: function () {
                    // Add HTML5 draggable event listeners to each row
                    rows = document.querySelectorAll('#invent_detail_table2 tbody tr');

                    // rows.forEach(row => {
                    //     row.addEventListener('dragstart', handleDragStart);
                    //     row.addEventListener('dragenter', handleDragEnter);
                    //     row.addEventListener('dragover', handleDragOver);
                    //     row.addEventListener('dragleave', handleDragLeave);
                    //     row.addEventListener('drop', handleDrop);
                    //     row.addEventListener('dragend', handleDragEnd);
                    // });
                }
            });

            // add place holder to the search filter input field
            $('#invent_detail_table2_filter label input').attr("placeholder", "Search");


        } else {
            alert("wrong");
        }

    });
}

function get_inventories(option) {
    var getInventories = $.ajax({
        url: "services/get_inventory.php",
        type: "POST",
        dataType: "json",
        data: {
            option: option
        },
        // adding fade effect on window and cursor to clock while the page is laoding
        beforeSend: function () {
            $("body").css("cursor", "wait");
            $("body").css("opacity", "0.5");
        },
    });


    getInventories.fail(function (jqXHR, textStatus) {
        alert("Something went Wrong! (getInventories)" +
            textStatus);
    });

    getInventories.done(function (data) {
        $("body").css("cursor", "default");
        $("body").css("opacity", "");

        var dataArray = []; // the array which is used to populate the tables for inventory_table
        var group_id = []; //array which will hold all the group_id till they can be passed into the data-id of each row

        // in php, it's throwing back a string, the error number
        if (data.error.id == "0") {
            $.each(data.inventories, function (i, item) {
                dataArray[i] = [];
                dataArray[i].push(item.barcode);
                dataArray[i].push(item.serial_number);
                dataArray[i].push(item.asset_description);
                dataArray[i].push(item.notes);
                dataArray[i].push(item.out_time);
                dataArray[i].push(item.in_time);
                dataArray[i].push(item.scan_date);
                dataArray[i].push(item.actions_name);
                group_id.push(item.asset_id); // pushing the id of the groups into the new array called group_id
            });


            /********************************** output the php data to the html table  ******************************************/

            // generating the table by plopping in the table data/rows

            var dragSrcRow = null; // Keep track of the source row
            var srcTable = ''; // Global tracking of table being dragged for 'over' class setting
            var rows = []; // Global rows for #example


            var table = $('#inventory_table').DataTable({
                data: dataArray,
                "destroy": true,
                columnDefs: [{
                    targets: [3],
                    className: "truncate"
                }],
                // Add HTML5 draggable class to each row
                createdRow: function (row, data, dataIndex, cells) {
                    // $(row).attr('draggable', 'true');
                    // adding truncate to the asset notes and shortening the text dispayed in the CSS
                    var td = $(row).find(".truncate");

                    td.attr("title", td.html()); //adding the title attribute so the whole text will show on hover
                    
                    // adding the data-id to individual rows
                    $(row).attr('data-id', group_id[dataIndex]);
                },

                drawCallback: function () {
                    // Add HTML5 draggable event listeners to each row
                    rows = document.querySelectorAll('#inventory_table tbody tr');

                    // rows.forEach(row => {
                    //     row.addEventListener('dragstart', handleDragStart);
                    //     row.addEventListener('dragenter', handleDragEnter);
                    //     row.addEventListener('dragover', handleDragOver);
                    //     row.addEventListener('dragleave', handleDragLeave);
                    //     row.addEventListener('drop', handleDrop);
                    //     row.addEventListener('dragend', handleDragEnd);
                    // });
                }
            });

            // add place holder to the search filter input field
            $('#inventory_table_filter label input').attr("placeholder", "Search");

        } else {
            alert("wrong");
        }

    });
}

function get_groups() {

    var getGroups = $.ajax({
        url: "services/get_groups.php",
        type: "POST",
        dataType: "json",
        // adding fade effect on window and cursor to clock while the page is laoding
        beforeSend: function () {
            $("body").css("cursor", "wait");
            $("body").css("opacity", "0.5");
        },
    });


    getGroups.fail(function (jqXHR, textStatus) {
        alert("Something went Wrong! (getGroups)" +
            textStatus);
    });

    getGroups.done(function (data) {
        $("body").css("cursor", "default");
        $("body").css("opacity", "");

        var dataArray = []; // the array which is used to populate the tables for groups
        var group_id = []; //array which will hold all the group_id till they can be passed into the data-id of each row

        // in php, it's throwing back a string, the error number
        if (data.error.id == "0") {
            $.each(data.groups, function (i, item) {
                dataArray[i] = [];
                dataArray[i].push(item.name);
                dataArray[i].push(item.program_name);
                dataArray[i].push(item.year);
                group_id.push(item.id); // pushing the id of the groups into the new array called group_id
            });


            /********************************** output the php data to the html table  ******************************************/

            // generating the table by plopping in the table data/rows

            var dragSrcRow = null; // Keep track of the source row
            var srcTable = ''; // Global tracking of table being dragged for 'over' class setting
            var rows = []; // Global rows for #example


            if ($.fn.DataTable.isDataTable(groups_table)) {
                groups_table.destroy();
                groups_table.MakeCellsEditable("destroy");
            }

            groups_table = $('#groups_table').DataTable({
                data: dataArray,
                "destroy": true,
                // Add HTML5 draggable class to each row
                createdRow: function (row, data, dataIndex, cells) {
                    // $(row).attr('draggable', 'true');

                    // adding the data-id to individual rows
                    $(row).attr('data-id', group_id[dataIndex]);
                },

                drawCallback: function () {
                    // Add HTML5 draggable event listeners to each row
                    rows = document.querySelectorAll('#groups_table tbody tr');

                    // rows.forEach(row => {
                    //     row.addEventListener('dragstart', handleDragStart);
                    //     row.addEventListener('dragenter', handleDragEnter);
                    //     row.addEventListener('dragover', handleDragOver);
                    //     row.addEventListener('dragleave', handleDragLeave);
                    //     row.addEventListener('drop', handleDrop);
                    //     row.addEventListener('dragend', handleDragEnd);
                    // });
                }
            });

            // add place holder to the search filter input field
            $('#groups_table_filter label input').attr("placeholder", "Search");


            // adding the click event listener to the rows
            $('#groups_table').on('click', 'tr', function () {
                //KEEP THIS LINE FOR FUTURE REFERENCE // var data = table.row( this ).data();

                // getting the data-id of the row clicked to be updated.
                rowClickedId = this.dataset.id;
            });



            // adding the edit functionality to the 1st column.
            groups_table.MakeCellsEditable({
                "onUpdate": ()=>{console.log(this);
                },
                "inputCss": 'my-input-class',
                "columns": [0], //changing what columns should be editable
                "confirmationButton": { // could also be true
                    "confirmCss": 'my-confirm-class',
                    "cancelCss": 'my-cancel-class'
                },
                "inputTypes": [{
                    "column": 0,
                    "type": "text",
                    "options": null
                }]
            });

        } else {
            alert("wrong");
        }

    });

}


function get_program_names() {
    var getProgramNames = $.ajax({
        url: "services/get_program_names.php",
        type: "POST",
        dataType: "json",
        // adding fade effect on window and cursor to clock while the page is laoding
        beforeSend: function () {
            $("body").css("cursor", "wait");
            $("body").css("opacity", "0.5");
        },
    });


    getProgramNames.fail(function (jqXHR, textStatus) {
        alert("Something went Wrong! (getProgramNames)" +
            textStatus);
    });

    getProgramNames.done(function (data) {
        $("body").css("cursor", "default");
        $("body").css("opacity", "");

        var content = `<option value="">-----------------------------Select-----------------------------</option>`
        // in php, it's throwing back a string, the error number
        if (data.error.id == "0") {
            $.each(data.programs, function (i, item) {
                content += `<option value="${item.id}">${item.name}</option>
                `;
            });
        } else {
            alert("wrong");
        }
        $("#program_name").html(content);
    });
};

function get_borrower_status(id) {
    var getBorrowerStatus = $.ajax({
        url: "services/get_borrower_status.php",
        type: "POST",
        data: {
            id: id
        },
        dataType: "json",
        // adding fade effect on window and cursor to clock while the page is laoding
        beforeSend: function () {
            $("body").css("cursor", "wait");
            $("body").css("opacity", "0.5");
        },
    });


    getBorrowerStatus.fail(function (jqXHR, textStatus) {
        alert("Something went Wrong! (getBorrowerStatus)" +
            textStatus);
    });

    getBorrowerStatus.done(function (data) {
        $("body").css("cursor", "default");
        $("body").css("opacity", "");

        var content = `<option value="">-----------------------------Select-----------------------------</option>`
        // in php, it's throwing back a string, the error number
        if (data.error.id == "0") {
            $.each(data.borrower_status, function (i, item) {
                content += `<option value="${item.borrower_status_id}">${item.name}</option>
                `;
                // console.log(item.borrower_status_id);
            });
        } else {
            alert("wrong");
        }
        $("#borrower_status").html(content);
    });
};

function get_borrowers() {

    var getBorrowers = $.ajax({
        url: "services/get_borrowers.php",
        type: "POST",
        dataType: "json",
        // adding fade effect on window and cursor to clock while the page is laoding
        beforeSend: function () {
            $("body").css("cursor", "wait");
            $("body").css("opacity", "0.5");
        },
    });


    getBorrowers.fail(function (jqXHR, textStatus) {
        alert("Something went Wrong! (getBorrowers)" +
            textStatus);
    });

    getBorrowers.done(function (data) {
        $("body").css("cursor", "default");
        $("body").css("opacity", "");

        var content = `<option value="">-----------------------------Select-----------------------------</option>`
        // in php, it's throwing back a string, the error number
        if (data.error.id == "0") {
            $.each(data.borrowers, function (i, item) {
                content += `<option value="${item.id}" class="getBorrower">${item.borrowers_student_id} ${item.borrowers_first_name} ${item.borrowers_last_name}</option>
                `;
            });
        } else {
            alert("wrong");
        }
        $(".getBorrowersList").html(content);
        $(".getBorrowersList2").html(content);
        $(".getBorrowersList3").html(content);
    });

}

function get_borrower(id) {
    var getBorrower = $.ajax({
        url: "services/get_borrower.php",
        type: "POST",
        data: {
            id: id
        },
        dataType: "json",
        // adding fade effect on window and cursor to clock while the page is laoding
        beforeSend: function () {
            $("body").css("cursor", "wait");
            $("body").css("opacity", "0.5");
        },
    });

    getBorrower.fail(function (jqXHR, textStatus) {
        alert("Something went Wrong! (get_borrower)" +
            textStatus);
    });

    getBorrower.done(function (data) {
        $("body").css("cursor", "default");
        $("body").css("opacity", "");
        // alert(data.borrower);
        if (data.error.id == "0") {
            // output the information of that asset from php database
            var borrower_id = data.borrower.id;
            var borrower_student_id = data.borrower.student_id;
            var borrower_first_name = data.borrower.first_name;
            var borrower_last_name = data.borrower.last_name;
            var borrower_dc_email = data.borrower.dc_email;
            var borrower_other_email = data.borrower.other_email;
            var borrower_email_confirmation = data.borrower.email_confirmation;
            var borrower_program_year = data.borrower.program_year;
            var borrower_phone = data.borrower.phone;
            var borrower_strikes = data.borrower.strikes;
            var programs_id = data.borrower.programs_id;
            console.log(programs_id);

            // console.log(categories_id);
            $('#borrower_id').val(borrower_id);
            $('#br_student_id').val(borrower_student_id);
            $('#br_first_name').val(borrower_first_name);
            $('#br_last_name').val(borrower_last_name);
            $('#br_dc_email').val(borrower_dc_email);
            $('#br_other_email').val(borrower_other_email);
            $('#br_phone').val(borrower_phone);
            $('#br_program_year').val(borrower_program_year);
            console.log(borrower_strikes);
            $('#borrower_status').val(borrower_strikes);
            $('#program_name').val(programs_id);


            if (borrower_email_confirmation == "1") {
                $("#email_confirmation").prop("checked", true);
            } else {
                $("#email_confirmation").prop("checked", false);
            }


        } else {
            alert("getBorrower wrong");
        }

    });
}

function get_password(id) {
    var getPassword = $.ajax({
        url: "services/get_borrower.php",
        type: "POST",
        data: {
            id: id
        },
        dataType: "json",
        // adding fade effect on window and cursor to clock while the page is laoding
        beforeSend: function () {
            $("body").css("cursor", "wait");
            $("body").css("opacity", "0.5");
        },
    });

    getPassword.fail(function (jqXHR, textStatus) {
        alert("Something went Wrong! (getPassword)" +
            textStatus);
    });

    getPassword.done(function (data) {
        $("body").css("cursor", "default");
        $("body").css("opacity", "");
        // var content = "";
        if (data.error.id == "0") {
            // output the information of that asset from php database
            var borrower_student_id = data.borrower.student_id;
            var borrower_first_name = data.borrower.first_name;
            var borrower_last_name = data.borrower.last_name;


            // console.log(categories_id);
            $('#psw_student_id').val(borrower_student_id);
            $('#psw_first_name').val(borrower_first_name);
            $('#psw_last_name').val(borrower_last_name);
            $('#borrower_password').val("");
            $('#password_id').val(id);
        } else {
            alert("getPassword wrong");
        }

    });
}


function get_hisAssets() {

    var getHisAssets = $.ajax({
        url: "services/get_assets.php",
        type: "POST",
        dataType: "json",
        // adding fade effect on window and cursor to clock while the page is laoding
        beforeSend: function () {
            $("body").css("cursor", "wait");
            $("body").css("opacity", "0.5");
        },
    });


    getHisAssets.fail(function (jqXHR, textStatus) {
        alert("Something went Wrong! (getHisAssets)" +
            textStatus);
    });

    getHisAssets.done(function (data) {
        $("body").css("cursor", "default");
        $("body").css("opacity", "");
        var content = `<option value="">------------------------Select the Asset------------------------</option>`
        // in php, it's throwing back a string, the error number
        if (data.error.id == "0") {
            $.each(data.assets, function (i, item) {
                content += `<option value="${item.barcode}" class="getAsset">${item.barcode} ${item.asset_description}</option>
                `;
            });
        } else {
            alert("wrong");
        }
        $(".getAssetsList2").html(content);
        // $(".getAssetsList2").html(content);
    });

}


function get_categories() {

    var getCategories = $.ajax({
        url: "services/get_categories.php",
        type: "POST",
        dataType: "json",
        // adding fade effect on window and cursor to clock while the page is laoding
        // beforeSend: function () {
        //     $("body").css("cursor", "wait");
        //     $("body").css("opacity", "0.5");
        // },
    });


    getCategories.fail(function (jqXHR, textStatus) {
        alert("Something went Wrong! (getCategories)" +
            textStatus);
    });

    getCategories.done(function (data) {
        // $("body").css("cursor", "default");
        // $("body").css("opacity", "");
        var content = `<option value="">------------------------------Select------------------------------</option>`
        // in php, it's throwing back a string, the error number
        if (data.error.id == "0") {
            $.each(data.categories, function (i, item) {
                content += `<option value="${item.id}" >${item.name}</option>
                `;
            });
        } else {
            alert("wrong");
        }
        $("#categories_list").html(content);
        $("#reserve_categories_list").html(content);

    });

}

function get_actions() {

    var getActions = $.ajax({
        url: "services/actions.php",
        type: "POST",
        dataType: "json",
        // adding fade effect on window and cursor to clock while the page is laoding
        beforeSend: function () {
            $("body").css("cursor", "wait");
            $("body").css("opacity", "0.5");
        },
    });


    getActions.fail(function (jqXHR, textStatus) {
        alert("Something went Wrong! (getActions)" +
            textStatus);
    });

    getActions.done(function (data) {
        $("body").css("cursor", "default");
        $("body").css("opacity", "");
        var content = `<option value="">------------------------------Select------------------------------</option>`
        // in php, it's throwing back a string, the error number
        if (data.error.id == "0") {
            $.each(data.actions, function (i, item) {
                content += `<option value="${item.id}" class="getAsset">${item.name}</option>
                `;
            });
        } else {
            alert("wrong");
        }
        $("#actions_list").html(content);
    });

}

function get_borrowers_print_history() {

    var getBorrowers = $.ajax({
        url: "services/get_borrowers.php",
        type: "POST",
        dataType: "json",
        // adding fade effect on window and cursor to clock while the page is laoding
        beforeSend: function () {
            $("body").css("cursor", "wait");
            $("body").css("opacity", "0.5");
        },
    });


    getBorrowers.fail(function (jqXHR, textStatus) {
        alert("Something went Wrong! (getBorrowers)" +
            textStatus);
    });

    getBorrowers.done(function (data) {
        $("body").css("cursor", "default");
        $("body").css("opacity", "");
        var content = `<option value="">-----------------------------Select-----------------------------</option>`
        // in php, it's throwing back a string, the error number
        if (data.error.id == "0") {
            $.each(data.borrowers, function (i, item) {
                content += `<option value="${item.borrowers_student_id}" >${item.borrowers_student_id} ${item.borrowers_first_name} ${item.borrowers_last_name}</option>
                `;
            });
        } else {
            alert("wrong");
        }
        $(".getBorrowersList4").html(content);
    });

}

function get_faculty() {

    var getFaculty = $.ajax({
        url: "services/borrowers_long_term.php",
        type: "POST",
        dataType: "json",
        // adding fade effect on window and cursor to clock while the page is laoding
        beforeSend: function () {
            $("body").css("cursor", "wait");
            $("body").css("opacity", "0.5");
        },
    });


    getFaculty.fail(function (jqXHR, textStatus) {
        alert("Something went Wrong! (getFaculty)" +
            textStatus);
    });

    getFaculty.done(function (data) {
        $("body").css("cursor", "default");
        $("body").css("opacity", "");
        var content = `<option value="">-----------------------------Select-----------------------------</option>`
        // in php, it's throwing back a string, the error number
        if (data.error.id == "0") {
            $.each(data.borrowers, function (i, item) {
                content += `<option value="${item.borrowers_student_id}" >${item.borrowers_student_id} ${item.borrowers_first_name} ${item.borrowers_last_name}</option>
                `;
            });
        } else {
            alert("wrong");
        }
        $(".getFacultyList").html(content);
    });

}

function get_rooms() {

    var getRooms = $.ajax({
        url: "services/get_rooms.php",
        type: "POST",
        dataType: "json",
        // adding fade effect on window and cursor to clock while the page is laoding
        beforeSend: function () {
            $("body").css("cursor", "wait");
            $("body").css("opacity", "0.5");
        },
    });

    getRooms.fail(function (jqXHR, textStatus) {
        alert("Something went Wrong! (getRooms)" +
            textStatus);
    });

    getRooms.done(function (data) {
        $("body").css("cursor", "default");
        $("body").css("opacity", "");
        var content = `<option value="">------------------------Select the room------------------------</option>`
        // in php, it's throwing back a string, the error number
        if (data.error.id == "0") {
            $.each(data.rooms, function (i, item) {
                content += `<option value="${item.id}" >${item.name}</option>
                `;
            });
        } else {
            alert("wrong");
        }
        $("#roomsList").html(content);
    });

}

function update_group_reserve(groups_id_array, groups_name_array, asset_desc) {


    var groups_id_array = JSON.stringify(groups_id_array);
    var groups_name_array = JSON.stringify(groups_name_array);

    var updateGroupReserve = $.ajax({
        url: "services/update_group_reserve.php",
        type: "POST",
        data: {
            groups_id_array: groups_id_array,
            groups_name_array: groups_name_array,
            asset_desc: asset_desc
        },
        dataType: "json",
        // adding fade effect on window and cursor to clock while the page is laoding
        beforeSend: function () {
            $("body").css("cursor", "wait");
            $("body").css("opacity", "0.5");
        },
    });

    updateGroupReserve.fail(function (jqXHR, textStatus) {
        alert("Something went Wrong! (updateGroupReserve)" +
            textStatus);
    });

    updateGroupReserve.done(function (data) {
        $("body").css("cursor", "default");
        $("body").css("opacity", "");
        $("#reserve_groups_form .updateErrMess").fadeIn('fast').delay(3000).fadeOut('slow');
        $("#reserve_groups_form .updateErrMess").text(data.error.message);
        // also update the dropdown list
        // get_assets();
        console.log(data.error);

    });
}

function get_group_reserve(asset_desc) {
    console.log(asset_desc);
    var getGroupReserve = $.ajax({
        url: "services/get_group_reserve.php",
        type: "POST",
        data: {
            asset_desc: asset_desc
        },
        dataType: "json",
        // adding fade effect on window and cursor to clock while the page is laoding
        beforeSend: function () {
            $("body").css("cursor", "wait");
            $("body").css("opacity", "0.5");
        },
    });

    getGroupReserve.fail(function (jqXHR, textStatus) {
        alert("Something went Wrong! (getGroupReserve)" +
            textStatus);
    });

    getGroupReserve.done(function (data) {
        $("body").css("cursor", "default");
        $("body").css("opacity", "");

        var dataArray1 = []; // the array which is used to populate the left table
        var group_id1 = []; //array which will hold all the group_id till they can be passed into the data-id of each row
        var dataArray2 = []; // the array which is used to populate the right table
        var group_id2 = []; //array which will hold all the group_id till they can be passed into the data-id of each row


        // in php, it's throwing back a string, the error number
        if (data.error.id == "0") {
            $.each(data.groups_reserve, function (i, item) {
                dataArray1[i] = [];
                dataArray1[i].push(item.name);
                dataArray1[i].push(item.program_name);
                dataArray1[i].push(item.year);
                group_id1.push(item.id); // pushing the id of the groups into the new array called group_id
            });

            $.each(data.groups_can_be_reserve, function (i, item) {
                dataArray2[i] = [];
                dataArray2[i].push(item.name);
                dataArray2[i].push(item.program_name);
                dataArray2[i].push(item.year);
                group_id2.push(item.id); // pushing the id of the groups into the new array called group_id
            });

            /********************************** output the php data to the html table  ******************************************/


            var table = $('#reserve_groups_table1').DataTable({
                data: dataArray1,
                "destroy": true,
                "order": [
                    [2, "asc"]
                ],
                // Add HTML5 draggable class to each row
                createdRow: function (row, data, dataIndex, cells) {
                    $(row).attr('draggable', 'true');

                    // adding the data-id to individual rows
                    $(row).attr('data-id', group_id1[dataIndex]);
                },

                drawCallback: function () {
                    // Add HTML5 draggable event listeners to each row
                    rows = document.querySelectorAll('#reserve_groups_table1 tbody tr');

                    rows.forEach(row => {
                        row.addEventListener('dragstart', handleDragStart);
                        row.addEventListener('dragenter', handleDragEnter);
                        row.addEventListener('dragover', handleDragOver);
                        row.addEventListener('dragleave', handleDragLeave);
                        row.addEventListener('drop', handleDrop);
                        row.addEventListener('dragend', handleDragEnd);
                    });
                }
            });

            var table = $('#reserve_groups_table2').DataTable({
                data: dataArray2,
                "destroy": true,
                "order": [
                    [2, "asc"]
                ],
                // Add HTML5 draggable class to each row
                createdRow: function (row, data, dataIndex, cells) {
                    $(row).attr('draggable', 'true');

                    // adding the data-id to individual rows
                    $(row).attr('data-id', group_id2[dataIndex]);
                },

                drawCallback: function () {
                    // Add HTML5 draggable event listeners to each row
                    rows2 = document.querySelectorAll('#reserve_groups_table2 tbody tr');

                    rows2.forEach(row => {
                        row.addEventListener('dragstart', handleDragStart);
                        row.addEventListener('dragenter', handleDragEnter);
                        row.addEventListener('dragover', handleDragOver);
                        row.addEventListener('dragleave', handleDragLeave);
                        row.addEventListener('drop', handleDrop);
                        row.addEventListener('dragend', handleDragEnd);
                    });
                }
            });

            // add place holder to the search filter input field
            $('#reserve_groups_table1_filter').css("display", "none");

            $('#reserve_groups_table1_filter label input').attr("placeholder", "Search");
            $('#reserve_groups_table2_filter label input').attr("placeholder", "Search");


            // // adding the click event listener to the rows
            // $('#groups_table').on('click', 'tr', function () {
            //     //KEEP THIS LINE FOR FUTURE REFERENCE // var data = table.row( this ).data();

            //     // getting the data-id of the row clicked to be updated.
            //     rowClickedId = this.dataset.id;
            // });



            // // adding the edit functionality to the 1st column.
            // table.MakeCellsEditable({
            //     "onUpdate": myCallbackFunction,
            //     "inputCss": 'my-input-class',
            //     "columns": [0], //changing what columns should be editable
            //     "confirmationButton": { // could also be true
            //         "confirmCss": 'my-confirm-class',
            //         "cancelCss": 'my-cancel-class'
            //     },
            //     "inputTypes": [{
            //         "column": 0,
            //         "type": "text",
            //         "options": null
            //     }]
            // });

        } else {
            alert("wrong");
        }

    });
}

function get_groups_reserve() {
    var getGroupsReserve = $.ajax({
        url: "services/get_groups_reserve.php",
        type: "POST",
        dataType: "json",
        // adding fade effect on window and cursor to clock while the page is laoding
        beforeSend: function () {
            $("body").css("cursor", "wait");
            $("body").css("opacity", "0.5");
        },
    });


    getGroupsReserve.fail(function (jqXHR, textStatus) {
        alert("Something went Wrong! (getGroupsReserve)" +
            textStatus);
    });

    getGroupsReserve.done(function (data) {
        $("body").css("cursor", "default");
        $("body").css("opacity", "");
        var content = `<option value="">------------------------Select------------------------</option>`
        // in php, it's throwing back a string, the error number
        if (data.error.id == "0") {
            $.each(data.groups_reserve, function (i, item) {
                content += `<option value="${item.asset_desc}" >${item.asset_desc}</option>
                `;
            });
        } else {
            alert("wrong");
        }
        $("#groups_reserve_list").html(content);

    });
}

function get_assets_reserves() {
    var getAssetsReserves = $.ajax({
        url: "services/get_assets_reserves.php",
        type: "POST",
        dataType: "json",
        // adding fade effect on window and cursor to clock while the page is laoding
        beforeSend: function () {
            $("body").css("cursor", "wait");
            $("body").css("opacity", "0.5");
        },
    });


    getAssetsReserves.fail(function (jqXHR, textStatus) {
        alert("Something went Wrong! (getAssetsReserves)" +
            textStatus);
    });

    getAssetsReserves.done(function (data) {
        $("body").css("cursor", "default");
        $("body").css("opacity", "");

        var dataArray = []; // the array which is used to populate the tables for groups
        var group_id = []; //array which will hold all the group_id till they can be passed into the data-id of each row


        // fill in the drop down menu 
        var content = `<option value="">------------------------------Select------------------------------</option>`
        // in php, it's throwing back a string, the error number
        if (data.error.id == "0") {
            $.each(data.assets_reserves_menu, function (i, item) {
                content += `<option value="${item.asset_desc}" class="getAsset">${item.asset_desc}</option>
                `;
            });
        } else {
            alert("wrong");
        }
        $(".getReserveAssetList").html(content);
        $(".getReserveAssetList").focus();

        // fill in the table down below
        if (data.error.id == "0") {
            $.each(data.assets_reserves, function (i, item) {
                dataArray[i] = [];
                dataArray[i].push(item.asset_desc);
                dataArray[i].push(item.assets_reserves_count);
                dataArray[i].push(item.assets_reserves_active);
                dataArray[i].push(item.assets_reserves_reserved);
                dataArray[i].push(item.assets_reserves_out);
                dataArray[i].push(item.assets_reserves_available);
                dataArray[i].push(item.category_name);
                dataArray[i].push(item.notes);
                dataArray[i].push(item.assets_reserves_replacement_cost);
                group_id.push(item.id); // pushing the id of the groups into the new array called group_id
            });


            /********************************** output the php data to the html table  ******************************************/

            // generating the table by plopping in the table data/rows

            var dragSrcRow = null; // Keep track of the source row
            var srcTable = ''; // Global tracking of table being dragged for 'over' class setting
            var rows = []; // Global rows for #example


            var table = $('#reserve_table').DataTable({
                data: dataArray,
                "destroy": true,
                columnDefs: [{
                    targets: [7],
                    className: "truncate"
                }],

                // Add HTML5 draggable class to each row
                createdRow: function (row, data, dataIndex, cells) {
                    // $(row).attr('draggable', 'true');
                    // adding truncate to the asset notes and shortening the text dispayed in the CSS
                    var td = $(row).find(".truncate");

                    td.attr("title", td.html()); //adding the title attribute so the whole text will show on hover
                    // adding the data-id to individual rows
                    $(row).attr('data-id', group_id[dataIndex]);
                },

                drawCallback: function () {
                    // Add HTML5 draggable event listeners to each row
                    rows = document.querySelectorAll('#reserve_table tbody tr');

                    // rows.forEach(row => {
                    //     row.addEventListener('dragstart', handleDragStart);
                    //     row.addEventListener('dragenter', handleDragEnter);
                    //     row.addEventListener('dragover', handleDragOver);
                    //     row.addEventListener('dragleave', handleDragLeave);
                    //     row.addEventListener('drop', handleDrop);
                    //     row.addEventListener('dragend', handleDragEnd);
                    // });
                }
            });

            // add place holder to the search filter input field
            $('#reserve_table_filter label input').attr("placeholder", "Search");

        } else {
            alert("wrong");
        }

    });
}

// function get_assets_reserves_list(){
//     var getAssetsReservesList = $.ajax({
//         url: "services/get_assets_reserves.php",
//         type: "POST",
//         dataType: "json",
//         // adding fade effect on window and cursor to clock while the page is laoding
//         beforeSend: function () {
//             $("body").css("cursor", "wait");
//             $("body").css("opacity", "0.5");
//         },
//     });


//     getAssetsReservesList.fail(function (jqXHR, textStatus) {
//         alert("Something went Wrong! (getAssetsReservesList)" +
//             textStatus);
//     });

//     getAssetsReservesList.done(function (data) {
//         $("body").css("cursor", "default");
//         $("body").css("opacity", "");
//         var content = `<option value="">------------------------------Select------------------------------</option>`
//         // in php, it's throwing back a string, the error number
//         if (data.error.id == "0") {
//             $.each(data.assets_reserves, function (i, item) {
//                 content += `<option value="${item.asset_desc}" class="getAsset">${item.asset_desc}</option>
//                 `;
//             });
//         } else {
//             alert("wrong");
//         }
//         $(".getReserveAssetList").html(content);
//         $(".getReserveAssetList").focus();
//     });
// }

function get_assets() {

    var getAssets = $.ajax({
        url: "services/get_assets.php",
        type: "POST",
        dataType: "json",
        // adding fade effect on window and cursor to clock while the page is laoding
        beforeSend: function () {
            $("body").css("cursor", "wait");
            $("body").css("opacity", "0.5");
        },
    });


    getAssets.fail(function (jqXHR, textStatus) {
        alert("Something went Wrong! (getAssets)" +
            textStatus);
    });

    getAssets.done(function (data) {
        $("body").css("cursor", "default");
        $("body").css("opacity", "");
        var content = `<option value="">------------------------Select the Asset------------------------</option>`
        // in php, it's throwing back a string, the error number
        if (data.error.id == "0") {
            $.each(data.assets, function (i, item) {
                content += `<option value="${item.id}" class="getAsset">${item.barcode} ${item.asset_description}</option>
                `;
            });
        } else {
            alert("wrong");
        }
        $(".getAssetsList").html(content);
        $(".getAssetsList3").html(content);
        // $(".getAssetsList2").html(content);
    });

}


function get_room(id) {
    var getRoom = $.ajax({
        url: "services/get_room.php",
        type: "POST",
        data: {
            room_id: id
        },
        dataType: "json",
        // adding fade effect on window and cursor to clock while the page is laoding
        beforeSend: function () {
            $("body").css("cursor", "wait");
            $("body").css("opacity", "0.5");
        },
    });

    getRoom.fail(function (jqXHR, textStatus) {
        alert("Something went Wrong! (getRoom)" +
            textStatus);
    });

    getRoom.done(function (data) {
        $("body").css("cursor", "default");
        $("body").css("opacity", "");
        // alert(data.asset);
        // var content = "";
        if (data.error.id == "0") {
            // output the information of that asset from php database
            var room_id = data.room.id;
            var room_name = data.room.name;
            var room_desc = data.room.description;
            var block_size = data.room.block_size;
            var block_start = data.room.block_start;
            var block_number = data.room.block_number;
            var restrictions_day = data.room.restrictions_day;
            var restrictions_night = data.room.restrictions_night;
            var notes_bottom = data.room.notes_bottom;

            // console.log(categories_id);
            $('#room_id').val(room_id);
            $('#room_name').val(room_name);
            $('#room_desc').val(room_desc);
            $('#bottom_notes').val(notes_bottom);
            $('#block_size').val(block_size);
            $('#block_start').val(block_start);
            $('#nodb').val(block_number);
            $('#restr_day').val(restrictions_day);
            $('#restr_night').val(restrictions_night);
        } else {
            alert("get room wrong");
        }

    });
}

function get_assets_reserve(asset_desc) {
    var getAssetsReserve = $.ajax({
        url: "services/get_assets_reserve.php",
        type: "POST",
        data: {
            asset_desc: asset_desc
        },
        dataType: "json",
        // adding fade effect on window and cursor to clock while the page is laoding
        beforeSend: function () {
            $("body").css("cursor", "wait");
            $("body").css("opacity", "0.5");
        },
    });

    getAssetsReserve.fail(function (jqXHR, textStatus) {
        alert("Something went Wrong! (getAssetsReserve)" +
            textStatus);
    });

    getAssetsReserve.done(function (data) {
        $("body").css("cursor", "default");
        $("body").css("opacity", "");
        // alert(data.asset);
        // var content = "";
        if (data.error.id == "0") {
            // output the information of that asset from php database
            var asset_desc = data.assets_reserve1.reserve_asset_description;
            var reserve_num = data.assets_reserve2.reserve_num;
            var category = data.assets_reserve1.reserve_category_id;
            var notes = data.assets_reserve1.reserve_notes;
            var replacement_cost = data.assets_reserve2.reserve_replacement_cost;

            // console.log(categories_id);

            $('#assets_reserve_desc').val(asset_desc);
            $('#assets_reserve').val(reserve_num);
            $('#reserve_categories_list').val(category);
            $('#reserve_notes').val(notes);
            $('#replacement_cost').val(replacement_cost);

        } else {
            alert("get assets reserve wrong");
        }

    });
}


function get_asset(id) {
    var getAsset = $.ajax({
        url: "services/get_asset.php",
        type: "POST",
        data: {
            id: id
        },
        dataType: "json",
        // adding fade effect on window and cursor to clock while the page is laoding
        beforeSend: function () {
            $("body").css("cursor", "wait");
            $("body").css("opacity", "0.5");
        },
    });

    getAsset.fail(function (jqXHR, textStatus) {
        alert("Something went Wrong! (getAsset)" +
            textStatus);
    });

    getAsset.done(function (data) {
        $("body").css("cursor", "default");
        $("body").css("opacity", "");
        // alert(data.asset);
        // var content = "";
        if (data.error.id == "0") {
            // output the information of that asset from php database
            var asset_id = data.asset.id;
            var barcode = data.asset.barcode;
            var asset_desc = data.asset.asset_description;
            var notes = data.asset.notes;
            var info = data.asset.info;
            var serial_number = data.asset.serial_number;
            var actions_id = data.asset.actions_id;
            var categories_id = data.asset.categories_id;

            // console.log(categories_id);
            $('#asset_id').val(asset_id);
            $('#barcode').val(barcode);
            $('#asset_desc').val(asset_desc);
            $('#notes').val(notes);
            $('#info').val(info);
            $('#serial_number').val(serial_number);
            $('#actions_list').val(actions_id);
            $('#categories_list').val(categories_id);
        } else {
            alert("get asset wrong");
        }

    });
}

// insert long term signout record

$("#long_term_signout_form").on('submit', function (e) {
    e.preventDefault();

    $.ajax({
        type: 'POST',
        url: "services/insert_long_term_signout.php",
        data: new FormData(this),
        dataType: "json",
        contentType: false,
        cache: false,
        processData: false,

        beforeSend: function () {
            $('.priBtn').attr("disabled", "disabled");
            $('#long_term_signout_form').css("opacity", "0.5");
        },

        success: function (data) {
            $('#long_term_signout_form').css("opacity", "");
            $(".priBtn").removeAttr("disabled");
            $("#long_term_signout_form .updateErrMess").fadeIn('fast').delay(3000).fadeOut('slow');
            $("#long_term_signout_form .updateErrMess").text(data.error.message);
            // also update the dropdown list
            // get_assets();
            console.log(data.error);

        }
    });
});

// delete room 

function delete_room(id) {

    console.log(id);

    var deleteRoom = $.ajax({
        url: "services/delete_room.php",
        type: "POST",
        data: {
            id: id
        },
        dataType: "json",
        // adding fade effect on window and cursor to clock while the page is laoding
        beforeSend: function () {
            $("body").css("cursor", "wait");
            $("body").css("opacity", "0.5");
        },
    });

    deleteRoom.fail(function (jqXHR, textStatus) {
        alert("Something went Wrong! (deleteRoom)" +
            textStatus);
    });

    deleteRoom.done(function (data) {
        $("body").css("cursor", "default");
        $("body").css("opacity", "");
        // clear all the input field once delete action is done
        $('#room_id').val('');
        $('#room_name').val('');
        $('#room_desc').val('');
        $('#bottom_notes').val('');
        $('#block_size').val('');
        $('#block_start').val('');
        $('#nodb').val('');
        $('#restr_day').val('');
        $('#restr_night').val('');

        $("#rooms_form .updateErrMess").fadeIn('fast').delay(3000).fadeOut('slow');
        $("#rooms_form .updateErrMess").text(data.error.message);
        // also update the dropdown list
        get_rooms();
        console.log(data.error);
    });

}

// update asset reserve
$("#reserve_form").on('submit', function (e) {

    e.preventDefault();

    let validate = false;
    let message = "";

    // before submitting the form, check if each of the form element has been validated, if not, alert a message
    if ($("#assets_reserve_desc").val() == "") {
        validate = true;
        message = `Please enter the asset description 
        `;
        $("#assets_reserve_desc").focus();
    }

    // if one of the fields is empty, alter the message 
    if (validate) {
        alert(message);
    } else {
        // when validation finished, update the asset information

        console.log($("#assets_reserve_desc").val());

        $.ajax({
            type: 'POST',
            url: "services/update_assets_reserve.php",
            data: new FormData(this),
            dataType: "json",
            contentType: false,
            cache: false,
            processData: false,

            beforeSend: function () {
                $('.priBtn').attr("disabled", "disabled");
                $('#reserve_form').css("opacity", "0.5");
            },

            success: function (data) {
                $('#reserve_form').css("opacity", "");
                $(".priBtn").removeAttr("disabled");
                $("#reserve_form .updateErrMess").fadeIn('fast').delay(3000).fadeOut('slow');
                $("#reserve_form .updateErrMess").text(data.error.message);
                // also update the dropdown list
                // get_rooms();
                console.log(data.error);

            }
        });

    }

});

// update room  or insert a new room record
$("#rooms_form").on('submit', function (e) {

    e.preventDefault();

    let validate = false;
    let message = "";

    // before submitting the form, check if each of the form element has been validated, if not, alert a message
    if ($("#room_name").val() == "") {
        validate = true;
        message = `Please enter a room name
        `;
        $("#room_name").focus();
    }

    if ($("#room_desc").val() == "") {
        validate = true;
        message += `Please enter asset room description
        `;
        $("#room_desc").focus();
    }

    if ($("#block_size").val() == "") {
        validate = true;
        message += `Please enter a block size
        `;
        $("#block_size").focus();
    }

    if ($("#block_start").val() == "") {
        validate = true;
        message += `Please enter the block start
        `;
        $("#block_start").focus();
    }

    if ($("#nodb").val() == "") {
        validate = true;
        message += `Please enter number of day blocks
        `;
        $("#nodb").focus();
    }


    // if one of the fields is empty, alter the message 
    if (validate) {
        alert(message);
    } else {
        // when validation finished, update the asset information

        console.log($("#room_id").val());

        $.ajax({
            type: 'POST',
            url: "services/update_room.php",
            data: new FormData(this),
            dataType: "json",
            contentType: false,
            cache: false,
            processData: false,

            beforeSend: function () {
                $('.priBtn').attr("disabled", "disabled");
                $('#rooms_form').css("opacity", "0.5");
            },

            success: function (data) {
                $('#rooms_form').css("opacity", "");
                $(".priBtn").removeAttr("disabled");
                $("#rooms_form .updateErrMess").fadeIn('fast').delay(3000).fadeOut('slow');
                $("#rooms_form .updateErrMess").text(data.error.message);
                // also update the dropdown list
                get_rooms();
                console.log(data.error);

            }
        });

    }

});

// update asset form  or insert a new asset record
$("#assets_form").on('submit', function (e) {

    e.preventDefault();

    let validate = false;
    let message = "";

    // before submitting the form, check if each of the form element has been validated, if not, alert a message
    if ($("#barcode").val() == "") {
        validate = true;
        message = `Please enter a barcode
        `;
        $("#barcode").focus();
    }

    if ($("#asset_desc").val() == "") {
        validate = true;
        message += `Please enter asset description
        `;
        $("#asset_desc").focus();
    }

    if ($("#actions_list").val() == "") {
        validate = true;
        message += `Please select an action
        `;
        $("#actions_list").focus();
    }

    if ($("#categories_list").val() == "") {
        validate = true;
        message += `Please select a category
        `;
        $("#categories_list").focus();
    }

    // if one of the fields is empty, alter the message 
    if (validate) {
        alert(message);
    } else {
        // when validation finished, update the asset information

        console.log($("#asset_id").val());

        $.ajax({
            type: 'POST',
            url: "services/update_asset.php",
            data: new FormData(this),
            dataType: "json",
            contentType: false,
            cache: false,
            processData: false,

            beforeSend: function () {
                $('.priBtn').attr("disabled", "disabled");
                $('#assets_form').css("opacity", "0.5");
            },

            success: function (data) {
                $('#assets_form').css("opacity", "");
                $(".priBtn").removeAttr("disabled");
                $("#assets_form .updateErrMess").fadeIn('fast').delay(3000).fadeOut('slow');
                $("#assets_form .updateErrMess").text(data.error.message);
                // also update the dropdown list
                get_assets();
                console.log(data.error);

            }
        });

    }

});

// update borrower form 
$("#borrowers_form").on('submit', function (e) {
    e.preventDefault();

    $.ajax({
        type: 'POST',
        url: "services/update_borrower.php",
        data: new FormData(this),
        dataType: "json",
        contentType: false,
        cache: false,
        processData: false,

        beforeSend: function () {
            $('.priBtn').attr("disabled", "disabled");
            $('#borrowers_form').css("opacity", "0.5");
        },

        success: function (data) {
            $('#borrowers_form').css("opacity", "");
            $(".priBtn").removeAttr("disabled");
            $("#borrowers_form .updateErrMess").fadeIn('fast').delay(3000).fadeOut('slow');
            $("#borrowers_form .updateErrMess").text(data.error.message);
            get_borrowers();
            console.log(data.error);
        }
    });
});
// update password form
$("#password_form").on('submit', function (e) {
    e.preventDefault();

    $.ajax({
        type: 'POST',
        url: "services/update_password.php",
        data: new FormData(this),
        dataType: "json",
        contentType: false,
        cache: false,
        processData: false,

        beforeSend: function () {
            $('.priBtn').attr("disabled", "disabled");
            $('#password_form').css("opacity", "0.5");
        },

        success: function (data) {
            $('#password_form').css("opacity", "");
            $(".priBtn").removeAttr("disabled");
            $(".updateErrMess").fadeIn('fast').delay(3000).fadeOut('slow');
            $(".updateErrMess").text(data.error.message);
            console.log(data.error);
        }
    });
});

$(document).ready(function () {

    // function to add ability to do date sorting on the tables.
    $.fn.dataTable.moment('ddd D MMM YYYY hh:mm:ss A');


    // fill in the drop down list of the borrowers page, this needs to be displayed once the site is loaded
    get_borrowers();

    get_borrower_status();

    get_program_names();

    // hide and show different subpages based on the tabs licked on
    $(`.subpage`).hide();
    $('.borrowers').show();
    $('.tab').click(function () {
        $(`.subpage`).hide();
        const theID = this.id;
        $(`.subpage.${theID}`).show();
    })

    /****************************************  on the borrowers page  **********************************/

    // when click on the borrower tab, get the latest information to fill in the drop down list
    $('#borrowers').click(function () {
        get_borrowers();

        get_borrower_status();

        get_program_names();
    });

    // when click on the get button, fill in the content of a specific borrower
    $('.getBorrowerBtn').click(function () {
        var id = $(".getBorrowersList").val();
        // console.log(id);
        get_borrower(id);
    })

    // when click on the update button, update the content of a specific borrower
    $('.updateBorrowerBtn').click(function () {
        // const id =  $(".getAssetsList").val();
        // console.log(id);
        // update_asset(id);
        $("#borrowers_form").submit();
    })

    /****************************************  on the password page  **********************************/
    // when click on the password tab
    $('#passwords').click(function () {
        get_borrowers();
    })

    $('.getPasswordBtn').click(function () {
        var id = $(".getBorrowersList2").val();
        // console.log(id);
        get_password(id);
    })

    $('.updatePswBtn').click(function () {
        // const id =  $(".getAssetsList").val();
        // console.log(id);
        // update_asset(id);
        $("#password_form").submit();
    })

    // click the eye icon to hide or show the password
    $('.fa-eye-span').click(function () {
        $(".fa.fa-eye-slash").toggle();
        $(".fas.fa-eye").toggle();
        if ($('#borrower_password').attr('type') === 'password') {
            $('#borrower_password').attr('type', 'text');
        } else {
            $('#borrower_password').attr('type', 'password');
        }

    })

    /****************************************  on the assets page  **********************************/
    // when click on the assets tab, fill in the drop down list of the assets page, fill in the drop down list of actions list, fill in the drop down list of categories list
    $('#assets').click(function () {
        get_assets();

        get_actions();

        get_categories();
    });

    // when click on the get button, fill in the content of a specific asset
    $('.getAssetBtn').click(function () {
        var id = $(".getAssetsList").val();
        // console.log(id);
        get_asset(id);
        // set the value of the update button to update
        $('.updateAssetBtn').attr('value', 'Update');
        // $('#asset_id').val(id);
    })

    // when click on the update button, update the content of a specific asset
    $('.updateAssetBtn').click(function () {
        // const id =  $(".getAssetsList").val();
        // console.log(id);
        // update_asset(id);
        $("#assets_form").submit();
    })

    // when click on add assets button, clear all the fields
    $('.addAssetBtn').click(function () {
        $('.getAssetsList').val('');
        $('#barcode').val('');
        $('#asset_desc').val('');
        $('#notes').val('');
        $('#info').val('');
        $('#serial_number').val('');
        $('#actions_list').val('');
        $('#categories_list').val('');
        // set the value of hidden id into 'insert'
        $('#asset_id').val('-1');
        // set the value of the update button to add
        $('.updateAssetBtn').attr('value', 'Add');
    })

    /****************************************  on the assets reserve page  **********************************/
    // when click on assets reserve tab
    $('#reserve').click(function () {
        // get the category list
        get_categories();
        // get the asset desc group list and display the table 
        get_assets_reserves();
    });

    // when click on the get btn
    $('.getReserveAssetBtn').click(function () {
        var asset_desc = $('.getReserveAssetList').val();
        console.log(asset_desc);
        get_assets_reserve(asset_desc);
    })

    // when click on the update button
    $('#updateAssetReserveBtn').click(function () {
        $("#reserve_form").submit();
    });

    /****************************************  on the groups page  **********************************/
    // when click on groups tab 
    $('#groups').click(function () {
        get_groups();
    });

    /****************************************  on borrower history page  **********************************/

    // when click on the borrower history tab
    $('#borrower_history').click(function () {
        get_borrowers();
    })

    $('.getBorrowerBtn_history').click(function () {
        var id = $(".getBorrowersList3").val();
        // console.log(id);
        get_borrower_history(id);
    })

    /****************************************  on assets history page  **********************************/
    // click on assets history tab

    $('#assets_history').click(function () {
        get_hisAssets();
    });

    $('.getAssetBtn_history').click(function () {
        var barcode = $(".getAssetsList2").val();
        // console.log(id);
        get_asset_history(barcode);
    })

    /****************************************  on long term signout page  **********************************/
    // when click on long term signout page
    $('#long_term_signout').click(function () {
        get_faculty();
        get_assets();
    });

    // insert the long term signout record when click the button
    $('.long_term_signout_Btn').click(function () {
        $("#long_term_signout_form").submit();
    })


    /****************************************  on assets out page  **********************************/
    // click on the logged out tab
    $('#assets_logged_out').click(function () {
        var array = [];
        array = [0, `#assets_logged_out_table`];
        get_assets_logged_out(array);
    });


    /****************************************  on assets overdue page  **********************************/
    // click on the overdue tab
    $('#assets_overdue').click(function () {
        var array = [];
        array = [1, `#assets_overdue_table`];
        get_assets_logged_out(array);
    });

    /****************************************  on assets overdue 10 days page  **********************************/
    // click on the overdue 10 days tab
    $('#assets_overdue_10_days').click(function () {
        var array = [];
        array = [2, `#assets_overdue_10_days_table`];
        get_assets_logged_out(array);
    });


    /****************************************  on print history page  **********************************/
    // when click on the tab, fill in the drop down list
    $('#print_history').click(function () {
        get_borrowers_print_history();
    });

    $('.getPrintHisBtn').click(function () {
        var id = $(".getBorrowersList4").val();
        // console.log(id);
        get_print_history(id);
    })

    /****************************************  on rooms page  **********************************/
    // when click on rooms tab, fill in the dropdown list of rooms
    $('#rooms').click(function () {
        get_rooms();
    });

    // when click on the get button, fill in the content of a specific room
    $('.getRoomBtn').click(function () {
        var id = $("#roomsList").val();
        // $('#room_id').val(id);
        // console.log(id);
        get_room(id);
        // set the value of the update button back to update
        $('.updateRoomBtn').attr('value', 'Update');
        // $('#asset_id').val(id);
    })

    // when click on add room button, clear all the fields
    $('.addRoomBtn').click(function () {
        $('#roomsList').val('');
        $('#room_name').val('');
        $('#room_desc').val('');
        $('#bottom_notes').val('');
        $('#block_size').val('');
        $('#block_start').val('');
        $('#nodb').val('');
        $('#restr_night').val('');
        $('#restr_day').val('');
        // set the value of hidden id into 'insert'
        $('#room_id').val('-1');
        // set the value of the update button to add
        $('.updateRoomBtn').attr('value', 'Add');
    })

    // when click on the update button, update the content of a specific room
    $('.updateRoomBtn').click(function () {
        $("#rooms_form").submit();
    })

    // when click ont he delete button, delete the room 
    $('.deleteRoomBtn').click(function () {
        var id = $("#room_id").val();
        console.log(id);
        // confirm('Are you sure you want to delete the room?');

        if (confirm("Are you really really sure you want to delete the room?") == true) {
            delete_room(id);
        }

    })

    /****************************************  on groups reserve page  **********************************/
    // when click on the tab, fill in the dropdown list
    $('#reserve_groups').click(function () {
        get_groups_reserve();
        $('#groups_reserve_list').focus();
    });

    // when click on the get button, display info in the two tables
    $('.getGroupReserveBtn').click(function () {
        var asset_desc = $('#groups_reserve_list').val();
        console.log(`${asset_desc}`);
        get_group_reserve(`${asset_desc}`);
    });

    // when click on the save button, send the data within the left table to the back end, and then update the dropdown list
    $('.saveGroupReserveBtn').click(function () {
        // var groups_reserve_data = [];
        var groups_id_array = [];
        var groups_name_array = [];
        var asset_desc = $('#groups_reserve_list').val();
        // extracting the data from each row in the left table
        rows = document.querySelectorAll('#reserve_groups_table1 tbody tr');

        rows.forEach(row => {
            // console.log($(row).attr('data-id'));
            groups_id_array.push($(row).attr('data-id'));
            // console.log(row);
            var tds = $(row).children();
            groups_name_array.push(tds[0].innerText);

        });

        // groups_reserve_data.push(groups_id_array);
        // groups_reserve_data.push(groups_name_array);
        // groups_reserve_data.push(asset_desc);

        // console.log(groups_reserve_data);

        update_group_reserve(groups_id_array, groups_name_array, asset_desc);
    });

    /****************************************  on inventory page  **********************************/
    $('#inventory').click(function () {
        $('.barcodeEntry').focus();
        // display the table 
        var option = 0;

        $('#notInvent').change(function () {
            // notInvent is checked but active is unchecked
            if ($('#notInvent').prop("checked") == true && $('#active').prop("checked") == false) {
                option = 1;
                get_inventories(option);

                // notInvent is checked and active is checked
            } else if ($('#notInvent').prop("checked") == true && $('#active').prop("checked") == true) {
                option = 3;
                get_inventories(option);

                // notInvent is unchecked and active is checked
            } else if ($('#notInvent').prop("checked") == false && $('#active').prop("checked") == true) {
                option = 2;
                get_inventories(option);

                // notInvent is unchecked and active is unchecked
            } else if ($('#notInvent').prop("checked") == false && $('#active').prop("checked") == false) {
                option = 0;
                get_inventories(option);
            }
        })

        $('#active').change(function () {
            // notInvent is checked but active is unchecked
            if ($('#notInvent').prop("checked") == true && $('#active').prop("checked") == false) {
                option = 1;
                get_inventories(option);

                // notInvent is checked and active is checked
            } else if ($('#notInvent').prop("checked") == true && $('#active').prop("checked") == true) {
                option = 3;
                get_inventories(option);

                // notInvent is unchecked and active is checked
            } else if ($('#notInvent').prop("checked") == false && $('#active').prop("checked") == true) {
                option = 2;
                get_inventories(option);

                // notInvent is unchecked and active is unchecked
            } else if ($('#notInvent').prop("checked") == false && $('#active').prop("checked") == false) {
                option = 0;
                get_inventories(option);
            }
        })

        // console.log(option);

        get_inventories(option);

    });

    // start new inventory 
    $('.startNewInventBtn').click(function () {

        if (confirm("Are you sure you want to start new inventory?") == true) {
            if (confirm("Are you really really sure you want to start new inventory?") == true) {
                // console.log('start');
                start_new_inventory();
            }
        }
    })

    // veryfying barcode 
    $('.verifyBtn').click(function () {

        // take the value from the text input
        var barcode = $('.barcodeEntry').val();
        // checking the barcode state, pop up an alert message, update the table is scan is successful,  put the focus back to the text input field 
        verify_barcode(barcode);
    })

    /****************************************  on inventory detail page  **********************************/
    $('#inventory_details').click(function () {
        // display the top table 
        get_inventory_details();
    });

    // when clickin the refresh button 
    $('.refreshBtn').click(function(){
        get_inventory_details();
    })

    

});