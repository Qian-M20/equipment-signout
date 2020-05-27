// =================================================================
// 1. TAILORED INPUTS
// =================================================================
var postData = "";

function startRequest(fName, postDataArr, choice, divRef) {
	postData = "";
	switch (choice) {
		case 1:	// to send log in info
			postData = "student_id=100000001&password=" + encodeURIComponent(postDataArr[0]);
			getRequest(fName, choice, null);
			break;
		case 2: // to populate upcoming orders list
		    postData = "full_list=true";
			getRequest(fName, choice, null);
			break;
        case 3: // to get Return Times
	        postData = "student_id=" + encodeURIComponent(postDataArr[0]);
	        getRequest(fName, choice, divRef);
	        break;
	    case 4: // to match scanned item with items in list
	        postData = "barcode=" + encodeURIComponent(postDataArr[0]);
	        getRequest(fName, choice, divRef);
	        break;
	    case 5: // to check out the assets (student picking up)
	        // Must create post data based on filled in .barcodes array, not .items array (because the order may have been only partially filled)
	        // Note the left over assets of a partially filled order will still appear in the upcoming orders on next refresh! Must eliminate
	        postData = "student_id=" + encodeURIComponent(oldOrdersArr[divRef].student_id) + "&staff_id=" + encodeURIComponent(postDataArr[0]) + "&bin=" + encodeURIComponent(oldOrdersArr[divRef].bin) + "&return_date=" + encodeURIComponent(oldOrdersArr[divRef].return_time) + "&order_date=" + encodeURIComponent(oldOrdersArr[divRef].time_stamp);
	        for (var i = 0; i < oldOrdersArr[divRef].barcodes.length; i++)
	        {
	            if (oldOrdersArr[divRef].barcodes[i] != '' && oldOrdersArr[divRef].barcodes[i] != null)
	            {
	                postData += "&barcode[" + i + "]=" + encodeURIComponent(oldOrdersArr[divRef].barcodes[i]) + "&notes[" + i + "]=" + encodeURIComponent(oldOrdersArr[divRef].notes[i]);
	            }
	        }
	        //alert(postData);
	        getRequest(fName, choice, divRef);
	        break;
	    case 6: // To check if invoice id has already been used.
	        postData = "student_id=" + encodeURIComponent(postDataArr[0]) + "&invoice_id=" + encodeURIComponent(postDataArr[1]);
	        getRequest(fName, choice, null);
	        break;
	    case 7: // To submit credit for printing.
	        postData = "student_id=" + encodeURIComponent(postDataArr[0]) + "&invoice_id=" + encodeURIComponent(postDataArr[1]) + "&copies_added=" + encodeURIComponent(postDataArr[2]) + "&staff_id=" + encodeURIComponent(postDataArr[3]);
	        getRequest(fName, choice, null);
	        break;
	    case 8: // Updating just the bin number on an order
	        postData = "student_id=" + encodeURIComponent(postDataArr[0]) + "&time_stamp=" + encodeURIComponent(postDataArr[1]) + "&bin=" + encodeURIComponent(postDataArr[2]);
	        getRequest(fName, choice, null);
	        break;
	    case 9: // Obtaining a list of expired orders
	        postData = "state=checkExpired";
	        getRequest(fName, choice, null);
	        break;
	    case 10: // Confirming that an expired bin has been cleared
	        postData = "state=2&student_id=" + encodeURIComponent(postDataArr[0]) + "&start_date=" + encodeURIComponent(postDataArr[1]);
	        getRequest(fName, choice, divRef);
	        break;
        case 11: // To submit debit for printing.
            postData = "student_id=" + encodeURIComponent(postDataArr[0]) + "&staff_id=" + encodeURIComponent(postDataArr[1]) + "&copies_printed=" + encodeURIComponent(postDataArr[2]);
            getRequest(fName, choice, null);
            break;
        case 12: // To get list of signed out assets associated wtih this one asset
            postData = "barcode[0]=" + encodeURIComponent(postDataArr[0]);
            getRequest(fName, choice, null);
            break;             
        case 13: // To sign in an/many asset(s)
            postData = "staff_id=" + encodeURIComponent(postDataArr[0]);
            for (var i = 0; i < assetsInArr['bCode_in'].length; i++)
	        {
	            if (assetsInArr['bCode_in'][i] != '' && assetsInArr['bCode_in'][i] != null)
	            {
	                postData += "&barcode[" + i + "]=" + encodeURIComponent(assetsInArr['bCode_in'][i]) + "&notes[" + i + "]=" + encodeURIComponent(assetsInArr['note_in'][i]);
	            }
	        }
	        //alert(postData);
	        getRequest(fName, choice, null);
            break;
    }
}

// =================================================================
// 2. MAKE REQUEST
// =================================================================
function getRequest(theFile, choice, divRef)
{
	var oXmlHttp = zXmlHttp.createRequest();
	oXmlHttp.open("post",theFile,true);
	oXmlHttp.onreadystatechange = function ()
	{
		if (oXmlHttp.readyState == 4)
		{
			if (oXmlHttp.status == 200)
			{
				var response = oXmlHttp.responseXML;
				
				switch (choice)
				{
					case 1:
						checkLogin(response);	break;
					case 2:
						populateOrdersArray(response); break;
					case 3:
					    returnTimes(response, divRef); break;
					case 4:
					    matchAssetToList(response, divRef); break;
					case 5:
					    checkCheckOut(response,divRef); break;
					case 6:
					    checkInvoiceStatus(response); break;
					case 7:
					    checkSubmitCredit(response); break;
					case 8:
					    checkBinUpdate(response); break;
					case 9:
					    displayExpiredBins(response); break;
					case 10:
					    confirmClearBin(response, divRef); break;
					case 11:
					    checkSubmitDebit(response); break;
					case 12:
					    getSignInList(response); break;
					case 13:
					    checkSignIn(response); break;
					default:
					    break;    
				}
			} 
			else
			{
				$('#content').html("<div class='content_insert' id='ajax_error'><h1>Sorry, Unexpected Error</h1><p>An error occurred: " + oXmlHttp.statusText + " " + oXmlHttp.status + "<br />Please log out and try again later.</p></div>"); //statusText is not always accurate
			}
		}   
	}
	
	//alert(postData);
	var dateStamp = new Date();
	oXmlHttp.setRequestHeader("lastCached", dateStamp.getTime() );
	oXmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	oXmlHttp.send(postData);
}

// =================================================================
// 3. TAILORED OUTPUTS
// =================================================================
// 1
function checkLogin(response) 
{
	if (response.getElementsByTagName('error').length != 0)
	{
		// Login failed
		$('#message').text("Incorrect password. Please try again");
	}
	else
	{
		// Login successful
		// Get the info that we know MUST be in the database//student_id = response.getElementsByTagName('student_id')[0].childNodes.item(0).nodeValue;
		first_name = response.getElementsByTagName('first_name')[0].childNodes.item(0).nodeValue;
		last_name = response.getElementsByTagName('last_name')[0].childNodes.item(0).nodeValue;
		
        // User does NOT need to sign a new agreement
        $('#message').text("Loading Data. Please wait.");
        $('#signout > h2').text(first_name + " " + last_name);
        
        // start orders retrieval
        startRequest("assets_bin.php", "", 2, null);
        startRequest("assets_bin.php", "", 9, null);
        $('#signin_panel').slideUp(850);   
	}
}

// 2
function populateOrdersArray(response)
{
    var lastTimeStamp = "";
    
    // Note: This approach is based on the returned data not being grouped
    // Therefore: there are as many timestamps as there are student_ids as there are assets
    
    // Get all orders
    var XMLData = response.getElementsByTagName('assets_description');
    
    for (var i = 0; i < XMLData.length; i++)
    {
        var startDate = response.getElementsByTagName('start_date')[i].childNodes.item(0).nodeValue;
        var timeStamp = response.getElementsByTagName('time_stamp')[i].childNodes.item(0).nodeValue;
        var stuId = response.getElementsByTagName('student_id')[i].childNodes.item(0).nodeValue;
        var stuName = response.getElementsByTagName('student_name')[i].childNodes.item(0).nodeValue;
        var assetDes = response.getElementsByTagName('assets_description')[i].childNodes.item(0).nodeValue;
        var binNum = response.getElementsByTagName('bin')[i].childNodes.item(0).nodeValue;
        
        if (timeStamp != lastTimeStamp)
        {            
            // new Order(name, student_id, bin#, time_stamp, start_date)
            newOrdersArr[newOrdersArr.length] = new Order(stuName, stuId, binNum, timeStamp, startDate);
            totalOrders++;   
            lastTimeStamp = timeStamp;
        }
        newOrdersArr[newOrdersArr.length - 1].items.push(assetDes);
    }
    
    // Append ONLY NEW entries into oldOrdersArray 
    for (var i = 0; i < newOrdersArr.length; i++)
    {
        var alreadyExists = false;
        
        for (var j = 0; j < oldOrdersArr.length; j++)
        {
            if (oldOrdersArr[j] != null && oldOrdersArr[j] != '')
            {
                // basically we identify an order by the composite primary key of STUDENT ID and TIMESTAMP
                if ((newOrdersArr[i].student_id == oldOrdersArr[j].student_id) && (newOrdersArr[i].time_stamp == oldOrdersArr[j].time_stamp))
                {
                    alreadyExists = true;
                }
            }
        }
        
        if (!alreadyExists)
        {
            oldOrdersArr.push(newOrdersArr[i]);
        }
    }
    
    // Now remove OLD ENTRIES from the oldOrdersArr that aren't in the newOrdersArr (these have expired since signing in and must be removed)
    // Yes, we COULD just flag these as expired, BUT, during a fresh sign-in, these would never have entered the oldOrdersArr, thus
    // the expired orders would fall through the cracks and so we have a whole other section devoted to expired orders... just saying, yo.
    for (var i = 0; i < oldOrdersArr.length; i++)
    {
        var expired = true;
        
        for (var j = 0; j < newOrdersArr.length; j++)
        {
            if (oldOrdersArr[i] != null && oldOrdersArr[i] != '')
            {
                // basically we identify an order by the composite primary key of STUDENT ID and TIMESTAMP
                if ((oldOrdersArr[i].student_id == newOrdersArr[j].student_id) && (oldOrdersArr[i].time_stamp == newOrdersArr[j].time_stamp))
                {
                    expired = false;
                }
            }
        }
        
        if (expired)
        {
            oldOrdersArr[i] = '';
            $('#o' + i).remove();
            $('#o' + i + '_header').remove();
        }
    }
   
    
    // Clear newOrdersArray to free memory
    newOrdersArr = "";
    newOrdersArr = new Array();
    
    // Append new entries from the newOrdersArray
    displayOrderList();
}

// 3
function returnTimes(response, ref)
{
    if (response.getElementsByTagName('error').length != 0)
	{
		displayMessage("An error has occured (cannot acquire return times). Please refresh the page and try again.", "open", "red");
	}
	else
	{
	    $('#o' + ref + '_return').append("<option value='0'>-- select a return time --</option>");
	    for (var i = 0; i < response.getElementsByTagName('return_time').length; i++)
        {
            $('#o' + ref + '_return').append("<option value='" + response.getElementsByTagName('time')[i].childNodes.item(0).nodeValue + "'>" + response.getElementsByTagName('display_time')[i].childNodes.item(0).nodeValue + "</option>");
        }
    }
}

// 4
function matchAssetToList(response, divRef)
{
    if (response.getElementsByTagName('error').length != 0)
	{
		// Could not find item with that barcode
		displayMessage("Error: Invalid barcode (or server could not perform search at this time).", "open", "red");
	}
	else
	{
	     var assetName = response.getElementsByTagName('asset_description')[0].childNodes.item(0).nodeValue;
	     
	     var found = 0;
	     for (var i = 0; i < $('#o' + divRef + '_item_list > li').length; i++)
	     {  
	        var listText = $('#o' + divRef + '_item_list > li:nth-child('+ (i+1) +')').text();

	        if (assetName == listText)
	        {
	            found = 1;
	            // highlight on screen
	            $('#o' + divRef + '_item_list > li:nth-child('+ (i+1) +')').css('color','#009900');
	            $('#o' + divRef + '_item_list > li:nth-child('+ (i+1) +')').css("list-style-image","url('./inc/images/check.jpg')");
	            
	            // update odersArr barcode value (use %2 because every second li is actually a textarea for notes
	            oldOrdersArr[divRef].barcodes[Math.ceil(i/2)] = $('#o' + divRef + '_barcode_scan').val();
	        
                // clear scan field and focus (make ready for next item).
                $('#o' + divRef + '_barcode_scan').val('');
                $('#o' + divRef + '_barcode_scan').focus();
                
                // update # of #
                $('#o' + divRef + '_total_assigned').text(findBarcodesLength(divRef));
	        }
	     }
	     if (found == 0)
         {
             displayMessage("Item scanned does not match any items in this list.", "open", "red");
         }
     }

}

// 5
function checkCheckOut(response, divRef)
{
    if (response.getElementsByTagName('error').length != 0)
	{
		// Could not find item with that barcode
		displayMessage("Could not checkout this order.  Please try again.", "open", "red");
	}
	else 
	{
        displayMessage("The order has been logged out.", "open", "green");
        
        // Delete oldOrders entry and remove the div from the DOM
        $('#o' + divRef + '_header').remove();
        $('#o' + divRef).remove();
        oldOrdersArr[divRef] = null;
    }
    
    displayOrderList()
} 

// 6
function checkInvoiceStatus(response)
{
    if (response.getElementsByTagName('error_message').length != 0)
	{
		var msg = response.getElementsByTagName('error_message')[0].childNodes.item(0).nodeValue;
		var col = "red";
		if (msg.indexOf('already') == -1)
		{
		    col = "green";
		}
		
		displayMessage(msg, "open", col);
	}
}

// 7
function checkSubmitCredit(response)
{
    if (response.getElementsByTagName('error_message').length != 0)
	{
		var msg = response.getElementsByTagName('error_message')[0].childNodes.item(0).nodeValue;
		var col = "red";
		if (msg.indexOf('successfully') != -1)
		{
		    col = "green";
		}
		
		displayMessage(msg, "open", col);
	}
}

// 8
function checkBinUpdate(response)
{
    if (response.getElementsByTagName('error').length != 0)
	{
		displayMessage("Could not assign bin number to database.  Please try again.", "open", "red");
    }
    else
    {
        displayMessage("Bin number successfully stored in database.", "open", "green");
    }
}

// 9
function displayExpiredBins(response)
{
    if (response.getElementsByTagName('error').length == 0)
	{
		var lastTimeStamp2 = "";
		
		for (var i = 0; i < response.getElementsByTagName('assets_description').length; i++)
		{
	        var startDate2 = response.getElementsByTagName('start_date')[i].childNodes.item(0).nodeValue;
            var timeStamp2 = response.getElementsByTagName('time_stamp')[i].childNodes.item(0).nodeValue;
            var stuId2 = response.getElementsByTagName('student_id')[i].childNodes.item(0).nodeValue;
            var stuName2 = response.getElementsByTagName('student_name')[i].childNodes.item(0).nodeValue;
            var assetDes2 = response.getElementsByTagName('assets_description')[i].childNodes.item(0).nodeValue;
            var binNum2 = response.getElementsByTagName('bin')[i].childNodes.item(0).nodeValue;

            if (timeStamp2 != lastTimeStamp2)
            {            
                // new Order(name, student_id, bin#, time_stamp, start_date)
                newExpiredOrdersArr[newExpiredOrdersArr.length] = new Order(stuName2, stuId2, binNum2, timeStamp2, startDate2);   
                lastTimeStamp2 = timeStamp2;
            }
            newExpiredOrdersArr[newExpiredOrdersArr.length - 1].items.push(assetDes2);
        }
        
        // Append new entries into oldExpOrdersArray
        for (var i = 0; i < newExpiredOrdersArr.length; i++)
        {
            var alreadyExists2 = false;
            
            for (var j = 0; j < oldExpiredOrdersArr.length; j++)
            {
                if (oldExpiredOrdersArr[j] != null && oldExpiredOrdersArr[j] != '')
                {
                    // basically we identify an order by the composite primary key of STUDENT ID and TIMESTAMP
                    if ((newExpiredOrdersArr[i].student_id == oldExpiredOrdersArr[j].student_id) && (newExpiredOrdersArr[i].time_stamp == oldExpiredOrdersArr[j].time_stamp))
                    {
                        alreadyExists2 = true;
                    }
                }
            }
            
            if (!alreadyExists2)
            {
                oldExpiredOrdersArr.push(newExpiredOrdersArr[i]);
            }
        }
        
        // Clear newOrdersArray to free memory
        newExpiredOrdersArr = "";
        newExpiredOrdersArr = new Array();
        
        // Append new entries from the newOrdersArray
        displayExpiredOrderList();
    }
}

// 10
function confirmClearBin(response, divRef)
{
    if (response.getElementsByTagName('message').length > 0)
	{
        displayMessage("Bin has been confirmed as 'Cleared.'", "open", "green");
        $('#e' + divRef + '_header').remove();
        $('#e' + divRef).remove();
        
        oldExpiredOrdersArr[divRef] = "";
    }
}

// 11
function checkSubmitDebit(response)
{
    if (response.getElementsByTagName('error_message').length != 0)
	{
        displayMessage("Error: Check data and try again.", "open", "red");
	}
	else
	{
	    var leftOver = response.getElementsByTagName('available_copies_amount')[0].childNodes.item(0).nodeValue;
	    displayMessage("Success. Remaining credit available: " + leftOver, "open", "green");
	    setDebit('reset');
	}
}

// 12
var assetsInArr = new Array();
var allSignedIn = true;
function getSignInList(response)
{
    if (response.getElementsByTagName('assets_description').length != 0)
    {
        assetsInArr['info'] = new Array(); // ID, Name, DueDate
        assetsInArr['bCode'] = new Array();
        assetsInArr['bCode_in'] = new Array();
        assetsInArr['assetDes'] = new Array();
        assetsInArr['note_in'] = new Array();
        
        var count = 0;
        for (var i = 0; i < response.getElementsByTagName('assets_description').length; i++)
        {
            if (response.getElementsByTagName('in_time')[i].childNodes.item(0).nodeValue == 0)
            {
                allSignedIn = false;
                assetsInArr['info'][0] = response.getElementsByTagName('borrowers_student_id')[i].childNodes.item(0).nodeValue;
                assetsInArr['info'][1] = response.getElementsByTagName('borrowers_last_name')[i].childNodes.item(0).nodeValue + ", " + response.getElementsByTagName('borrowers_first_name')[i].childNodes.item(0).nodeValue;
                assetsInArr['info'][2] = response.getElementsByTagName('due_time_string')[i].childNodes.item(0).nodeValue
                assetsInArr['bCode'][count] = response.getElementsByTagName('assets_barcode')[i].childNodes.item(0).nodeValue;
                assetsInArr['assetDes'][count] = response.getElementsByTagName('assets_description')[i].childNodes.item(0).nodeValue;
                assetsInArr['note_in'][count] = "";
                assetsInArr['bCode_in'][count] = "";
            }
            count++;
        }
        if (!allSignedIn)
        {
            displayAssetsIn(assetsInArr);
        }
        else
        {   
            setSignIn('reset');
            displayMessage("This item comes from an old order in which all items were already signed back in.", "open", "red");
        }
    }
    else
    {
        displayMessage("Error: Either this item is not currently signed out or the barcode entered is invalid.", "open", "red")
        setSignIn('reset');
    }
}


// 13
function checkSignIn(response)
{
    displayMessage("Items logged back in.", "open", "green");
    setSignIn('reset');
    assetsInArr = "";
    assetsInArr = new Array();
    allSignedIn = true;
}