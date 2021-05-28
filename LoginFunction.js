function Login(){
    var radio=document.getElementsByName('tab');
    var value;
    for(var i of radio) {
        if(i.checked){
            value=i.value;
        }
    }
    if(value =='student'){
        window.location.replace("./StudentPage.html");
    }
    else if(value=='instructor'){
        window.location.replace("./InstructorPage.html");
    }
    else if(value=='secretary') {
        window.location.replace("./SecretaryPage.html");
    }
    
}
function signin(){
    var radio=document.getElementById('logntype');
    if(radio.value =='student'){
        window.location.replace("./StudentPage.html");
    }
    else if(radio.value=='instructor'){
        window.location.replace("./InstructorPage.html");
    }
    else if(radio.value=='secretary') {
        window.location.replace("./SecretaryPage.html");
    }
}
