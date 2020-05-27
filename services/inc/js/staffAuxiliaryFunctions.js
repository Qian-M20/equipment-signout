//alert("Work currently in progress.  Please ignore errors/problems during these work periods.");

// ====================================================================================
// INITIAL jQUERY STUFF
// ====================================================================================
var lastOrdId = "";
var lastUtilId = "";
var lastExpOrdId = "";

$(document).ready(function() {
    $("#user_pass").focus();    
    $('#message_panel').slideUp(0);
});

function applyJQuery(ref, mode)
{
    if (mode == 'orders')
    {
        // For the incoming orders
        $('#o' + ref + '_item_list > li.note').slideUp(0);  
        $('#o' + ref).slideUp(0); 
        
        $('#o' + ref + '_item_list > li.item_name').toggle(function() {
            $(this).next().slideDown(250);
            $(this).next().find('textarea').focus();
        }, function () {
            $(this).next().slideUp(250);
        });
        
        $("#o" + ref + "_header").click(function() {
            $(".order_section").slideUp(300);
            $(".order_section_header").css("background-image", "url(./inc/images/open_arrow.png)");
            
            if (lastOrdId != $(this).next(".order_section").attr("id")) {
                $(this).next(".order_section").slideDown(300, function(){
                    $('#o' + lastOrdId.replace('o', '') + '_staff_id_scan').focus();
                });
                $(this).css("background-image", "url(./inc/images/close_arrow.png)");
                lastOrdId = $(this).next(".order_section").attr("id");
            }
            else
            {
                lastOrdId = "";
            }
        });
    }
    else if (mode == 'expired')
    {
        // For expired orders
        $('#e' + ref).slideUp(0); 
        
        $("#e" + ref + "_header").click(function() {
            $(".expired_section").slideUp(300);
            $(".expired_section_header").css("background-image", "url(./inc/images/open_arrow.png)");
            
            if (lastExpOrdId != $(this).next(".expired_section").attr("id")) {
                $(this).next(".expired_section").slideDown(300);
                $(this).css("background-image", "url(./inc/images/close_arrow.png)");
                lastExpOrdId = $(this).next(".expired_section").attr("id");
            }
            else
            {
                lastExpOrdId = "";
            }
        });
    }
}


// ====================================================================================
// FOR HITTING ENTER DURING SIGN IN (we lost the ENTER capability when we disabled form)
// ====================================================================================
function getKey(e)
{
	if (e.keyCode == 13) 
	{
        startRequest("ad.php", [$("#user_pass").val()], 1, null);
	}
}	

// =================================================================
// FOR SPECIAL MESSAGES (to avoid using ugly alerts)
// =================================================================
var closeMessage;

function displayMessage(msg, mode, col)
{
    if (col == 'red')
    {
        colour = '#990000';
    }
    else if (col == 'green')
    {
        colour = '#009900';
    }
    
    $('#message_panel').css('background-color', colour);
    
    if (mode == 'close')
    {
        clearTimeout(closeMessage);
        $('#message_panel').slideUp(300, function() {
            $('#message_panel_text').html("");
        });
    }
    else
    {
        $('#message_panel_text').html(msg);
        $('#message_panel').slideDown(300);
        closeMessage = setTimeout("displayMessage('','close','')",10000);
    }
}

// =================================================================
// TIMERS FOR GETTING UPDATED ORDER LISTS
// =================================================================
var ordersTimer;

function beginOrdersTimer()
{
    clearTimeout(ordersTimer);
    if (!firstLoad)
    {
        startRequest("assets_bin.php", "", 2, null);
        startRequest("assets_bin.php", "", 9, null);
    }
    ordersTimer = setTimeout("beginOrdersTimer()", 15000);
}

// ====================================================================================
// UPCOMING ORDER LIST-RELATED FUNCTIONS
// ====================================================================================
var oldOrdersArr = new Array();
var newOrdersArr = new Array();
var newExpiredOrdersArr = new Array();
var oldExpiredOrdersArr = new Array();
var totalOrders = 0;

// Create objects
function Order(n, i, b, ts, sd)
{
	// Properties
	this.student_name = n;
	this.student_id = i;
	this.bin = b;
	this.time_stamp = ts; // 10-digit number
	this.start_date = sd; // legible format
	this.items = new Array();
	this.barcodes = new Array();
	this.notes = new Array();
	this.return_time = "";
	
	// Methods
}

var firstLoad = true;
function displayOrderList()
{
    // Determine if oldOrdersArr is empty (length = 0) or full of blanks (["","",""...])
    var isEmpty = true;
    for (var i = 0; i < oldOrdersArr.length; i++)
    {
        if (oldOrdersArr[i] != '' && oldOrdersArr[i] != null)
        {
            isEmpty = false;
        }
    }
    
    if (isEmpty)
    {
        var content = "";
        noneMsg = ["Whaddya looking in here for?! I said there aren't any new orders! Go grab a coffee or somethin', sheesh.", "What, did you think, that I was <em>lying</em> to you? Look, we've had some rough times in the past, but I'd never lie to you. There are NO NEW ORDERS.", "Not convinced, eh? Well don't YOU feel silly now. Like I said, there are no new orders.", "Hey, I don't tell you how to do YOUR job, do I? I told ya, there ain't no new orders.", "Look, I appreciate your work ethic, but seriously, there are NO new orders. Come back later, kid."];
        var x = Math.floor(Math.random() * 5);
        
        content += "<div class='order_section_header' id='o0_header'>There are no new orders.</div>";
        content += "<div class='order_section' id='o0'>" + noneMsg[x] + "</div>";
        
        $('#order').html(content);
        applyJQuery(0, 'orders');
    }
    else
    {
        for (var i = 0; i < oldOrdersArr.length; i++)
        {
            // first remove "No New Orders" Div if it was present
            if ($('#o0_header').text() == "There are no new orders.")
            {
                $('#o0_header').remove();
                $('#o0').remove();
            }
            
            // if order exists at this index
            if (oldOrdersArr[i] != null && oldOrdersArr[i] != "")
            {
                // if this entry is not already represented in the DOM
                if ($('#o' + i).length == 0) 
                {
                    var content = "";
                    // Initial form sections
                        content += "<div class='order_section_header' id='o" + i + "_header'><span style='display:block;float:left;'>" + oldOrdersArr[i].student_name + "</span><span style='display:block;float:right;margin-right:35px;' id='o"+i+"_header_bin'>Bin: " + oldOrdersArr[i].bin + "</span></div>";
                        content += "<div class='order_section' id='o" + i + "'><table id='o" + i + "_table'>";
                        content += "<tr><td class='title_cell'>Student ID:</td><td id='o" + i + "_student_id'>" + oldOrdersArr[i].student_id + "</td></tr>";
                        content += "<tr><td class='title_cell'>Time:</td><td>" + oldOrdersArr[i].start_date + "</td></tr";
                        content += "<tr><td class='title_cell'>Staff ID:</td><td><input type='text' id='o" + i + "_staff_id_scan' value='' onkeyup='checkStaffId(this.value, " + i + ");' maxlength='9' size='10' /></td></tr>";
                        content += "<tr><td class='title_cell'>Select Bin:</td><td><select id='o" + i + "_bin' onchange='setBin(" + i + ");' disabled='disabled'>";
                    
                    // Insert bin options
                        content += "<option value='0'> -- </option>";
                        for (var j = 1; j < 16; j++)
                        {
                            var sel = "";
                            if (j == oldOrdersArr.bin)
                            {
                                sel = " selected='selected'";
                            }
                            content += "<option value='"+ j + "'" + sel + ">" + j + "</option>";
                        }
                        content += "</select></tr>";
                        
                        content += "<tr><td class='title_cell'>Scan Item:</td><td><input type='text' maxlength='20' id='o" + i + "_barcode_scan' value='(no access)' onkeyup='checkBarcodeEnter(event,this.value," + i + ");' size='12' disabled='disabled' /> <input type='button' id='o" + i + "_barcode_go' onclick='checkBarcodeClick($(&apos;#o" + i + "_barcode_scan&apos;).val()," + i + ");' value='Go' disabled='disabled' /></td></tr>";
                    
                    // Print out list of items
                        content += "<tr><td colspan='2'><ul class='item_list' id='o" + i + "_item_list'>";
                        for (var k = 0; k < oldOrdersArr[i].items.length; k++)
                        {
                            content += "<li class='item_name' title='Click to add a note.'>" + oldOrdersArr[i].items[k] + "</li>";
                            content += "<li style='list-style-type:none;' id='o" + i + "" + k + "_note' class='note'><textarea cols='32'></textarea></li>";
                        }
                        content += "</ul></td></tr>";
                    
                    // Remaining order form sections
                        content += "<tr><td class='title_cell'># Scanned:</td><td><span id='o" + i + "_total_assigned'>0</span> of <span id='o" + i + "_total_items'>" + oldOrdersArr[i].items.length + "</span></td></tr>";
                        content += "<tr><td class='title_cell'>Partial:</td><td><label><input style='margin-left:0;padding-left:0;' type='checkbox' id='o" + i + "_bypass' value=''/> Allow incomplete order</label>";
                        content += "<tr><td class='title_cell'>Return:</td><td><select class='return_time_select' id='o" + i + "_return' onchange='setReturnTime(" + i + ");' disabled='disabled'>";
                        
                    // Get Return Times
                        // needs to be inserted later (because of the delay between running this proceedural code and the ajax call)
                    
                    // Wrap up
                        content += "</select></td></tr><tr><td class='title_cell'>Student ID:</td><td><input type='text' id='o" + i + "_student_id_scan' onkeyup='checkStudentId(this.value, " + i + ");' value='(no access)' maxlength='9' size='10' disabled='disabled' /></td></tr>";
                        content += "<tr><td>\</td><td><input id='o" + i + "_checkout' type='button' value='Check Out' onclick='checkOut(" + i + ");' disabled='disabled' /> <input id='o" + i + "_reset' type='button' value='Reset' onclick='reset(" + i + ");' disabled='disabled' /></td></tr></table>";
                        content += "</div>";
                    
                    // Add to DOM, apply jQuery, insert return times appropriate to strikes
                    $('#order').append(content);
                    applyJQuery(i, 'orders');
                    startRequest('return_times.php', [oldOrdersArr[i].student_id], 3, i);
                }
            }    
        }
    }

    if (firstLoad)
    {
        $('#content').animate({'opacity':'1', 'filter':'alpha(opacity=100)'}, 850, 'swing');
        $('#signin_panel').slideUp(850);
        beginOrdersTimer();
    }
    
    firstLoad = false;
}


function displayExpiredOrderList()
{
    for (var i = 0; i < oldExpiredOrdersArr.length; i++)
    {
        // if order exists at this index
        if (oldExpiredOrdersArr[i] != null && oldExpiredOrdersArr[i] != "")
        {
            // if this entry is not already represented in the DOM
            if ($('#e' + i).length == 0) 
            {
                var content2 = "";
                content2 += "<div class='expired_section_header' id='e" + i + "_header'><span style='display:block;float:left;'>Expired Order</span><span class='expired_bin' style='display:block;float:right;margin-right:35px;'>Bin: " + oldExpiredOrdersArr[i].bin + "</span></div>";
                content2 += "<div class='expired_section' id='e" + i + "'><table>";
                content2 += "<tr><td class='title_cell'>Student ID:</td><td>" + oldExpiredOrdersArr[i].student_id + "</td></tr>";
                content2 += "<tr><td class='title_cell'>Student Name:</td><td>" + oldExpiredOrdersArr[i].student_name + "</td></tr>";
                content2 += "<tr><td class='title_cell'>Time:</td><td>" + oldExpiredOrdersArr[i].start_date + "</td></tr>";
                content2 += "<tr><td class='title_cell'>Items:</td><td><ul class='item_list' id='e" + i + "_item_list'>";

                for (var k = 0; k < oldExpiredOrdersArr[i].items.length; k++)
                {
                    content2 += "<li class='item_name' title='Click to add a note.'>" + oldExpiredOrdersArr[i].items[k] + "</li>";
                }

                content2 += "</ul></td></tr>";
                content2 += "<tr><td colspan='2'>Please ensure that this bin is clear of the above-listed items.</td></tr>";
                content2 += "<tr><td colspan='2'><input type='button' value='Confirm Bin as Cleared' onclick='confirmBinCleared(" + i + ");' /></td></tr></table></div>";
                
                $('#utilities').append(content2);
                applyJQuery(i, 'expired');
            }
        }
    }
}

// ====================================================================================
// FORM INPUT CHECKS AND FUNCTIONS
// ====================================================================================
function checkStaffId(txt, divRef)
{
    if (txt.length == 9) // Check goes here (ajax call)
    {
        // Check that ID is valid and activate form
        $('#o' + divRef + '_bin').removeAttr('disabled');
        $('#o' + divRef + '_barcode_scan').removeAttr('disabled');
        $('#o' + divRef + '_barcode_scan').val("");
        $('#o' + divRef + '_barcode_go').removeAttr('disabled');
        $('#o' + divRef + '_return').removeAttr('disabled');
        $('#o' + divRef + '_student_id_scan').removeAttr('disabled');
        $('#o' + divRef + '_student_id_scan').val("");
        $('#o' + divRef + '_checkout').removeAttr('disabled');
        $('#o' + divRef + '_reset').removeAttr('disabled');
        
        // Disable staff id
        $('#o' + divRef + '_staff_id_scan').attr('disabled','disabled');
    }  
}

function checkBarcodeEnter(e, txt, divRef)
{
	// To avoid senging forms (and refreshing the page), enter was initially disabled (via onsubmit=return false)
	if (e.keyCode == 13) 
	{
        // This starts a chain of events that matches the scanned barcode with items in the list.
        startRequest('asset_name.php', [txt], 4, divRef);
	}
}

function checkBarcodeClick(txt, divRef)
{
    // This starts a chain of events that matches the scanned barcode with items in the list.
    startRequest('asset_name.php', [txt], 4, divRef);
}

function checkStudentId(txt, divRef)
{
    // Placeholder should you wish to have the scan turn into an automatic CHECKOUT click.
}

function setBin(ref)
{
    var allowed = true;
    for (var i = 0; i < oldOrdersArr.length; i++)
    {
        if (oldOrdersArr[i].bin == $('#o' + ref + '_bin').val())
        {
            allowed = false;
        }
        
        $('.expired_bin').each(function(){
            if ($('#o' + ref + '_bin').val() == $(this).text().substr(5, ($(this).text().length - 1)))
            {
                allowed = false;
            }
        });
    }
    
    if (allowed)
    {
        // Adds the bin number into the oldOrdersArr for this order and into the header.
        oldOrdersArr[ref].bin = $('#o' + ref + '_bin').val();
        $('#o' + ref + '_header_bin').text("Bin: " + $('#o' + ref + '_bin').val());
        
        // update DB 
        // Note, this isn't completely necessary, per se, but helps in clearing expired orders at a later time
        startRequest('assets_bin.php', [oldOrdersArr[ref].student_id, oldOrdersArr[ref].time_stamp, oldOrdersArr[ref].bin], 8, null);
    }
    else
    {
        displayMessage("There is another bin with this number.", "open", "red");
        $('#o' + ref + '_bin > option').removeAttr('selected');
    }
    
    allowed = true;
}

function setReturnTime(ref)
{
    // Adds the return time into the oldOrdersArr for this order
    oldOrdersArr[ref].return_time = $('#o' + ref + '_return').val();
}

function confirmBinCleared(ref) 
{
    startRequest("assets_bin_check.php", [oldExpiredOrdersArr[ref].student_id, oldExpiredOrdersArr[ref].time_stamp], 10, ref);
}


// ====================================================================================
// CHECKOUT-RELATED FUNCTIONS
// ====================================================================================
function reset(ref) {
    // Reset info in oldOrdersArr for this order.
    oldOrdersArr[ref].barcodes = "";
    oldOrdersArr[ref].barcodes = new Array();
    oldOrdersArr[ref].notes = "";
    oldOrdersArr[ref].notes = new Array();
    oldOrdersArr[ref].bin = 0;
    oldOrdersArr[ref].return_time = "";
    
    // Reset info in div and disable fields
    $('#o' + ref + '_bin').attr('disabled','disabled');
    $('#o' + ref + '_barcode_scan').attr('disabled','disabled');
    $('#o' + ref + '_barcode_scan').val("(no access)");
    $('#o' + ref + '_barcode_go').attr('disabled','disabled');
    $('#o' + ref + '_return').attr('disabled','disabled');
    $('#o' + ref + '_student_id_scan').attr('disabled','disabled');
    $('#o' + ref + '_student_id_scan').val("(no access)");
    $('#o' + ref + '_checkout').attr('disabled','disabled');
    $('#o' + ref + '_reset').attr('disabled','disabled');
    $('#o' + ref + '_item_list > li:nth-child(even) > textarea').val("");
    
    // Enable staff id field
    $('#o' + ref + '_staff_id_scan').removeAttr('disabled');
    $('#o' + ref + '_staff_id_scan').val("");
    $('#o' + ref + '_staff_id_scan').focus();
    
    // Reset styling changes that may have occured
    $('#o' + ref + '_header_bin').text("Bin: 0");
    $('#o' + ref + '_item_list > li').css({'list-style-image':'', 'list-style-type':'disc', 'color':'#ffffff'});
    $('.note').css('list-style-type','none');
    $('#o' + ref + '_return > option').removeAttr('selected');
    $('#o' + ref + '_bin > option').removeAttr('selected');
    $('#o' + ref + '_bypass').removeAttr('checked')
    $('#o' + ref + '_total_assigned').text(oldOrdersArr[ref].barcodes.length);
    
    // Optional slide up of any open textareas, BUT then this throws off the jQuery toggle Clicks by 1 click.
    // $('#o' + ref + '_item_list > li:nth-child(even)').slideUp(250);
}

function checkOut(ref)
{
    // No need to check for Staff ID because you can't get to this point without having previously check that elsewhere.

    // Check bin
    var bin0Error = false;
    var doubledBinError = false;
    var assetsError = false;
    var timeError = false
    var studentError = false;
    var bypass = $('#o' + ref + '_bypass').attr('checked');
    
    if (oldOrdersArr[ref].bin == 0)
    {
       bin0Error = true;
    }
    
    // Check if bin number has already been selected
    for (var i = 0; i <oldOrdersArr.length; i++)
    {
        if (oldOrdersArr[ref].bin == oldOrdersArr[i].bin  && i != ref)
        {
            doubledBinError = true;
        }
        
        $('.expired_bin').each(function(){
            if (oldOrdersArr[ref].bin == $(this).text().substr(5, ($(this).text().length - 1)))
            {
                doubledBinError = true;
            }
        });
    }
    
    // Check if all assets are assigned
    for (var i = 0; i <oldOrdersArr[ref].items.length; i++)
    {
        if (oldOrdersArr[ref].barcodes[i] == "" || oldOrdersArr[ref].barcodes[i] == null)
        {
            assetsError = true;
        }
    }
    
    // Check that a return time has been selected
    if (oldOrdersArr[ref].return_time == 0)
    {
       timeError = true;
    }
    
    // Check that the scanned student ID matches the one for this order
    if ($('#o' + ref + '_student_id_scan').val() != oldOrdersArr[ref].student_id)
    {
        studentError = true;
    }
    
    // Perform notifications
    if (bin0Error)
    {
        displayMessage("Please select a bin number.", "open", "red");
    }
    else if (doubledBinError)
    {
        displayMessage("There is another order using this bin.", "open", "red");
    }
    else if (assetsError && !bypass)
    {
        displayMessage("There are items that have not been scanned. Check \"Partial \" if you wish to proceed anyway.", "open", "red");
    }
    else if(timeError)
    {
        displayMessage("Please select a return time.", "open", "red");
    }
    else if (studentError)
    {
        displayMessage("The student number does not match the number for this order.", "open", "red");
    }
    else 
    {
        // Save notes
        for (var i = 0; i < oldOrdersArr[ref].items.length; i++)
        {
            if (oldOrdersArr[ref].barcodes[i] != null && oldOrdersArr[ref].barcodes[i] != '')
            {
                oldOrdersArr[ref].notes[i] = $('#o' + ref + '_item_list>li:nth-child(' + ((i*2)+2) + ') > textarea').val(); 
            }
        }
        
        // Send info
        startRequest('assets_logged_out.php', [$('#o' + ref + '_staff_id_scan').val()], 5, ref);
    }
}

function findBarcodesLength(ref)
{
    var totalBarcodes = 0;
    for (var i = 0; i < oldOrdersArr[ref].items.length; i++)
    {
        if (oldOrdersArr[ref].barcodes[i] != null && oldOrdersArr[ref].barcodes[i] != '')
        {
            totalBarcodes++;
        }
    }
    return totalBarcodes;
}