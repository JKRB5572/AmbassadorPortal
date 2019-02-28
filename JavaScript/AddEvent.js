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
    var primary = document.getElementById("primary");
    var workshop = document.getElementById("workshop");
    var ambassadors = document.getElementById("ambassadors");
    var location = document.getElementById("location");
    var contact = document.getElementById("contact");
    var equipment = document.getElementById("equipment");
    var additionalInformation = document.getElementById("additionalInformation");

    var sectionName = document.getElementById("sectionName");


    if(primary){
        if(primary.style.display != "none"){
            sectionName.innerHTML = "Basic Details";
            return 1;
        }
    }
    if(workshop){
        if(workshop.style.display != "none"){
            sectionName.innerHTML = "Workshop Details";
            return 2;
        }
    }
    if(ambassadors){
        if(ambassadors.style.display != "none"){
            sectionName.innerHTML = "Ambassadors";
            return 3;
        }
    }
    if(location){
        if(location.style.display != "none"){
            sectionName.innerHTML = "Location";
            return 4;
        }
    }
    if(contact){
        if(contact.style.display != "none"){
            sectionName.innerHTML = "Contact Details";
            return 5;
        }
    }
    if(equipment){
        if(equipment.style.display != "none"){
            sectionName.innerHTML = "Equipment";
            return 6;
        }
    }
    if(additionalInformation){
        if(additionalInformation.style.display != "none"){
            sectionName.innerHTML = "Additional Information";
            return 7;
        }
    }

    return -1;

}


function getActiveSectionAsString(){
    var activeSection = getActiveSection();

    if(activeSection != -1){
        var dictionary = ["primary", "workshop", "ambassadors", "location", "contact", "equipment", "additionalInformation"];
        return dictionary[activeSection - 1];
    }
}


function checkForErrors(){

    var errors = {
        numberOfErrors: 0,
        eventName: null,
        fundingSource: null,
        eventType: null,
        numberOfParticipants: null,
        yearGroup: null,
        numberOfAmbassadors: null,
        reportLocation: null,
        postcode: null
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


    var regExpNumberOfParticipants = /(^[1-9]$)|(^[1-9][0-9]$)|(^[1-4][0-9][0-9]$)|(^500$)/;
    if(document.getElementById("numberOfParticipants")){
        if(document.getElementById("numberOfParticipants").value != "" && regExpNumberOfParticipants.test(document.getElementById("numberOfParticipants").value) == false){
            errors.numberOfErrors++;
            errors.numberOfParticipants = "An invalid number of participants has been entered";
        }
    }

    if(getActiveSection() >= 3){
        if(document.getElementById("eventType").value == "Primary School"){
            if($('#yearGroupPrimarySchool').find(':checked').length == 0){
                errors.numberOfErrors++;
                errors.yearGroup = "No year group has been selected";
                changePage(getActiveSectionAsString(), "workshop");
            }
        }
        else if(document.getElementById("eventType").value == "Secondary School"){
            if($('#yearGroupSecondarySchool').find(':checked').length == 0){
                errors.numberOfErrors++;
                errors.yearGroup = "No year group has been selected";
                changePage(getActiveSectionAsString(), "workshop");
            }
        }

    }


    var regExpNoAmbassadors = /(^[1-9]$)|(^[1-4][0-9]$)|(^50$)/;
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
    if(errors.numberOfParticipants !== null){
        errorOutput += "<li>" + errors.numberOfParticipants + "</li>";
    }
    if(errors.yearGroup !== null){
        errorOutput += "<li>" + errors.yearGroup + "</li>";
    }
    if(errors.numberOfAmbassadors !== null){
        errorOutput += "<li>" + errors.numberOfAmbassadors + "</li>";
    }
    if(errors.reportLocation !== null){
        errorOutput += "<li>" + errors.reportLocation + "</li>";
    }
    if(errors.postcode !== null){
        errorOutput += "<li>" + errors.postcode + "</li>";
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


    //Check workshop details complete
    if(errors.yearGroup !== null || errors.numberOfParticipants !== null){
        document.getElementById("section2").className = "fa fa-exclamation-circle";
    }
    else{
        if(isCardiffEvent(document.getElementById("eventType").value) === false){
            if($('#eventTopics').find(':checked').length > 0){    
                document.getElementById("section2").className = "fa fa-circle";
            }
            else{
                document.getElementById("section2").className = "fa fa-circle-thin";
                informationStatus.requiredInformationProvided = false;
            }
        }
        else{
            document.getElementById("section2").className = "fa fa-circle-thin";
            informationStatus.requiredInformationProvided = false;
        }
    }


    //Check ambassador details complete
    if(errors.numberOfAmbassadors !== null || errors.reportLocation !== null){
        document.getElementById("section3").className = "fa fa-exclamation-circle";
    }
    else if(
        document.getElementById("numberNeeded").value >= 1 &&
        document.getElementById("reportLocation").value 
    ){
        if(document.getElementById("leadAmbassador").value){
            document.getElementById("section3").className = "fa fa-circle";   
        }
        else{
            informationStatus.preferredInformationProvided = false;
        }
    }
    else{
        document.getElementById("section3").className = "fa fa-circle-thin";
        informationStatus.requiredInformationProvided = false;
    }


    //Check event address complete
    if(errors.postcode !== null){
        document.getElementById("section4").className = "fa fa-exclamation-circle";
    }
    else if(
        ( document.getElementById("address1").value || document.getElementById("postcode").value ) &&
        document.getElementById("transport").value 
    ){
        if(
            document.getElementById("address1").value &&
            document.getElementById("postcode").value &&
            document.getElementById("county").value
        ){
            document.getElementById("section4").className = "fa fa-circle"; 
        }
        else{
            document.getElementById("section4").className = "fa fa-circle-thin";
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
    toggleYearGroup(selection);
    removeTrainingRequirement(selection);
}


function updateAddress(selection){
    var address1 = document.getElementById("address1");
    var address2 = document.getElementById("address2");
    var county = document.getElementById("county");
    var postcode = document.getElementById("postcode");

    if(isCardiffEvent(selection) === true){
        address1.value = "Queen's Building";
        address2.value = "Cardiff University";
        county.value = "Cardiff";
        postcode.value = "CF24 3AA";
    }
    else if(address1.value == "Queen's Building" && address2.value == "Cardiff University" && county.value == "Cardiff" && postcode.value == "CF24 3AA"){
        address1.value = "";
        address2.value = "";
        county.value = "";
        postcode.value = "";
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


function toggleYearGroup(selection){

    if(selection === "Primary School"){
        $('#yearGroupRow').show();
        $('#yearGroupPrimarySchool').show();
        $('#yearGroupSecondarySchool').hide();
        $('#levelRow').hide();
    }
    else if(selection === "Secondary School"){
        $('#yearGroupRow').show();
        $('#yearGroupPrimarySchool').hide();
        $('#yearGroupSecondarySchool').show();
        $('#levelRow').hide();
    }
    else if(selection === "CPD" || selection === "College"){
        $('#yearGroupRow').hide();
        $('#yearGroupPrimarySchool').hide();
        $('#yearGroupSecondarySchool').hide();
        $('#levelRow').show();
    }
    else{
        $('#yearGroupRow').hide();
        $('#yearGroupPrimarySchool').hide();
        $('#yearGroupSecondarySchool').hide();
        $('#levelRow').hide();
    }

    if(selection !== "Primary School"){
        document.getElementById("fpCheckbox").checked = false;
        document.getElementById("yr1Checkbox").checked = false;
        document.getElementById("yr2Checkbox").checked = false;
        document.getElementById("yr3Checkbox").checked = false;
        document.getElementById("yr4Checkbox").checked = false;
        document.getElementById("yr5Checkbox").checked = false;
        document.getElementById("yr6Checkbox").checked = false;
    }
    if(selection !== "Secondary School"){
        document.getElementById("yr7Checkbox").checked = false;
        document.getElementById("yr8Checkbox").checked = false;
        document.getElementById("yr9Checkbox").checked = false;
        document.getElementById("yr10Checkbox").checked = false;
        document.getElementById("yr11Checkbox").checked = false;
        document.getElementById("yr12Checkbox").checked = false;
        document.getElementById("yr13Checkbox").checked = false;
    }
    if(selection !== "CPD" && selection !== "CPD"){
        document.getElementById("level").value = "";
    }
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