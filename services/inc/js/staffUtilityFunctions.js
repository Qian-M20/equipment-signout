// This file deals with functions for the three utility panels on the right 
// (signing in, adding printing credit, and removing printing credit)

// =============================================================================
// INITIAL jQUERY
// =============================================================================
$(document).ready(function() {
    $('.utilities_section').slideUp(0); 
    
    // For the 3 utility lists (right-side)
    $('.utilities_section').slideUp(0);   
    
    $(".utilities_section_header").click(function() {
        $(".utilities_section").slideUp(300);
        $(".utilities_section_header").css("background-image", "url(./inc/images/open_arrow.png)");
        
        var thisId = $(this).next(".utilities_section").attr("id");
        
        if (lastUtilId != thisId) {
            $(this).next(".utilities_section").slideDown(300, function() {
                switch (thisId.substr(1,1))
                {
                    case '1':$('#signin_staff_id_scan').focus();break;
                    case '2':$('#p_credit_staff_id_scan').focus();break;
                    case '3':$('#p_debit_staff_id_scan').focus();break;
                }
            });
            
            $(this).css("background-image", "url(./inc/images/close_arrow.png)");
            
            lastUtilId = thisId;
        }
        else
        {
            lastUtilId = "";
        }
    });
    
    // Lock out sections
    setSignIn('reset');
    setCredit('reset');
    setDebit('reset');
    
    // Bind events
    $('#signin_staff_id_scan').keyup(function() {
        if ($(this).val().length == 9)
        {
            setSignIn('enable');
        }
    });  
    
    $('#p_credit_staff_id_scan').keyup(function() {
        if ($(this).val().length == 9)
        {
            setCredit('enable');
        }
    });
    
    $('#p_debit_staff_id_scan').keyup(function() {
        if ($(this).val().length == 9)
        {
            setDebit('enable');
        }
    });
    
});

function setSignIn(mode)
{
    if (mode == 'reset')
    { 
        $('#signin_staff_id_scan').val('');
        $('#signin_staff_id_scan').removeAttr('disabled');
        $('#signin_staff_id_scan').focus();
        $('#signin_asset_id_scan').val('(no access)');
        $('#signin_asset_id_scan, #signin_submit_start, #signin_reset').attr('disabled','disabled');
        $('#signin_student_info_cell').text('(Data pending scan...)');
        $('#signin_date_info_cell').text('(Data pending scan...)');
        $('#signin_submit_end').css("display", "none");
        $('#signin_submit_start').css("display", "inline");
        $('#signin_submit').attr('value', 'Submit');
        $('#signin_asset_list').html('');
        $('#signin_add_item_btn').css("visibility","hidden");
    }
    else 
    {
        $('#signin_staff_id_scan').attr('disabled','disabled');
        $('#signin_asset_id_scan, #signin_submit_start, #signin_reset').removeAttr('disabled');
        $('#signin_asset_id_scan').val('');
        $('#signin_asset_id_scan').focus();
    }
}

function setCredit(mode) 
{
    if (mode == 'reset')
    {
        $('#p_credit_staff_id_scan').removeAttr('disabled');
        $('#p_credit_staff_id_scan').val('');
        $('#p_credit_staff_id_scan').focus();
        $('#p_credit_invoice_id_scan, #p_credit_copies_added, #p_credit_student_id_scan').val('(no access)');
        $('#p_credit_invoice_id_scan, #p_credit_copies_added, #p_credit_student_id_scan, #p_credit_submit, #credit_reset, #p_credit_check_invoice').attr('disabled','disabled');
    }
    else 
    {
        $('#p_credit_staff_id_scan').attr('disabled','disabled');
        $('#p_credit_invoice_id_scan, #p_credit_copies_added, #p_credit_student_id_scan').val('');
        $('#p_credit_invoice_id_scan, #p_credit_copies_added, #p_credit_student_id_scan, #p_credit_submit, #credit_reset, #p_credit_check_invoice').removeAttr('disabled');
        $('#p_credit_student_id_scan').focus();
    }
} 

function setDebit(mode)
{
    if (mode == 'reset')
    {
        $('#p_debit_staff_id_scan').removeAttr('disabled');
        $('#p_debit_staff_id_scan').val('');
        $('#p_debit_staff_id_scan').focus();
        $('#p_debit_copies_printed, #p_debit_student_id_scan').val("(no access)");
        $('#p_debit_copies_printed, #p_debit_student_id_scan, #p_debit_submit,#debit_reset').attr('disabled','disabled');
    }
    else 
    {
        $('#p_debit_staff_id_scan').attr('disabled','disabled');
        $('#p_debit_copies_printed, #p_debit_student_id_scan').val('');
        $('#p_debit_copies_printed, #p_debit_student_id_scan, #p_debit_submit, #debit_reset').removeAttr('disabled');
        $('#p_debit_student_id_scan').focus();
    }
}


// =============================================================================
// FOR SIGNING IN ASSETS
// =============================================================================
function submitSignIn()
{
    if ($('#signin_asset_id_scan').val() != '')
    {
        startRequest("assets_logged_in.php", [$('#signin_asset_id_scan').val()], 12, null);
    }
    else
    {
        displayMessage("Oops. It looks like you forgot scan an item.", "open", "red");
    }
}

function displayAssetsIn(arr)
{
    var content3 = "";
    for (var i = 0; i < arr['bCode'].length; i++)
    {
        content3 += "<li id='a" + i + "_item_name'>" + arr['assetDes'][i] + "</li>";
        content3 += "<li style='list-style-type:none;'><textarea class='note_in' id='a" + i + "_note_in' cols='32' rows='1'></textarea></li>";
    }
    
    $('#signin_student_info_cell').text(arr['info'][1] + " (" + arr['info'][0] + ")");
    $('#signin_date_info_cell').text(arr['info'][2]);
    $('#signin_asset_list').html(content3);
    content3 = "";
    
    $('#signin_submit').click(function(){signInAssets();});   
    $('#signin_submit').attr('value', 'Sign In Assets'); 
    $('#signin_add_item_btn').css("visibility","visible");
    $('#signin_submit_start').css("display", "none");
    $('#signin_submit_end').css("display", "inline");
}

function matchAssetInBarcode(bc)
{
    var matched = false;
    for (var i = 0; i < assetsInArr['bCode'].length; i++)
    {
        if (bc == assetsInArr['bCode'][i])
        {
            // Update array
            assetsInArr['bCode_in'][i] = bc;

            // Visually update list item
            $('#a' + i + '_item_name').css({"list-style-image":"url('./inc/images/check.jpg')", "color":"#009900"});
            
            matched = true;
        }
    }
    
    $('#signin_asset_id_scan').val("");
    $('#signin_asset_id_scan').focus();
    
    if (!matched)
    {
        displayMessage("Item scanned does not match any items in this list.", "open", "red");
    }
}

function signInAssets()
{
    for (var i = 0; i < assetsInArr['bCode_in'].length; i++)
    {
        assetsInArr['note_in'][i] = $('#a' + i + '_note_in').val();
    }
    
    startRequest("assets_logged_in.php", [$('#signin_staff_id_scan').val()], 13, null);
}

// =============================================================================
// FOR CREDITING PRINTS
// =============================================================================
function checkInvoice()
{
    if ($('#p_credit_invoice_id_scan').val() == '' || $('#p_credit_student_id_scan').val() == '')
    {
        displayMessage("Please provide both a student ID and invoice ID.", "open", "red");
    }
    else
    {
        startRequest('addcopies.php', [$('#p_credit_student_id_scan').val(), $('#p_credit_invoice_id_scan').val()], 6, null);
    }
}

function submitPrintCredit()
{
    // Check if necessary elements are present
    if ($('#p_credit_student_id_scan').val() == '' || $('#p_credit_invoice_id_scan').val() == '' || $('#p_credit_student_id_scan').val() == '')
    {
        displayMessage("Please ensure all data is filled out.", "open", "red");
    }
    else
    {
        startRequest('addcopies.php', [$('#p_credit_student_id_scan').val(), $('#p_credit_invoice_id_scan').val(), $('#p_credit_copies_added').val(), $('#p_credit_staff_id_scan').val()], 7, null);
        setCredit('reset');
    }
}


// =============================================================================
// FOR DEBITING PRINTS
// =============================================================================
function submitPrintDebit()
{
    if ($('#p_debit_student_id_scan').val() == '' || $('#p_debit_copies_printed').val() == '')
    {
        displayMessage("Please ensure you have filled out all the fields", "open", "red");
    }
    else
    {
        startRequest("printcopies.php", [$('#p_debit_student_id_scan').val(), $('#p_debit_staff_id_scan').val(), $('#p_debit_copies_printed').val()], 11, null);
    }
}