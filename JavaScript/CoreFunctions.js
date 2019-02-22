/*
Description: -
Function that redirects to a supplied url.

Parameters: -
url: The url that you wish to redirect to.
*/
function redirect(url){
    window.location.href = url;
}


/*
Description: -
    Function that returns the verbose version of a date in html form(I.E. 1<sup>st</sup> September 2018)

Parameters: -
    Numeric date supplied in the format YYYY-MM-DD
*/

function verboseDateHTML(numericDate){
    var date = String(numericDate).split("-");
    
    var year = date[0];
    var month = date[1];
    var day = date[2];
    var returnDate;

    //Determine day
    if(day.slice(0, 1) === "0"){
        day = day.slice(-1); 
    }
    if(day === "11" || day === "12" || day === "13"){
        returnDate = day + "<sup>th</sup>";
    }
    else if(day.slice(-1) === "1"){
        returnDate = day + "<sup>st</sup>";
    }
    else if(day.slice(-1) === "2"){
        returnDate = day + "<sup>nd</sup>";
    }
    else if(day.slice(-1) === "3"){
        returnDate = day + "<sup>rd</sup>";
    }
    else{
        returnDate = day + "<sup>th</sup>";
    }

    //Determine month and add year
    returnDate += " " + monthName(month - 1);
    returnDate += " " + year;

    return returnDate
}


function dateStringJS(numericDate){
    var date = String(numericDate).split("-");
    return date[1] + "-" + date[2] + "-" + date[0];
}


/*
Description: -
    Function that returns the name equivlant of a numerical month

Parameters: -
    month: Numerical representation of the month for which the name equivilant will be returned
*/
function monthName(month){
    var monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
    return monthNames[parseInt(month)];
}


function changePage(currentPageID, targetPageID, callbackFunction){
    document.getElementById(currentPageID).style.display = "none";
    document.getElementById(targetPageID).style.display = "block";

    if(callbackFunction){
        callbackFunction();
    }
}



function stripQuotationMarks(string){
    string = string.replace("'", "");
    string = string.replace('"', '');
    return string;
}



function deleteNotificationAmbassador(id){
    var xmlhttp = new XMLHttpRequest();
    var notificationsIcon = document.getElementsByClassName("fa-bell")[0];

    xmlhttp.onreadystatechange = function() {
        if(this.readyState == 4 && this.status == 200){
            document.getElementById(id).style.display = "none";
            notificationsIcon.dataset.notifications = notificationsIcon.dataset.notifications - 1;
        }
    }

    xmlhttp.open("POST", "/PHP/Scripts/DeleteNotification.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("id=" + id + "&table=ambassador");
}


function deleteNotificationAdmin(id){
    var xmlhttp = new XMLHttpRequest();
    var notificationsIcon = document.getElementsByClassName("fa-bell")[0];

    xmlhttp.onreadystatechange = function() {
        if(this.readyState == 4 && this.status == 200){
            document.getElementById(id).style.display = "none";
            notificationsIcon.dataset.notifications = notificationsIcon.dataset.notifications - 1;
        }
    }

    xmlhttp.open("POST", "/PHP/Scripts/DeleteNotification.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("id=" + id + "&table=admin");
}