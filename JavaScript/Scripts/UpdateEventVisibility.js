function updateVisibility(eventID, requestedValue){
    var initialValue = -1;
    var target;

    //Determine initial value
    if(document.getElementById("eventHide").classList.contains("active")){
        initialValue = 2;
        target = document.getElementById("eventHide");
    }
    else if(document.getElementById("eventShowAdmin").classList.contains("active")){
        initialValue = 1;
        target = document.getElementById("eventShowAdmin");
    }
    else if(document.getElementById("eventShowAll").classList.contains("active")){
        initialValue = 0;
        target = document.getElementById("eventShowAll");
    }

    if(initialValue != requestedValue){

        if(initialValue != -1){
            //Send Request
            var xmlhttp = new XMLHttpRequest();

            xmlhttp.onreadystatechange = function() {
                if(this.readyState == 4 && this.status == 200){

                    updatedValue = this.responseText;
                    console.log(updatedValue);
                    if(updatedValue != requestedValue){
                        alert("Unable to complete operation at this time.");
                    }
                    else{

                        //Update active class
                        target.classList.remove("active");
                        if(requestedValue == 2){
                            document.getElementById("eventHide").classList.add("active");
                        }
                        else if(requestedValue == 1){
                            document.getElementById("eventShowAdmin").classList.add("active");
                        }
                        else{
                            document.getElementById("eventShowAll").classList.add("active");
                        }

                    }
                }
            };

            xmlhttp.open("POST", "/PHP/Scripts/UpdateEventVisibility.php", true);
            xmlhttp.setRequestHeader("Content-type",  "application/x-www-form-urlencoded");
            xmlhttp.send("id=" + eventID + "&value=" + requestedValue);
        }
        else{
            alert("A system error has occured whilst determining values. Please seek help from the system adminstrator and quote the following error code: 9-J-UEV-1.");
        }

    }
}
