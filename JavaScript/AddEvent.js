function checkNoErrors(currentPage, nextPage){
    var errors = checkForErrors();
    
    if(errors.numberOfErrors === 0){
        changePage(currentPage, nextPage, updatePage);
    }
    else{
        updatePage();
    }
}


function updatePage(){
    var errors = checkForErrors();
    var informationStatus = updateProgressBarSymbol(errors);
    var activeSection = getActiveSection();

    if(errors.numberOfErrors > 0){
        document.getElementById('errorOutput').innerHTML = printErrorMessages(errors);
    }
    else{
        document.getElementById('errorOutput').innerHTML = ""; 
    }

    if(informationStatus.requiredInformationProvided === true){
        document.getElementById("formSubmission").style.backgroundColor = "#3c2c59";
    }
    else{
        document.getElementById("formSubmission").style.backgroundColor = "gray";
    }
    
    updateProgressBarColour(activeSection);
}


function getActiveSection(type){
    var activeSection = null;

    if(document.getElementById("primary").style.display != "none"){
        activeSection = 1;
        document.getElementById("sectionName").innerHTML = "Basic Details";
    }
    else if(document.getElementById("location").style.display != "none"){
        activeSection = 2;
        document.getElementById("sectionName").innerHTML = "Location";
    }
    else if(document.getElementById("class").style.display != "none"){
        activeSection = 3;
        document.getElementById("sectionName").innerHTML = "Class Details";
    }
    else if(document.getElementById("ambassadors").style.display != "none"){
        activeSection = 4;
        document.getElementById("sectionName").innerHTML = "Ambassadors";
    }
    else if(document.getElementById("contact").style.display != "none"){
        activeSection = 5;
        document.getElementById("sectionName").innerHTML = "Contact Details";
    }
    else if(document.getElementById("equipment").style.display != "none"){
        activeSection = 6;
        document.getElementById("sectionName").innerHTML = "Equipment";
    }
    else if(document.getElementById("additionalInformation").style.display != "none"){
        activeSection = 7;
        document.getElementById("sectionName").innerHTML = "Additional Information";
    }

    if(type == "string"){
        var dictionary = ["primary", "location", "class", "ambassadors", "contact", "equipment", "additionalInformation"];
        return dictionary[activeSection - 1];
    }
    else{
        return activeSection;
    }
}


function checkForErrors(){

    var errors = {
        numberOfErrors: 0,
        eventName: null,
        fundingSource: null,
        eventType: null,
        postcode: null,
        className: null,
        classSize: null,
        numberOfAmbassadors: null,
        reportLocation: null
    };

    var regExpName = /^\w/;
    if(document.getElementById("eventName")){
        if(document.getElementById("eventName").value == ""){
            errors.numberOfErrors++;
            errors.eventName = "Event name has been left blank";
        }
        else if(regExpName.test(document.getElementById("eventName").value) == false){
            errors.numberOfErrors++;
            errors.eventName = "An invalid event name has been entered";
        }
    }

    if(document.getElementById("fundingSource")){
        if(document.getElementById("fundingSource").value == ""){
            errors.numberOfErrors++;
            errors.fundingSource = "A funding source must be selected";
        }
    }

    if(document.getElementById("eventType")){
        if(document.getElementById("eventType").value == ""){
            errors.numberOfErrors++;
            errors.eventType = "An event type must be selected";
        }
    }

    var regExpPostcode = /^[A-Z]{2}[0-9]{2}\ [0-9][A-Z]{2}$/;
    if(document.getElementById("postcode")){
        if(document.getElementById("postcode").value != ""){
            var postcode = document.getElementById("postcode").value.toUpperCase();
            if(postcode.length === 7){
                postcode = postcode.slice(0, 4) + " " + postcode.slice(4);
            }
            document.getElementById("postcode").value = postcode;
            if(regExpPostcode.test(postcode) == false){
                errors.numberOfErrors++;
                errors.postcode = "An invalid postcode has been entered";          
            }
        }
    }

    if(document.getElementById("className")){
        if(document.getElementById("className").value != "" && regExpName.test(document.getElementById("className").value) == false){
            errors.numberOfErrors++;
            errors.className = "An invalid class name has been entered";
        }
    }

    var regExpClassSize = /(^[1-9]$)|(^[1-9][0-9]$)|(^1[0-9][0-9]$)|(^2[0-4][0-9]$)|(^250$)/;
    if(document.getElementById("classSize")){
        if(document.getElementById("classSize").value != "" && regExpClassSize.test(document.getElementById("classSize").value) == false){
            errors.numberOfErrors++;
            errors.classSize = "An invalid class size has been entered";
        }
    }

    var regExpNoAmbassadors = /(^1[0-9]$)|(^[1-9]$)|(^20$)/;
    if(document.getElementById("numberNeeded")){
        if(document.getElementById("numberNeeded").value != "" && regExpNoAmbassadors.test(document.getElementById("numberNeeded").value) == false){
            errors.numberOfErrors++;
            errors.numberOfAmbassadors = "An invalid number of ambassadors has been entered";
        }
    }

    if(document.getElementById("reportLocation")){
        if(document.getElementById("reportLocation").value != "" && regExpName.test(document.getElementById("reportLocation").value) == false){
            errors.numberOfErrors++;
            errors.reportLocation = "An invalid report location has been entered";
        }
    }

    return errors;
}


function printErrorMessages(errors){
    errorOutput = "<ul>";
    if(errors.eventName !== null){
        errorOutput += "<li>" + errors.eventName + "</li>";
    }
    if(errors.fundingSource !== null){
        errorOutput += "<li>" + errors.fundingSource + "</li>";
    }
    if(errors.eventType !== null){
        errorOutput += "<li>" + errors.eventType + "</li>";
    }
    if(errors.postcode !== null){
        errorOutput += "<li>" + errors.postcode + "</li>";
    }
    if(errors.className !== null){
        errorOutput += "<li>" + errors.className + "</li>";
    }
    if(errors.classSize !== null){
        errorOutput += "<li>" + errors.classSize + "</li>";
    }
    if(errors.numberOfAmbassadors !== null){
        errorOutput += "<li>" + errors.numberOfAmbassadors + "</li>";
    }
    if(errors.reportLocation !== null){
        errorOutput += "<li>" + errors.reportLocation + "</li>";
    }
    errorOutput += "</ul>";
    return errorOutput;
}


function updateProgressBarColour(section){

    if(section >= 2){
        document.getElementById("connector1").style.backgroundColor = "#d3374a";
        document.getElementById("section2").style.color = "#d3374a";
    }
    if(section >= 3){
        document.getElementById("connector2").style.backgroundColor = "#d3374a";
        document.getElementById("section3").style.color = "#d3374a";
    }
    if(section >= 4){
        document.getElementById("connector3").style.backgroundColor = "#d3374a";
        document.getElementById("section4").style.color = "#d3374a";
    }
    if(section >= 5){
        document.getElementById("connector4").style.backgroundColor = "#d3374a";
        document.getElementById("section5").style.color = "#d3374a";
    }
    if(section >= 6){
        document.getElementById("connector5").style.backgroundColor = "#d3374a";
        document.getElementById("section6").style.color = "#d3374a";
    }
    if(section >= 7){
        document.getElementById("connector6").style.backgroundColor = "#d3374a";
        document.getElementById("section7").style.color = "#d3374a";
    }

    if(section <= 6){
        document.getElementById("connector6").style.backgroundColor = "#dcdcdc";
        document.getElementById("section7").style.color = "#dcdcdc";
    }
    if(section <= 5){
        document.getElementById("connector5").style.backgroundColor = "#dcdcdc";
        document.getElementById("section6").style.color = "#dcdcdc";
    }
    if(section <= 4){
        document.getElementById("connector4").style.backgroundColor = "#dcdcdc";
        document.getElementById("section5").style.color = "#dcdcdc";
    }
    if(section <= 3){
        document.getElementById("connector3").style.backgroundColor = "#dcdcdc";
        document.getElementById("section4").style.color = "#dcdcdc";
    }
    if(section <= 2){
        document.getElementById("connector2").style.backgroundColor = "#dcdcdc";
        document.getElementById("section3").style.color = "#dcdcdc";
    }
    if(section <= 1){
        document.getElementById("connector1").style.backgroundColor = "#dcdcdc";
        document.getElementById("section2").style.color = "#dcdcdc";
    }
    
}


function updateProgressBarSymbol(errors){
    var informationStatus = {
        requiredInformationProvided: true,
        preferredInformationProvided: true
    };

    //Check primary details correct
    if(errors.eventName !== null || errors.fundingSource !== null){
        document.getElementById("section1").className = "fa fa-exclamation-circle";
    }
    else if(
        document.getElementById("eventName").value &&
        document.getElementById("fundingSource").value &&
        document.getElementById("eventDate").value &&
        document.getElementById("startTime").value &&
        document.getElementById("endTime").value &&
        document.getElementById("eventType").value
    ){
        document.getElementById("section1").className = "fa fa-circle";
    }
    else{
        document.getElementById("section1").className = "fa fa-circle-thin";
        informationStatus.requiredInformationProvided = false;
    }


    //Check event address complete
    if(errors.postcode !== null){
        document.getElementById("section2").className = "fa fa-exclamation-circle";
    }
    else if(
        document.getElementById("address1").value &&
        document.getElementById("postcode").value &&
        document.getElementById("transport").value 
    ){
        if(document.getElementById("county").value){
            document.getElementById("section2").className = "fa fa-circle"; 
        }
        else{
            document.getElementById("section2").className = "fa fa-circle-thin";
            informationStatus.preferredInformationProvided = false;
        } 
    }
    else{
        document.getElementById("section2").className = "fa fa-circle-thin";
        informationStatus.requiredInformationProvided = false;
    }


    //Check class details complete
    if(errors.className !== null || errors.classSize !== null){
        document.getElementById("section3").className = "fa fa-exclamation-circle";
    }
    else{
        if(isCardiffEvent(document.getElementById("eventType").value) === false){

            if(
                document.getElementById("level").value &&
                document.querySelectorAll('input[type="checkbox"]:checked').length > 0
            ){     
                document.getElementById("section3").className = "fa fa-circle";
            }
            else{
                document.getElementById("section3").className = "fa fa-circle-thin";
                informationStatus.requiredInformationProvided = false;
            } 
        }  
    }


    //Check ambassador details complete
    if(errors.numberOfAmbassadors !== null || errors.reportLocation !== null){
        document.getElementById("section4").className = "fa fa-exclamation-circle";
    }
    else if(
        document.getElementById("numberNeeded").value >= 1 &&
        document.getElementById("reportLocation").value 
    ){
        if(document.getElementById("leadAmbassador").value){
            document.getElementById("section4").className = "fa fa-circle";   
        }
        else{
            informationStatus.preferredInformationProvided = false;
        }
    }
    else{
        document.getElementById("section4").className = "fa fa-circle-thin";
        informationStatus.requiredInformationProvided = false;
    }


    //Check contact details complete
    if(
        ( isCardiffEvent(document.getElementById("eventType").value) === true) || (
            document.getElementById("contactName").value && (
                document.getElementById("contactEmail").value ||
                document.getElementById("contactPhoneNo").value
            )
        )
    ){
        document.getElementById("section5").className = "fa fa-circle";   
    }
    else{
        document.getElementById("section5").className = "fa fa-circle-thin";
        informationStatus.requiredInformationProvided = false;
    }


    if(informationStatus.requiredInformationProvided === true){
        populateVisibilityOptions(true);
    }
    else{
        populateVisibilityOptions(false);
    }

    return informationStatus;

}


function eventTypeUpdates(){
    var selection = document.getElementById("eventType").value;
    updateAddress(selection);
    populateLevelOptions(selection);
    removeTrainingRequirement(selection)
}


function updateAddress(selection){
    if(isCardiffEvent(selection) === true){
        var address1 = document.getElementById("address1");
        var address2 = document.getElementById("address2");
        var county = document.getElementById("county");
        var postcode = document.getElementById("postcode");

        address1.value = "Queen's Building";
        address2.value = "Cardiff University";
        county.value = "Cardiff";
        postcode.value = "CF24 3AA";
    }
}


function removeTrainingRequirement(selection){
    var radioButton = document.getElementsByName("trainingRequired")[2];
    if(isCardiffEvent(selection) === true){
        $('#trainingRequiredRow').hide();
        radioButton.checked = true;
    }
    else{
        $('#trainingRequiredRow').show();
    }
}


function isCardiffEvent(selection){
    if(selection === "CU Open Day" || selection === "CU UCAS Day" || selection === "Networking Event"){
        return true;
    }
    else{
        return false;
    }
}


function populateLevelOptions(selection){
    var level = document.getElementById("level");

    if(selection === "Primary School"){
        level.disabled = false;
        level.innerHTML = '<option value="">---</option><option value="KS1">KS1</option><option value="KS2 (Basic)">KS2 (Basic)</option><option value="KS2 (Advanced)">KS2 (Advanced)</option>';
        level.style.backgroundColor = "white";
    }
    else if(selection === "Secondary School"){
        level.disabled = false;
        level.innerHTML = '<option value="">---</option><option value="KS3">KS3</option><option value="KS4">KS4</option><option value="KS5">KS5</option>';
        level.style.backgroundColor = "white";
    }
    else if(selection === "College"){
        level.disabled = false;
        level.innerHTML = '<option value="">---</option><option value="Level 2">Level 2</option><option value="Level 2">Level 3</option><option value="Level 4">Level 4</option><option value="Level 5">Level 5</option>';
        level.style.backgroundColor = "white";
    }
    else if(selection === "CPD"){
        level.disabled = false;
        level.innerHTML = '<option value="">---</option><option value="KS2 Basic">KS2 (Basic)</option><option value="KS2 (Advanced)">KS2 (Advanced)</option><option value="KS3">KS3</option><option value="KS4">KS4</option><option value="KS5">KS5</option><option value="lLevel ">Level 2</option><option value="Level 3">Level 3</option><option value="Level 4">Level 4</option><option value="Level 5">Level 5</option>';
        level.style.backgroundColor = "white";
    }
    else if(selection === "Other"){
        level.disabled = false;
        level.innerHTML = '<option value="">---</option><option value="KS2 (Basic)">KS2 (Basic)</option><option value="KS2 (Advanced)">KS2 (Advanced)</option><option value="KS3">KS3</option><option value="KS4">KS4</option><option value="KS5">KS5</option><option value="Level 2">Level 2</option><option value="Level 3">Level 3</option><option value="Level 4">Level 4</option><option value="Level 5">Level 5</option>';
        level.style.backgroundColor = "white";
    }
    else{
        level.disabled = true;
        level.innerHTML = '';
        level.style.backgroundColor = "lightgray";
    }

    level.selectedIndex = -1;
}


function updateNoAmbassadors(value){
    var returnValue = 2;
    if(value >= 40){
       returnValue = Math.round( (value) / 10 );
    }
    else if(value > 30){
        returnValue = 3;
    }
    else if(value == 0){
        returnValue = 0;
    }
    document.getElementById('numberNeeded').value = returnValue;
}


function populateVisibilityOptions(includeAmbassadors){
    var options = '<option value="2">Admins Only</option>';

    if(includeAmbassadors === true){
        options += '<option value="1">Admins &amp; Lead Ambassadors</option>';
        options += '<option value="0">Everyone</option>';
    }

    document.getElementById("visibility").innerHTML = options;
}


function showNumberOfRepeats(){
    if(document.getElementById("repeatFrequency").value == ""){
        $('#numberOfEventsRow').hide();
    }
    else{
        $('#numberOfEventsRow').show();
    }
}


function validateSubmit(){
    var errors = checkForErrors();
    var informationStatus = updateProgressBarSymbol(errors);
    var submit = true;
    var form = document.getElementById("addEventForm");

    if(errors.numberOfErrors === 0){
        if(informationStatus.requiredInformationProvided === false){
            submit = confirm("There is important information missing, are you sure you wish to create this event?");
        }
        else if(informationStatus.preferredInformationProvided === false){
            submit = confirm("There is some missing information, are you sure you wish to create this event?");
        }
        if(submit == true){
            form.action = phpFormPath;
            form.submit();
        }
    }
    else{
        alert("Errors must be correct before you can create this event");
    }
}