function Login(){
    var radio=document.getElementsByName('tab');
    var value;
    for(var i of radio) {
        if(i.checked){
            value=i.value;
        }
    }
    if(value =='Student'){
        window.location.replace("./StudentPage.html");
    }
    else if(value=='Instructor'){
        window.location.replace("./SecretaryPage.html");
    }
    else if(value=='Secretary') {
        window.location.replace("./SecretaryPage.html");
    }
    
}
function redirect(){
    window.location.replace("./StudentPage.html");
}