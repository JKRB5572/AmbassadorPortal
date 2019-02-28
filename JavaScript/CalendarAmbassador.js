class Event {
    constructor(eventID, eventName, eventDate, startTime, endTime, type, trainingRequired, topicID, topicString) {
        this.ID = eventID;
        this.name = stripQuotationMarks(eventName);
        this.date = eventDate;
        this.startTime = startTime;
        this.endTime = endTime;
        this.type = type;
        this.trainingRequired = trainingRequired;
        this.topicID = JSON.parse(topicID);
        this.topicString = topicString;
     
    
        this.registeredToWork = function() {
            if(registeredEvents.indexOf(this.ID) > -1){
                return true;
            }
            else{
                return false;
            }
        };

        
        this.selectedToWork = function() {
            if(workingEvents.indexOf(this.ID) > -1){
                return true;
            }
            else{
                return false;
            }

        };


        this.reserveList = function() {
            if(reserveEvents.indexOf(this.ID) > -1){
                return true;
            }
            else{
                return false;
            }
        }


        this.eligibility = function () {
            if(!this.topicID){
                return "eligible";
            }
            else if(this.trainingRequired != "Y"){
                return "eligible";
            }
            else{
                if(training){
                    for(var i = 0; i < this.topicID.length; i++){
                        if( topicsRequiringTraining.includes(this.topicID[i]) ){
                            if( !training.includes(parseInt(this.topicID[i])) ){
                                return "not-eligible";
                            }
                        }
                    }
                    return "eligible";
                }
                else{
                    return "not-eligible";
                }
            }
        };


        this.registeredClass = function(){
            if(this.selectedToWork() == true){
                return "working-event";
            }
            else if(this.reserveList() == true){
                return "reserve-list-for-event";
            }
            else if(this.registeredToWork() == true){
                return "registered-for-event";
            }
            else if(this.eligibility() == "eligible"){
                return "not-registered-for-event";
            }
                
        };


        this.getPositionInCalendar = function(firstDay) {
            return this.date.slice(-2) - 1 + firstDay;
        };


        this.eventClasses = function () {
            var returnValue = "event-type-" + String(this.type).replace(/\s/g, "-").toLowerCase() + " ";

            if(!this.topicID){
                returnValue += " event-topic-null";
            }
            else if(this.topicID.length > 1){
                returnValue += " event-topic-mixed";
                for (var i = 0; i < this.topicID.length; i++) {
                    returnValue += " event-topic-" + this.topicID[i].replace(/(\s|:)/g, "-").toLowerCase();
                }
            }
            else {
                returnValue += " event-topic-" + this.topicID[0].replace(/(\s|:)/g, "-").toLowerCase();
            }
            returnValue += " " + this.eligibility();
            returnValue += " " + this.registeredClass();
            return returnValue;
        };


        this.onClick = function() {
            var returnValue;

            if(this.eligibility() == "eligible"){
                returnValue = "onclick='showEventDetails(\"" + this.ID + "\", \"" + this.name + "\", \"" + this.date + "\", \"" + this.startTime + "\", \"" + this.endTime + "\", \"" + this.type + "\", \"" + this.topicString + "\")' ";
            }
            else{
                returnValue = "onclick='showNonEligible()' ";
            }

            return returnValue;
        }


        this.calendarViewHTML = function () {
            var returnValue = "<div id='" + this.ID + "' class='calendar-event ";
            returnValue += this.eventClasses() + "' ";
            returnValue += this.onClick();
            returnValue += ">" + this.name + "</div>";
            return returnValue;
        };


        this.listViewDateTime = function() {
            var returnValue;

            if(this.date){
                returnValue = verboseDateHTML(this.date);
                if(this.startTime){
                    returnValue += ", <em>" + String(this.startTime).slice(0, 5);
                    if(this.endTime){
                        returnValue += " - " + String(this.endTime).slice(0, 5);
                    }
                    returnValue += "</em>";
                }

            }
            else if(this.startTime){
                returnValue = "<em>" + String(this.startTime).slice(0, 5);
                if(this.endTime){
                    returnValue += " - " + String(this.endTime).slice(0, 5);
                }
                returnValue += "</em>";
            }

            return returnValue;
        };


        this.listViewType = function() {
            if(this.type){
                return "<br/>" + this.type;
            }
        }


        this.listViewTopic = function() {
            if(this.topicString){
                return "<br/>" + this.topicString;
            }
        }
        
        
        this.listViewHTML = function() {
            var returnValue = "<td class='list-view-event " + this.eventClasses() + "'>";
            returnValue += "<a href='Event.php?id=" + this.ID + "'>";
            returnValue += "<div>";
            returnValue += "<h5>" + this.name + "</h5>";
            returnValue += "<p>";
            returnValue += this.listViewDateTime();
            returnValue += this.listViewType();
            returnValue += this.listViewTopic();
            returnValue += "</p></div></a></td>";

            return returnValue;
        };

    }
}


function confirmWithdraw(eventID, userID){
    var confirmation = confirm("Are you sure you wish to withdraw from this event? This cannot be undone.");
    if(confirmation == true){
        registerForEvent(eventID, userID);
    }
}


function registerForEvent(eventID, userID){
    document.getElementById("sendingDataSpinner").style.display = "block";
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function(){
        if(this.readyState == 4 && this.status == 200){
            document.getElementById("sendingDataSpinner").style.display = "none";
            var eventHTML = document.getElementById(eventID).outerHTML;

            if(eventHTML.includes("not-registered-for-event")){
                var eventHTML = eventHTML.replace("not-registered-for-event", "registered-for-event");
                document.getElementById("showEventDetails").innerHTML = "Registration successful.";
            }
            else if(eventHTML.includes("working-event")){
                var eventHTML = eventHTML.replace("working-event", "not-registered-for-event");
                document.getElementById("showEventDetails").innerHTML = "Request to withdraw sent successfully.";
            }
            else if(eventHTML.includes("registered-for-event")){
                var eventHTML = eventHTML.replace("registered-for-event", "not-registered-for-event");
                document.getElementById("showEventDetails").innerHTML = "Deregistration succesful.";
            }
            else if(eventHTML.includes("reserve-list-for-event")){
                var eventHTML = eventHTML.replace("reserve-list-for-event", "not-registered-for-event");
                document.getElementById("showEventDetails").innerHTML = "Deregistration succesful.";
            }
            
            document.getElementById(eventID).outerHTML = eventHTML;
        }
    };
    xmlhttp.open("POST", "../PHP/Scripts/EventRegistration.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    var postHeader = "eventID=" + eventID + "&universityID=" + userID;
    xmlhttp.send(postHeader);
}


function registrationComplete(eventID){
    document.getElementById("sendingDataSpinner").style.display = "none";
}


/*
Description: -
    Modified version of previousMonth function where the user cannot navigate to a month before the current date.
*/

function previousMonth(){
    if(calendarMonth > todayMonth && calendarYear >= todayYear){
        if(calendarMonth === 0){
            calendarMonth = 11;
            calendarYear--;
        }
        else{
            calendarMonth--;
        }
        populateCalendar(calendarMonth, calendarYear);
        filterEventType();
    }
}


function addEvents(jsonObj){
    //Clear list view
    document.getElementById('listView').innerHTML = "";

    var listViewContent = "";

    var ref, previousRef = 0, refCount = 0, jsonLength = jsonObj.length;
    var eventColour;
    var firstDay = firstDayInMonth(calendarMonth, calendarYear);

    if(jsonObj.length < 1){
        document.getElementById('listView').innerHTML = "No events this month";
    }

    else{
        

        var monthsEvents = [];

        for(var i =0; i < jsonObj.length; i++){
            monthsEvents.push(new Event(
                jsonObj[i]["eventID"],
                jsonObj[i]["eventName"], 
                jsonObj[i]["eventDate"],
                jsonObj[i]["startTime"], 
                jsonObj[i]["endTime"], 
                jsonObj[i]["type"], 
                jsonObj[i]["trainingRequired"],
                jsonObj[i]["topicID"],
                jsonObj[i]["topicString"]
            ));
        }

        listViewContent += '<table>';

        for(var i = 0; i < monthsEvents.length; i++){

            var eventName = monthsEvents[i]["name"];
            if(eventName.length > 20){
                eventName = eventName.slice(0, 17) + "...";
            }

            ref = monthsEvents[i].getPositionInCalendar(firstDay);


            if(previousRef === ref && refCount <= 1){
                refCount++;
                document.getElementById("calCell"+ref).getElementsByTagName("DIV")[refCount * 2].innerHTML = monthsEvents[i].calendarViewHTML();
            }
            else if(previousRef === ref && refCount > 1 && i+1 === jsonLength){
                refCount++;
                document.getElementById("calCell"+previousRef).getElementsByTagName("DIV")[4].innerHTML = "<div class='event-topic-more'>" + (refCount - 1) + " more events</div>";
            }
            else if(previousRef === ref && refCount >= 2){
                refCount++;
            }
            else if(previousRef !== ref && refCount > 2){
                document.getElementById("calCell"+previousRef).getElementsByTagName("DIV")[4].innerHTML = "<div class='event-topic-more'>" + (refCount - 1) + " more events</div>";
                document.getElementById("calCell"+ref).getElementsByTagName("DIV")[0].innerHTML = monthsEvents[i].calendarViewHTML();
                refCount = 0;
            }
            else{
                document.getElementById("calCell"+ref).getElementsByTagName("DIV")[0].innerHTML = monthsEvents[i].calendarViewHTML();
                refCount = 0;
            }
            
            previousRef = ref;

            if(i % 3 === 0){
                listViewContent += "<tr>" + monthsEvents[i].listViewHTML();
            }
            else if(i % 3 === 2) {
                listViewContent += monthsEvents[i].listViewHTML() + "</tr>";
            }
            else{
                listViewContent += monthsEvents[i].listViewHTML();
            }
        }
        listViewContent += '</table>';
        document.getElementById('listView').innerHTML = listViewContent;
    }
    filterEventType();
    document.getElementById("loadingMoreSpinner").style.display = "none";
}



function checkWithdrawalPeriod(_eventDate){
    var eventDate = new Date(dateStringJS(_eventDate));
    console.log(dateStringJS(_eventDate));
    var todayDate = new Date();
    var timeDifference = eventDate.getTime() - todayDate.getTime();
    var daysDifference = Math.ceil(timeDifference / (1000 * 3600 * 24));

    console.log(daysDifference);

    if(daysDifference > 2){
        return true;
    }
    else{
        return false;
    }
}



function showEventDetails(eventID, eventName, eventDate, startTime, endTime, eventType, topicString){
    var eventHTML = document.getElementById(eventID).outerHTML;

    var eventDetails;
    eventDetails = "<strong>" + eventName + "</strong><br/>";
    if(eventDate){
        eventDetails += verboseDateHTML(eventDate);
        if(startTime){
            eventDetails += ", ";
        }
    }
    if(startTime){
        eventDetails += "<em>" + startTime.slice(0, 5);
        if(endTime){
            eventDetails += " - " + endTime.slice(0, 5);
        }
        eventDetails += "</em>";
    }

    if(eventType !== "null"){
        eventDetails += "<br/>" + eventType;
    }
    if(topicString !== "null"){
        eventDetails += "<br/>" + topicString;
    }
    if(eventHTML.includes("not-registered-for-event")){
        eventDetails += "<br/><br/><button onclick='registerForEvent(\"" + eventID + "\", \"" + userID + "\")' style='color: white;'>Register</button>";
    }
    else if(eventHTML.includes("working-event")){

        eventDetails += "<br/><br/>You have been selected to work this event. "

        if(checkWithdrawalPeriod(eventDate) == true){
            eventDetails += "If you are no longer able to attend you may withdraw by clicking the button below.<br/><br/><button onclick='confirmWithdraw(\"" + eventID + "\", \"" + userID + "\")' style='color: white;'>Withdraw</button>";
        }
        else{ 
            eventDetails += "As this event starts in less than 48 hours if you are no longer able to attend you must send an email to comscambassadors@cardiff.ac.uk requesting to withdraw and explaining the reason(s) why.";
        }

    }
    else if(eventHTML.includes("reserve-list-for-event")){
        eventDetails += "<br/><br/>Other ambassadors have been selected for this event. You are currently on a reserve list in case any of the selected ambassadors are no longer able to attend the event and will be updated if and when any changes occur.<br/><br/><button onclick='registerForEvent(\"" + eventID + "\", \"" + userID + "\")' style='color: white;'>Deregister</button>";
    }
    else if(eventHTML.includes("registered-for-event")){
        eventDetails += "<br/><br/>You have registered your interest in working this event and will be soon be notified if you have been selected.<br/><br/><button onclick='registerForEvent(\"" + eventID + "\", \"" + userID + "\")' style='color: white;'>Deregister</button>";
    }


    document.getElementById("showEventDetails").innerHTML = eventDetails;
}


function showNonEligible(){
    document.getElementById("showEventDetails").innerHTML = "<p><strong>You do not meet the training requirements to be eligible for this event.</strong></p?";
}