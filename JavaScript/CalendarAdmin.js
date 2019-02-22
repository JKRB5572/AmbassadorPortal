class Event {
    constructor(eventID, eventName, project, fundingSource, eventDate, startTime, endTime, type, address1, postcode, topicID, topicString, visibility){
        this.ID = eventID;
        this.name = stripQuotationMarks(eventName);
        this.project = stripQuotationMarks(project);
        this.fundingSource = fundingSource;
        this.date = eventDate;
        this.startTime = startTime;
        this.endTime = endTime;
        this.type = type;
        this.address1 = stripQuotationMarks(address1);
        this.postcode = postcode;
        this.topicID = JSON.parse(topicID);
        this.topicString = topicString;
        this.visibility = visibility;


        this.getPositionInCalendar = function(firstDay) {
            return this.date.slice(-2) - 1 + firstDay;
        };


        this.eventClasses = function() {
            var returnValue = "event-type-" + String(this.type).replace(/\s/g, "-").toLowerCase();
            returnValue += "event-funding-" + String(fundingSource).replace(/\s/g, "-").toLowerCase();

            if(!this.topicID){
                returnValue += " event-topic-null";
            }
            else if(this.topicID.length > 1){
                returnValue += " event-topic-mixed";
                for(var i = 0; i < this.topicID.length; i++){
                    returnValue += " event-topic-" + this.topicID[i].replace(/(\s|:)/g, "-").toLowerCase();
                }
            }
            else{
                returnValue += " event-topic-" + this.topicID[0].replace(/(\s|:)/g, "-").toLowerCase();
            }
            return returnValue;
        };


        this.onClick = function() {
            var returnValue = "onclick='showEventDetails(\"" + this.ID + "\", \"" + this.name + "\", \"" + this.date + "\", \"" + this.startTime + "\", \"" + this.endTime + "\", \"" + this.address1 + "\", \"" + this.postcode + "\", \"" + this.type + "\", \"" +  this.topicString + "\", \"" + this.fundingSource + "\")' ";

            returnValue += " ondblclick='redirect(\"Event.php?id=" + this.ID + "\")' ";
            return returnValue;
        }


        this.checkVisibility = function() {
            if(this.visibility > 0){
                return " <i class='fa fa-eye-slash' style='float: right;'></i>";
            }
            else{
                return "";
            }
        }


        this.calendarViewHTML = function() {
            var returnValue  = "<div id='" + this.ID + "' class='calendar-event ";
            returnValue += this.eventClasses() + "' ";
            returnValue += this.onClick();
            returnValue += " >";
            
            if(this.name.length > 20){
                returnValue += this.name.slice(0, 17) + "...";
            }
            else{
                returnValue += this.name;
            }
            returnValue += this.checkVisibility();
             
            returnValue += "</div>";
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


        this.listViewAddress = function() {
            var returnValue;

            if(this.address1){
                returnValue = "<br/>" + this.address1;
                if(this.postcode){
                    returnValue += ", " + this.postcode;
                }
            }
            else if(this.psotcode){
                returnValue += "<br/>" + this.postcode;
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
            returnValue += "<a href='Event.php?id=" + this.ID  + "'>";
            returnValue += "<div>"
            returnValue += "<h5>" + this.name;
            returnValue += this.checkVisibility();
            returnValue += "</h5>";
            returnValue += "<p>";
            returnValue += this.listViewDateTime();
            returnValue += this.listViewAddress();
            returnValue += this.listViewType();
            returnValue += this.listViewTopic();
            returnValue += "</p></div></a></td>";

            return returnValue;
        };

    }
}




function previousMonth(){
    if(calendarMonth === 0){
        calendarMonth = 11;
        calendarYear--;
    }
    else{
        calendarMonth--;
    }
    populateCalendar(calendarMonth, calendarYear);
    filterTopic();
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
        
        for(var i = 0; i < jsonObj.length; i++){
            monthsEvents.push(new Event(
                jsonObj[i]["eventID"],
                jsonObj[i]["eventName"],
                jsonObj[i]["project"],
                jsonObj[i]["fundingSource"],
                jsonObj[i]["eventDate"],
                jsonObj[i]["startTime"],
                jsonObj[i]["endTime"],
                jsonObj[i]["type"],
                jsonObj[i]["address1"],
                jsonObj[i]["postcode"],
                jsonObj[i]["topicID"],
                jsonObj[i]["topicString"],
                jsonObj[i]["visibility"]
            ));
        }

        listViewContent += '<table>';

        for(var i = 0; i < jsonObj.length; i++){

            var eventName = monthsEvents[i]["name"];
            if(eventName.length > 20){
                eventName = eventName.slice(0, 17) + "...";
            }
            if(monthsEvents[i]["visibility"] > 0){
                eventName += " <i class='fa fa-eye-slash'></i>";
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
    filterTopic();
    document.getElementById("loadingMoreSpinner").style.display = "none";
}


function showEventDetails(eventID, eventName, eventDate, startTime, endTime, address1, postcode, type, topic, fundingSource){
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
        eventDetails += "</em>"
    }

    if(address1){
        eventDetails += "<br/>" + address1;
        if(postcode){
            eventDetails += ", <em>" + postcode + "</em>";
        }
    }
    else if(postcode){
        eventDetails += "<br/><em>" + postcode + "</em>";
    }

    if(type !== "null"){
        eventDetails += "<br/>" + type;
    }
    if(topic !== "null"){
        eventDetails += "<br/>" + topic;
    }
    eventDetails += "<br/><em>" + fundingSource + "</em><br/><br/><button class='server-action' onclick='window.location.href=\"Event.php?id=" + eventID + "\"'>View Event</button>";
    document.getElementById("showEventDetails").innerHTML = eventDetails;
}