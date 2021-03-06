<?php
session_start();

    require '../.PHPsession/sessionTimeOut.php';
if (isset($_SESSION['inactiveTimeCheck']) && (time() - $_SESSION['inactiveTimeCheck'] >                                                                         $inactiveLimit))
    {
    // last request was more than 30 minutes ago
    session_unset();     // unset $_SESSION variable for the run-time
    session_destroy();   // destroy session data in storage
    }
$_SESSION['inactiveTimeCheck'] = time(); // update last activity time stamp


//If this page is accsessed using a different computer than the one which is logged in then the first if will redirect it to logIn page (the chances of session variables being set NOT TO NULL in another computer are very less for better options they can be encrypted)
    if($_SESSION["nameOfBody"] == NULL || $_SESSION["userName"] == NULL)
        {
        header('Location: ../logInPage/loginScreen.php');
        }
//if a user presses a logout button then first the session variables are unset then even if the previous page's address is pasted in address bar it will not have the session variables set and will be redirected to login page
    else if($_POST["logOutVal"] == "logOut")
        {
        session_unset();
        session_destroy();
        header('Location: ../logInPage/loginScreen.php');
        }
//if the session variables are preperly set i.ie proper login in most cases (except where the exact session variables are set to NON NULL) they are copies into local php variables so that they can be used again
//therfore to use a portal page illegally the session varaibles should have not only been set to non null values but precisely one of the values that match the code after wards
    else
        {
        $nameOfBody = $_SESSION["nameOfBody"];
        $userName = $_SESSION["userName"];
        $tableName = $nameOfBody . "eeVee";
        }

//This function displays the events posted by that club until now with the most recent event first
require '../.groupFamily/groupFamilyTree.php';

function showMyTable()
        {
        require '../.MySQLServer/credentials.php';
        require '../signUpPage/clubColumnData.php';
    
        $dbname = $GLOBALS["nameOfBody"];

        // Create connection
        $conn = mysqli_connect($servername, $username, $password, $dbname);
        // Check connection
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        $clubTableName = $GLOBALS['tableName'];

        $sql = "SELECT * FROM $clubTableName ORDER BY clubId DESC";
        $result = mysqli_query($conn, $sql);

        echo "<tbody>";

        if (mysqli_num_rows($result) > 0)
            {
            while($row = mysqli_fetch_assoc($result))
                {
                if($row[$col[15]] == "deleted" )continue;
                
                $rowId = $row["eeVeeID"];
                $totalMessage = "<tr id='$rowId'>";
                $colNo = 0;
                $groupToolTip = "";
                $typeToolTip = "";
                $timeStamp = "";
                $comment = "";
                $status = "";
                foreach($row as $partialMessage)
                    {
                    if($colNo > 0 && $colNo < 18)
                        {
                            if($colNo == 1 || $colNo == 17){        //display:none;
                                $totalMessage = $totalMessage . "<td style='display:none;' id='$rowId-$colNo'>$partialMessage</td>";
                            }
                            else if($colNo == 2){
                                $totalMessage = $totalMessage . "<td class='eveName' id='$rowId-$colNo'>$partialMessage</td>";
                            }
                            else if($colNo == 7){
                                $freq = "";
                                if($partialMessage == 0)
                                    {
                                    $freq = "once";
                                    }
                                else if($partialMessage == 1111111)
                                    {
                                    $freq = "daily";
                                    }
                                else 
                                    {
                                    if($partialMessage%10 != 0)
                                        {
                                        $freq = $freq . "Sat ";
                                        $partialMessage -=  1;
                                        }
                                    if($partialMessage%100 != 0)
                                        {
                                        $freq = $freq . "Fri ";
                                        $partialMessage -=  10;
                                        }
                                    if($partialMessage%1000 != 0)
                                        {
                                        $freq = $freq . "Thu ";
                                        $partialMessage -=  100;
                                        }
                                    if($partialMessage%10000 != 0)
                                        {
                                        $freq = $freq . "Wed ";
                                        $partialMessage -=  1000;
                                        }
                                    if($partialMessage%100000 != 0)
                                        {
                                        $freq = $freq . "Tue ";
                                        $partialMessage -=  10000;
                                        }
                                    if($partialMessage%1000000 != 0)
                                        {
                                        $freq = $freq . "Mon ";
                                        $partialMessage -=  100000;
                                        }
                                    if($partialMessage%10000000 != 0)
                                        {
                                        $freq = $freq . "Sun ";
                                        $partialMessage -=  1000000;
                                        }
                                    } 
                                $totalMessage = $totalMessage . "<td id='$rowId-$colNo'>$freq</td>";
                            }
                            else if($colNo == 14){  //display:none; and sending data to tool tip
                                $groupToolTip = $partialMessage;
                                $totalMessage = $totalMessage . "<td style='display:none;' class='groupString' id='$rowId-$colNo'>$partialMessage</td>";
                            }
                            else if($colNo == 13){  //display:none; and sending data to tool tip
                                $typeToolTip = $partialMessage;
                                $totalMessage = $totalMessage . "<td style='display:none;' class='groupString' id='$rowId-$colNo'>$partialMessage</td>";
                            }
                            else if($colNo == 16){  //display:none; and sending data to tool tip
                                $timeStamp = $partialMessage;
                                $totalMessage = $totalMessage . "<td style='display:none;' class='groupString' id='$rowId-$colNo'>$partialMessage</td>";
                            }
                            else if($colNo == 12){  //display:none; and sending data to tool tip
                                $comment = $partialMessage;
                                $totalMessage = $totalMessage . "<td style='display:none;' class='groupString' id='$rowId-$colNo'>$partialMessage</td>";
                            }
                            else if($colNo == 15){  //display:none; and sending data to tool tip
                                $status = $partialMessage;
                                $totalMessage = $totalMessage . "<td style='display:none;' class='groupString' id='$rowId-$colNo'>$partialMessage</td>";
                            }
                            else{
                                $totalMessage = $totalMessage . "<td id='$rowId-$colNo'>$partialMessage</td>";
                            }
                        }
                    $colNo = $colNo + 1;
                    }

//-----This is to bring the names of the groupsNodes from groupFamilyTree.php------                
            $groupToolTip = explode(",",$groupToolTip);
            for($i = 0;$i<count($groupToolTip);$i++)
                {
                $groupToolTip[$i] = explode("-",$groupToolTip[$i]);
                }
//------------ Assigning the string of groupNode Names to $groupToolTipTitle --
            $groupToolTipTitle = "/";

            foreach($groupToolTip as $path)
                {
                $title = $GLOBALS["HOME"];
                foreach($path as $pathPoint)
                    {
                    $title = $title[$pathPoint];
                    }
                $groupToolTipTitle = $groupToolTipTitle . "
                /
                " . $title;
                }
//------------ if $groupToolTipTitle is empty then it is assigned "NONE" but it should not take this value as form validation should take care of that ------- 
            if($groupToolTipTitle == "")
                {
                $groupToolTipTitle = "NONE";
                }
                
//Already the $colNo is increased by 1 ---------------------------------------------
                                
//------------- making a column of the groupNode Names , it is for future ref and is to be hidden for now ---------
$totalMessage = $totalMessage . "<td style='display:none;' 
                                    id='$rowId-$colNo'>$groupToolTipTitle</td>";
                
//------ incresing the $colNo so as no two elements have the same id --------
#Do this for all the <td>'s you insert from now on                
                $colNo = $colNo + 1;
             
//----- inserting a 'tooltipp' and 'modal' showing all the comment Names  ----------
$totalMessage = $totalMessage . "<td id='$rowId-$colNo'><a href='#' data-toggle='tooltip'  data-placement='left' title='$comment'><button class='btn btn-warning' data-toggle='modal' data-target='#$rowId-$colNo-modal'><span class='glyphicon glyphicon-comment'></span></button></a></td>";
                
echo "  <div autofocus class='modal fade' id='$rowId-$colNo-modal' role='dialog'>
        <div class='modal-dialog'>
    
          <div class='modal-content'>
            
            <div class='modal-header'>
              <button type='button' class='close' data-dismiss='modal'>&times;</button>
              <h4 class='modal-title'>Comment</h4>
            </div>
            <div class='modal-body'>
              <p>$comment</p>
            </div>
            <div class='modal-footer'>
              <button type='button' class='btn btn-danger' data-dismiss='modal'>Close</button>
            </div>
            
          </div>
      
        </div>
        </div>" ;                 
//------ incresing the $colNo so as no two elements have the same id --------
#Do this for all the <td>'s you insert from now on                
                $colNo = $colNo + 1;                 
                
//----- inserting a 'tooltip' and 'modal' showing all the groupNode Names  ---------- 
 $totalMessage = $totalMessage . "<td id='$rowId-$colNo'><a href='#' data-toggle='tooltip'  data-placement='left' title='$groupToolTipTitle'><button class='btn btn-primary' data-toggle='modal' data-target='#$rowId-$colNo-modal'><span class='glyphicon glyphicon-pushpin'></span></button></a></td>";
                
echo "  <div autofocus class='modal fade' id='$rowId-$colNo-modal' role='dialog'>
        <div class='modal-dialog'>
    
          <div class='modal-content'>
            
            <div class='modal-header'>
              <button type='button' class='close' data-dismiss='modal'>&times;</button>
              <h4 class='modal-title'>Audience</h4>
            </div>
            <div class='modal-body'>
              <p>$groupToolTipTitle</p>
            </div>
            <div class='modal-footer'>
              <button type='button' class='btn btn-danger' data-dismiss='modal'>Close</button>
            </div>
            
          </div>
      
        </div>
        </div>" ;               
                
                
//------ incresing the $colNo so as no two elements have the same id --------
#Do this for all the <td>'s you insert from now on                
                $colNo = $colNo + 1;

//----- inserting a 'tooltip' showing all the TYPES  ----------                
$totalMessage = $totalMessage . "<td id='$rowId-$colNo'><a href='#' data-toggle='tooltip'  data-placement='left' title='$typeToolTip'><button class='btn btn-success'><span class='glyphicon glyphicon-tag'></span></button>
</a></td>"; 
    
//------ incresing the $colNo so as no two elements have the same id --------
#Do this for all the <td>'s you insert from now on                
                $colNo = $colNo + 1;
                
//----- inserting a 'tooltip' showing all the TIMESTAMPS  ----------                
$totalMessage = $totalMessage . "<td id='$rowId-$colNo'><a href='#' data-toggle='tooltip'  data-placement='left' title='$status'><button style='background-color:grey;color:white;' class='btn btn-default'><span class='glyphicon glyphicon-info-sign'></span></button>
</a></td>";                
                
//------ incresing the $colNo so as no two elements have the same id --------
#Do this for all the <td>'s you insert from now on                
                $colNo = $colNo + 1;
                
//----- inserting a 'tooltip' showing all the TIMESTAMPS  ----------                
$totalMessage = $totalMessage . "<td id='$rowId-$colNo'><a href='#' data-toggle='tooltip'  data-placement='left' title='$timeStamp'><button class='btn btn-danger'><span class='glyphicon glyphicon-time'></span></button>
</a></td>"; 
                
                
                
                
//------ incresing the $colNo so as no two elements have the same id --------
#Do this for all the <td>'s you insert from now on                
                $colNo = $colNo + 1;
                
                echo $totalMessage;
                }
            }
        else
            {

            }
        echo "</tbody>";
        mysqli_close($conn);
        }
//--------------------------------------------------------------

?>

<!DOCTYPE html>
<html>
    <head>
        <script src="../../jQuery/jQuery.js"></script>
        <link href="../../BootStrap/bootstrap.min.css" type="text/css" rel="stylesheet"/>
        <script src="../../BootStrap/bootstrap.min.js"></script>
        <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"
        type="text/css">
        <link rel="shortcut icon" href="../../Images/1.jpg">
        <title><?php echo $nameOfBody; ?></title>
        <script>
            var formOpen = false;
            var formMode = "UNDEFINED";
            var selectedEvent ;
            
            function fillForm(selectedEve)
                {
                var formElements = selectedEvent.children();

                var input = formElements[0].innerHTML;
                $("#eeVeeID").val(input);
                input = formElements[1].innerHTML;
                $("#eveName").val(input);
                input = formElements[2].innerHTML;
                $("#evePlace").val(input);
                input = formElements[3].innerHTML;
                $("#eveStTime").val(input);
                input = formElements[4].innerHTML;
                $("#eveStDate").val(input);
                input = formElements[5].innerHTML;
                $("#eveDur").val(input);
                input = formElements[6].innerHTML;
                if(input == "once")
                    {
                    $("#noRepeatButton").prop("checked" , true);
                    }
                else if(input == "daily")
                    {
                    $("#dailyRepeatButton").prop("checked" , true);
                    }
                else //decoding the repetition
                    {
                    $("#weeklyRepeatButton").prop("checked" , true);
                var sunBool , monBool , tueBool , wedBool , thuBool , friBool , satBool;

                    input = input.toString();

                    sunBool = input.indexOf("Sun");
                    if(sunBool != -1)
                        {
                        $("#Sun").prop("checked" , true);
                        }
                    monBool = input.indexOf("Mon");
                    if(monBool != -1)
                        {
                        $("#Mon").prop("checked" , true);
                        }
                    tueBool = input.indexOf("Tue");
                    if(tueBool != -1)
                        {
                        $("#Tue").prop("checked" , true);
                        }
                    wedBool = input.indexOf("Wed");
                    if(wedBool != -1)
                        {
                        $("#Wed").prop("checked" , true);
                        }
                    thuBool = input.indexOf("Thu");
                    if(thuBool != -1)
                        {
                        $("#Thu").prop("checked" , true);
                        }
                    friBool = input.indexOf("Fri");
                    if(friBool != -1)
                        {
                        $("#Fri").prop("checked" , true);
                        }
                    satBool = input.indexOf("Sat");
                    if(satBool != -1)
                        {
                        $("#Sat").prop("checked" , true);
                        }
                        
                    $("#weeklyRepeatContent").slideDown(0);
                    }
                input = formElements[7].innerHTML;
                $("#regDeadTime").val(input);
                input = formElements[8].innerHTML;
                $("#regDeadDate").val(input);
                input = formElements[9].innerHTML;
                $("#regPlace").val(input);
                input = formElements[10].innerHTML;
                $("#regWebsite").val(input);
                input = formElements[11].innerHTML;
                $("#comment").val(input);    
                
                var groupString = formElements[13].innerHTML;
                var groupArray = groupString.split(",");
                    
                var allPaths = $("#pathSelectionRegion").find("input");    
                    
                for (var i=0;i<groupArray.length; i++)
                    {
                    for(var j=0;j<allPaths.length;j++)
                        {
                        if($(allPaths[j]).val() == groupArray[i])
                            {
                            $(allPaths[j]).prop("checked" , true);    
                            }
                        }
                    } 
                
                input = formElements[12].innerHTML;
                 $("#chooseTypeOptions").find("#" + input).prop("checked" , true);    
//                var typesArr = input.split("/");   
//                    
//                for(var i=2;i<typesArr.length;i++)
//                    {
//                $("#chooseTypeOptions").find("#" + typesArr[i]).prop("checked" , true);
//                    }
                    
//----------------------------------------------------------------------------
                }
            function clearForm()
                {
                $("#eveName").val("");
                $("#evePlace").val("");
                $("#eveStTime").val("");
                $("#eveStDate").val("");
                $("#eveDur").val("");
                    {
                $("#noRepeatButton").prop("checked" , false);
                $("#dailyRepeatButton").prop("checked" , false);
                $("#weeklyRepeatButton").prop("checked" , false);
                    $("#Sun").prop("checked" , false);
                    $("#Mon").prop("checked" , false);
                    $("#Tue").prop("checked" , false);
                    $("#Wed").prop("checked" , false);
                    $("#Thu").prop("checked" , false);
                    $("#Fri").prop("checked" , false);
                    $("#Sat").prop("checked" , false);
                $("#weeklyRepeatContent").slideUp(0);
                }
                $("#regDeadTime").val("");
                $("#regDeadDate").val("");
                $("#regPlace").val("");
                $("#regWebsite").val("");
                $("#comment").val("");    
                var allCheckBoxes ;
                allCheckBoxes = $("#pathSelectionRegion").find("input")

                for(var j=0;j<allCheckBoxes.length;j++)
                    {
                    $(allCheckBoxes[j]).prop("checked" , false);
                    }
                
                $("#chooseTypeOptions input").prop("checked" , false);    
                    
                }

            $(document).ready(function(){
                $('[data-toggle="tooltip"]').tooltip();
                
                 $("a").tooltip({
                            'selector': '',
                            'placement': 'top',
                            'container':'body'
                            });
//-------------- clubName Script  --------------------------------------------
                $("#clubName").mouseenter(function(){
                    $("#clubName").animate({fontSize : '+=1.5em'},100);
                });
                $("#clubName").mouseleave(function(){
                    $("#clubName").animate({fontSize : '-=1.5em'},100);
                });
//-----------------------------------------------------------------------

//-------------- Create New Event Form Script  ----------------------------------
                $("#createNewEventFormButton").click(function(){
//                    var allCheckBoxes ;
//                    allCheckBoxes = $("#pathSelectionRegion").find("input")
//
//                    for(var j=0;j<allCheckBoxes.length;j++)
//                        {
//                        $(allCheckBoxes[j]).prop("disabled" , false);
//                        }
                    //make all the checkboxes enabled so that group path can be edited
                    $("#EventFormTag").attr("action","insertNewEventSecured.php");
                    //changing the action attribute to send it to inserting page
                    var imgSrc = $("#createFormBackground").attr("src");
                    $("#EventFormRegion").css('background-image', 'url("' + imgSrc +                                      '")');
                    //changing the background color
                    if(!formOpen)
                        {
                        $("#EventFormRegion").slideDown();
                        formOpen = !formOpen;   
                        }
                    //if form is not open then slide down the form
                    //change the var 'formOpen' to the opposite value
                    else if(formOpen && formMode == "create")
                        {
                        $("#EventFormRegion").slideUp();
                        formOpen = !formOpen;
                        }
                    //if form is open and last clicked button is create then slideup the form and change the value of var 'formOpen'
                    else if(formOpen && formMode == "edit")
                        {

                        }
                    //if form is open and last clicked button is edit then SKIP
                    formMode = "create";
                });

                $("#weeklyRepeatButton").click(function(){
                    $("#weeklyRepeatContent").slideDown();
                });
                $("#noRepeatButton").click(function(){
                    $("#weeklyRepeatContent").slideUp();
                });
                $("#dailyRepeatButton").click(function(){
                    $("#weeklyRepeatContent").slideUp();
                });
                $("#registrationContentButton").click(function(){
                    $('#registrationContent').slideToggle();
                });

//-------------------------------------------------------------------------
//--------------  Event Validation Script  ----------------------------------
                $("#validateEventForm").click(function(){

                    var validationMessage = "";
                    var submit = true;

                    if($("#eveName").val() == "")
                        {
                        validationMessage = validationMessage + "Name of the event is Empty<br>";
                        submit = false;
                        }
                    if($("#evePlace").val() == "")
                        {
                        validationMessage = validationMessage + "Place is Empty<br>";
                        submit = false;
                        }
                    if($("#eveStDate").val() == "")
                        {
                        validationMessage = validationMessage + "Starting Date is not fully filled<br>";
                        submit = false;
                        }
                    if($("#eveStTime").val() == "")
                        {
                        validationMessage = validationMessage + "Starting Time is not fully filled<br>";
                        submit = false;
                        }
                    var duration = $("#eveDur").val();
                    duration = Number(duration);
                    if(duration <= 0)
                        {
                        validationMessage = validationMessage + "Duration is not Proper<br>";
                        submit = false;    
                        }
                    else if($("#eveDur").val() == "")
                        {
                        validationMessage = validationMessage + "Duration is not Proper<br>";
                        submit = false;
                        }
                    if(!$("#noRepeatButton").is(':checked') &&                                  !$("#dailyRepeatButton").is(':checked') &&                              !$("#weeklyRepeatButton").is(':checked') )
                        {
                        validationMessage = validationMessage + "Please select a repetition<br>";
                        submit = false;
                        }

                    var allCheckBoxes ;
                    allCheckBoxes = $("#pathSelectionRegion").find("input")
                    var groupPathValidation = false;

                    for(var j=0;j<allCheckBoxes.length;j++)
                        {
                        if($(allCheckBoxes[j]).is(':checked'))
                            {
                            groupPathValidation = true;
                            break;
                            }
                        }
                    if(!groupPathValidation)
                        {
                        validationMessage = validationMessage + "Please select atleast one Group<br>";
                        submit = false;
                        }

                    var typeValidation = false;
                    var allTypes = $("#chooseTypeOptions").find("input");
                    for(var i=0;i<allTypes.length;i++)
                        {
                        if($(allTypes[i]).is(':checked'))
                            {
                            typeValidation = true;
                            break;    
                            }
                        }
                    
                    if(!typeValidation)
                        {
                        validationMessage = validationMessage + "Please select atleast one Type<br>";
                        submit = false;    
                        }
                    
                    var registrationDetailsFilled = false;
                    if($("#regDeadDate").val() != ""
                            && $("#regDeadTime").val() != ""
                                && $("#regPlace").val() != ""
                                    && $("#regWebsite").val() != "")
                        {
                        registrationDetailsFilled = true;
                        }
                    
                    var commentFilled = false;
                    if($("#comment").val() != "")
                        {
                        commentFilled = true;    
                        }
                    
                    
                    $("#validationMessage").html(validationMessage);
                    $("#validationMessage").slideDown(100);
                    $("#validationMessage").delay(5000).slideUp(100);

                    if(submit)
                        {
                        $("#EventFormTag").submit();
                        }
                });
//-------------------------------------------------------------------------
//------------ Edit and Delete Event Script  ---------------------------------------
//If background color is changed then you have to make changes in the css properties in this area
                $("#editOldEventFormButton").click(function(){
//                    var allCheckBoxes ;
//                    allCheckBoxes = $("#pathSelectionRegion").find("input")
//
//                    for(var j=0;j<allCheckBoxes.length;j++)
//                        {
//                        $(allCheckBoxes[j]).prop("disabled" , true);
//                        }
                    //disabling all the checkboxes so that admin cannot edit the path
                    $("#EventFormTag").attr("action","editOldEventSecured.php");
                    //changing the action attribute to send it to editing page
                    var imgSrc = $("#editFormBackground").attr("src");
                    $("#EventFormRegion").css('background-image', 'url("' + imgSrc +                                      '")');
                    //changing the background color
                    if(!formOpen)
                        {
                        fillForm(selectedEvent);
                        $("#EventFormRegion").slideDown();
                        formOpen = !formOpen;
                        }
                    //if form is not open then autofill the form and then slide down
                    //change the var 'formOpen' to the opposite value
                    else if(formOpen && formMode == "edit")
                        {
                        $("#EventFormRegion").slideUp();
                        formOpen = !formOpen;
                        clearForm();
                        }
                    //if form is open and last clicked button is edit then slideup the form and clear the form and change the value of var 'formOpen'
                    else if(formOpen && formMode == "create")
                        {
                        clearForm();
                        fillForm(selectedEvent);
                        }
                    //if form is open and last clicked button is create then clear the form and autofill the form with the selected event
                    formMode = "edit";
                });

                $("#deleteEventButton").click(function(){
                   $("#deleteEventConfimationForm").fadeToggle(100);
               });
               $("#deleteEventConfirmation").click(function(){
                  $("#deleteEventForm").submit();
               });
               $("#deleteEventRejection").click(function(){
                  $("#deleteEventConfimationForm").fadeOut(100);
               });

//---------------  Event row click script ----------------------------------------
              $("#eventPortal tbody tr").click(function(){
                   $("#eventPortal tbody tr").css("background","white");
                   $(this).css("background","lightgreen");
                   //background color change
                   selectedEvent = $(this);
                   //row is put in 'selectedEvent' variable
                   $("#deleteEventeeVeeId").val($(this).attr("id"));
                   //deleteEvent form's  'deleteEventeeVeeId' input is filled with Id of selected <tr> as its id is already eeVee ID
                    if(!formOpen)
                        {

                        }
                    else if(formOpen && formMode == "edit")
                        {
                        clearForm();
                        fillForm(selectedEvent);
                        }
                   //if the last clicked button is edit and form is open the change the auto fill
                   $("#deleteEventButton").fadeIn(100);
                   $("#editOldEventFormButton").fadeIn(100);
                   $("#deleteEventConfimationForm").fadeOut(100);
                   //self-explainatory
                });
//-------------- This part is for going to top button ---------------------------- 
            $(window).scroll(function(){
                var scrollFromTop = $(this).scrollTop();
                if(scrollFromTop > 500){
                    $("#goToTop").fadeIn();
                }else{
                    $("#goToTop").fadeOut();
                }
            });
                
            $("#goToTop").on('click' ,function(e){
                 e.preventDefault();
                 $("html , body").animate({scrollTop:0} , 1000); 
              });
            
            
//            $("#createNewEventFormButton , #editOldEventFormButton").on('click'         ,function(e){
//                              e.preventDefault();
//                             $("html , body").animate({scrollTop:0} , 1000); 
//                          });     
//-------------- This part is for horizontal scrolling of table ----------------------
               //Actual handling of the scrolling
            var scrollHandle = 0,
                scrollStep = 3,
                parent = $("#eventPortal");
                
            function startScrolling(modifier, step) 
                {
                if (scrollHandle === 0)
                    {
                    scrollHandle = setInterval(function () 
                                                {
                        var newOffset = parent.scrollLeft() + (scrollStep * modifier);
                        parent.scrollLeft(newOffset);
                                                }, 10);
                    }
                }

            function stopScrolling() 
                {
                    clearInterval(scrollHandle);
                    scrollHandle = 0;
                    }
                
              $(".horzScorll").on("mouseenter" , function(){
                   var  data = $(this).data('scrollModifier'),
                        direction = parseInt(data, 10);
                  
                  startScrolling(direction, scrollStep);
              }); 
                
              $(".horzScorll").on("mouseleave" , function(){
                    stopScrolling();
              });    
//----------------------------------------------------------------------------
            });
        </script>
        <style>
            body{
                padding-left: 0px;
            }
            #clubName{
                position: relative;
            }
            #EventFormRegion{
                font-size: 15px;
            }
            #eventPortal{
                padding: 15px;
            }
            #eventPortal table{

            }
            #eventPortal th{
              text-align: center;
            }
            #eventPortal td{
              text-align: center;    
            }
            #eventPortal thead{
            
            }
            .tooltip-inner{
                color: aliceblue;
                background-color: cadetblue;
                font-size: 20px;
                max-width: 350px;
            }            
            #EventFormRegion{
                background-size: contain; 
            }
            .formLabel{
                color: white;
            }
            .frequency label{
                color: white;
            }
            #goToTop{
                position: fixed;
                top: 0;
                right: 50%;
                z-index: 100;
            }
            #goToTop button{
                opacity: 0.1;
                height: 30px;
                width: 150px;
                background-color: aliceblue;
                border-radius: 20px;
            }
            #goToTop button:hover{
                opacity: 0.2;
                background-color: lightblue;
            }
            .affix{
                top: 0;
                width: 100%;
                z-index: 1000;
                background-clip: content-box;
                background-color: dimgray;
                border-bottom-left-radius: 30px;
                border-bottom-right-radius: 30px;
            }
            .affix-top{
            }
            #topAffixArea{
                padding-bottom: 10px;
            }
            #Buttons{
                text-align: center;
            }
            .tableHead{
                
            }
            .horzScorll{
                position: fixed;
                height: 100%;
                width: 1%;
                opacity: 0.1;
                z-index: 1001;
            }
            .horzScorll:hover{
                opacity: 0.5;
            }
            #leftScroll{
                top: 0px;
                left: 0px;
            }
            #rightScroll{
                top: 0px;
                right: 0px;
            }
            .eveName{
                font-weight: bold;
                font-style: italic;
            }
            #logOut{
                text-align: center;
            }
            #topFixedPart{

            }
        </style>
    </head>

    <body>
        <div class="container-fluid">
            
            <div id="topFixedPart">
            <hr>
            
            <div class="row">
                <div class="col-xs-2">
                    <span id="clubName" style="font-size:40px;"><?php echo $nameOfBody; ?></span>
                </div>
                <div class="col-xs-8">
                    
                </div>
                <div class="col-xs-2">
                    <span id="logOut">
                        <form method="post" action="<?php                                                   htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                            <input type="text" value="logOut" style="display:none"                          name="logOutVal">
                            <input class="btn btn-success" type="submit" value="Log Out">
                        </form>
                    </span>
                </div>
            </div>
            
            <hr>
            </div>
<!--            <div data-spy="affix" data-offset-top="104" style="text-align:center;" id="topAffixArea" class="row">-->
            
<!--     Create Delete and Edit Buttons       -->
                <div class="row" id="Buttons">
<!--     Create New Event Button           -->
                <div class="col-xs-4">
                    <div>
                        <button class="btn btn-info" id="createNewEventFormButton" type="button" >Create</button>
                    </div>
                
                </div>
<!--     Edit Selected Event Button           -->
                <div class="col-xs-4">
                    <button id="editOldEventFormButton" style="display:none;" class="btn btn-warning" type="button" >Edit</button>
                </div>
<!--     Delete Selected Event Button           -->
                <div id="deleteEvent"  class="col-xs-4">
                    <form id="deleteEventForm" method="post"                                       action="deleteOldEventSecured.php">
                    <input style='display:none;' type='password' name='check' value='proper'>
                    <input style='display:none;' type="text" value="eeVeeID"                                   id="deleteEventeeVeeId" name="deleteEvent">
                    <input class="btn btn-danger" style="display:none;" id="deleteEventButton" type="button" value="Delete" >
                    </form>

                    <div id="deleteEventConfimationForm" style="display:none;">
                        <div class="row">
                            <div class="col-xs-12">
                                Are you sure you want to delete the selected event?
                            </div>
                        </div>
                        <div class="row">
                            <button class="btn btn-warning col-xs-6"                                        id="deleteEventConfirmation" >Yes</button>
                            <button class=" btn btn-success col-xs-6" id="deleteEventRejection" autofocus >No</button>
                        </div>
                    </div>
                </div>
                </div>
               
<!--               </div>-->
            
            <br>
            
<!--                  Event form                   -->
            <div id="EventFormRegion" style="display:none;" class="row">
                <div id="EventForm"  class="col-xs-12">
                    <form id="EventFormTag" method="post"                                             action="insertNewEventSecured.php">
                        <div class="row">
                            <div  class="col-xs-2">
                                <?php
                                require '../typesOfEvents/typesOfEventsTable.php';
                                echo $layout;
                                ?>               
                            </div>
                            <div class="col-xs-6">
                                <?php
                                require 'clubPortalEventForm.php';
                                echo $EventFormWithoutPath;
                                ?>
                                <div style="color:darkred;display:none;" id="validationMessage">

                                </div>
                            </div>
                            <div id="pathSelectionRegion" class="col-xs-4">
                                <?php
                                echo $EventPath;
                                ?>
                            </div>
                        </div>
                    </form>
                    <hr>
                </div>
            </div>
            
<!--                  Events Table display          -->
            <div class="row">
               <button type="button" id="leftScroll" data-scroll-modifier='-1' class=" horzScorll"></button>
                <button type="button" id="rightScroll" data-scroll-modifier='1' class=" horzScorll"></button>
               
                <div id="eventPortal" class="col-xs-12 table-responsive">
                    <table id="eventPortalTable" class='table'>
                    <?php
//                            data-spy='affix' data-offset-top='110'
//...........the column names are printed here ..................................
                    require '../signUpPage/clubColumnData.php';
                    echo "<br>";
                    echo "<thead class='tableHead'><tr class='bg-primary'>";
                    $colNo = 0;
                    foreach($col as $colName)
                        {
                        if($colNo > 0 && $colNo < 18)
                            {
                            if($colNo == 1){
                                echo "<th style='display:none;'>$colName</th>";
                            }
                            else if($colNo == 12){
                                echo "<th style='display:none;'>$colName</th>";
                            }
                            else if($colNo == 13){
                                echo "<th style='display:none;'>$colName</th>";
                            }
                            else if($colNo == 14){
                                echo "<th style='display:none;'>$colName</th>";
                            }
                            else if($colNo == 15){
                                echo "<th style='display:none;'>$colName</th>";
                            }
                            else if($colNo == 16){
                                echo "<th style='display:none;'>$colName</th>";
                            }
                            else if($colNo == 17){
                                echo "<th style='display:none;'>$colName</th>";
                            }
                            else{
                                echo "<th>$colName</th>";
                            }
                            }//column names
                        $colNo = $colNo + 1;
                        }

                    echo "<th style='display:none;'>target Groups</th>";
                    echo  "<th>Comment</th>";        
                    echo  "<th>Groups</th>";
                    echo  "<th>Type</th>";
                    echo  "<th>Status</th>";        
                    echo  "<th>TS</th>";       
                            
                    echo  "</tr></thead>";
//.............................................................................

                    showMyTable();
                    ?>
                    </table>
                </div>    
            </div>   
            
        <a href="#" id="goToTop" style="display:none;"><button type="button" >UP</button></a>  
        
        </div>
        
        <img src="../../Images/editFormBackground.jpg" alt="editFormBackground" style="display:none;" id="editFormBackground"/>
        <img src="../../Images/createFormBackground.jpg" alt="createFormBackground" style="display:none;" id="createFormBackground"/>
        
        <div style="height:2000px"></div>
        <!--
        
        checking the event in clubDB before inserting or updating to reduce spamming<br>
        frequency decrypting<br> 
        think and increase the flexibility of the groups concept<br>
        task implementation<br>
        using status column<br>
        making js more object oriented<br>
        validation of comment and registration <br>
        horz scroll and not scrolling when create is clicked and already the form is open<br> 
        thorough testing <br>
        -->
        
    </body>
</html>
