/*
File Description: -
This files contains functions use to generate the calendars throughout the website.

/*
Description: -
    Function that fetches eventID, eventName, eventDate, startTime, endTime, location, eventType and eventTopic from the Events table for a given month and year and returns the results as a JSON object passed to a callback function.

Requirements: -
    A html element with id loadingMoreSpinner that is to be displayed while the ajax request fetches the data.

Parameters: -
    month: The numeric month that you want to fetch data for (Starting at 01 for January). Must be passed as a two character string.
    year: the year that you want to fetch data for. Must be passed as a four character string.
    callback: Any callback function that can handle the JSON object. Must be in the format function(jsonObj).
*/

function eventsLoadMore(month, year, callback, limit){
    document.getElementById("loadingMoreSpinner").style.display = "block";
    var xmlhttp = new XMLHttpRequest();
    var jsonObj;
    xmlhttp.onreadystatechange = function(){
        if(this.readyState == 4 && this.status == 200){
            jsonObj = JSON.parse(this.responseText);
            callback(jsonObj);
        }
    };
    xmlhttp.open("POST", "../PHP/Scripts/EventsLoadMore.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    if(typeof limit !== "undefined" && limit === true){
        xmlhttp.send("month="+month+"&year="+year+"&limit=true");
    }
    else{
        xmlhttp.send("month="+month+"&year="+year);
    }
    
}


function daysInMonth(month, year){
    return new Date(year, month + 1, 0).getDate();
}


function firstDayInMonth(month, year){
    return new Date(year, month, 0).getDay();
}


/* Description: -
    Function that populates a Heading 2 with id 'calendarDate' with the month and year that is currently being displayed and populates a table with 42 td's with id's 'calCell0' - 'calCell41' with the date creating a 7 week calendar. The file that calls this function must have a JavaScript function with title 'addEventsAdmin' that will contain the code to add events to the calendar in the desired format making use of the div slots created in each td.

Requirements: -
    See description

Parameters: -
    month: The month for which the calendar will be displayed.
    year: The year for which the calendar will be displayed.
*/


function populateCalendar(month, year, limit){
    var daysInCurrentMonth = daysInMonth(month, year);
    var daysInPreviousMonth;
    if(month > 0){
        daysInPreviousMonth = daysInMonth(month - 1, year);
    }
    else{
        daysInPreviousMonth = daysInMonth(11, year -1);
    }

    var firstDay = firstDayInMonth(month, year);
    var i = 0;
    var calCellNo = 0;

    for(i = firstDay; i > 0; i--){
        document.getElementById("calCell" + calCellNo).innerHTML = "<div class='calendar-inactive'>" + (daysInPreviousMonth - i +1) + "<br/>&nbsp;<div class='calendar-table-div'>&nbsp;</div><div class='calendar-table-div'>&nbsp;</div></div><!-- calendar-inactive -->";
        calCellNo++;
    }

    for(i = 0; i < daysInCurrentMonth; i++){
        calCellNo = i + firstDay;
        document.getElementById("calCell" + calCellNo).innerHTML =  (i + 1) + "<br/><div class='calendar-table-div'>&nbsp;</div><div class='calendar-table-div'>&nbsp;</div><div class='calendar-table-div'>&nbsp;</div>";
    }

    if(todayMonth === calendarMonth && todayYear === calendarYear){
        document.getElementById("calCell" + (firstDay + todayDay - 1)).style.color = "red";
        document.getElementById("calCell" + (firstDay + todayDay - 1)).style.fontWeight = "bold";
    }

    calCellNo++;
    i = 1;

    for(calCellNo; calCellNo < 42; calCellNo++){
        document.getElementById("calCell" + calCellNo).innerHTML = "<div class='calendar-inactive'>" + i + "<br/><div class='calendar-table-div'>&nbsp;</div><div class='calendar-table-div'>&nbsp;</div><div class='calendar-table-div'>&nbsp;</div></div><!-- calendar-inactive -->";
        i++;
    }


    document.getElementById("calendarDate").innerHTML = "<strong>" + monthName(calendarMonth) + "</strong> " + calendarYear;

    sqlMonth = calendarMonth + 1;
    if(sqlMonth < 10){
        sqlMonth = "0" + sqlMonth; 
    }

    if(typeof limit === "undefined"){
        eventsLoadMore(sqlMonth, year, addEvents);
    }
    else{
        eventsLoadMore(sqlMonth, year, addEvents, true);
    }
    
}



/*
Dsecription: -
    Function that increments the month by one (taking into account that the numerical values of the month and the year are preserved) and repopulates the calendar with the new values. Requires two variables 'calendarMonth' and 'calendarYear' that contain numerical values of the month and year respectively.
*/ 
function nextMonth(){
    if(calendarMonth === 11){
        calendarMonth = 0;
        calendarYear++;
    }
    else{
        calendarMonth++;
    }
    populateCalendar(calendarMonth, calendarYear);
    filterTopic();
}




/*

Description: -
    Function to change between calendar and list views.

Requirements: -
    Two html buttons with id's calendarButton and listButton
    Two html divs with id's listView and calendarView that are to be hidden or displayed depending on the input from the buttons.

Parameters: -
    button: string that indicates whether the calendarButton or listButton has triggered the function.
*/

function changeDisplay(button){
    if(button === "calendarButton" && eventDisplay !== "calendar"){
        document.getElementById("listButton").getElementsByTagName("I")[0].style.color = "black";
        document.getElementById("listButton").style.backgroundColor = "rgb(220, 220, 220)";
        document.getElementById("calendarButton").getElementsByTagName("I")[0].style.color = "white";
        document.getElementById("calendarButton").style.backgroundColor = "rgb(211, 55, 74)";
        document.getElementById("calendarView").style.display = "initial";
        document.getElementById('listView').style.display = "none";
        eventDisplay = "calendar";
        $.post('../PHP/Scripts/CalendarSession.php', { value: "calendar"});
    }
    else if(button === "listButton" && eventDisplay !== "list"){
        document.getElementById("listButton").getElementsByTagName("I")[0].style.color = "white";
        document.getElementById("listButton").style.backgroundColor = "rgb(211, 55, 74)";
        document.getElementById("calendarButton").getElementsByTagName("I")[0].style.color = "black";
        document.getElementById("calendarButton").style.backgroundColor = "rgb(220, 220, 220)";
        document.getElementById("calendarView").style.display = "none";
        document.getElementById("listView").style.display = "block";
        eventDisplay = "list";
        $.post('../PHP/Scripts/CalendarSession.php', { value: "list"});   
    }
}



function filterTopic(){
    var checkedCount = 0;
    var filter = [];
    var calendarEvents = document.getElementsByClassName("calendar-event");
    var listViewEvents = document.getElementsByClassName("list-view-event");

    if($("#filterGreenfootCheckbox").prop("checked")){
        filter.push("event-topic-3");
    }
    if($("#filterLegoMindstormCheckbox").prop("checked")){
        filter.push("event-topic-6")
    }
    if($("#filterMicrobitsCheckbox").prop("checked")){
        filter.push("event-topic-4");
    }
    if($("#filterPythonCheckbox").prop("checked")){
        filter.push("event-topic-2");
    }
    if($("#filterRaspberryPisCheckbox").prop("checked")){
        filter.push("event-topic-7");
    }
    if($("#filterScratchCheckbox").prop("checked")){
        filter.push("event-topic-5");
    }
    if($("#filterWebDesignCheckbox").prop("checked")){
        filter.push("event-topic-8");
    }
    if($("#filterTypeTopicCheckbox").prop("checked")){
        filter.push("event-topic-0");
    }

    if($("#filterPrimarySchoolCheckbox").prop("checked")){
        filter.push("event-type-primary-school");
    }
    if($("#filterSecondarySchoolCheckbox").prop("checked")){
        filter.push("event-type-secondary-school");
    }
    if($("#filterCollegeCheckbox").prop("checked")){
        filter.push("event-type-college");
    }
    if($("#filterCPDCheckbox").prop("checked")){
        filter.push("event-type-cpd");
    }
    if($("#filterCommunityCheckbox").prop("checked")){
        filter.push("event-type-community");
    }
    if($("#filterOpenDayCheckbox").prop("checked")){
        filter.push("event-type-cu-open-day");
    }
    if($("#filterUCASCheckbox").prop("checked")){
        filter.push("event-type-cu-ucas-day");
    }
    if($("#filterNetworkingEventCheckbox").prop("checked")){
        filter.push("event-type-networking-event");
    }
    if($("#filterTypeOtherCheckbox").prop("checked")){
        filter.push("event-type-other");
    }    


    if(filter.length > 0){
        var containsFilter = true;
        for(var i = 0; i < calendarEvents.length; i++){
            containsFilter = true;
            for(var j = 0; j < filter.length; j++){
                if(!calendarEvents[i].outerHTML.includes(filter[j])){
                    containsFilter = false;
                    break;
                }
            }
            if(containsFilter == true){
                calendarEvents[i].style.visibility = "visible";
                listViewEvents[i].style.visibility = "visible";
            }
            else{
                calendarEvents[i].style.visibility = "hidden";
                listViewEvents[i].style.visibility = "hidden";
            }
        }
    }
    else{
        for(var i = 0; i < calendarEvents.length; i++){
            calendarEvents[i].style.visibility = "visible";
            listViewEvents[i].style.visibility = "visible";
        }
    }
}