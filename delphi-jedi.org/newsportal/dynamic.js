function dynamicload(elem,request,ondone) {
  // create the request object
  if (window.XMLHttpRequest) {
    xhr = new XMLHttpRequest(); //  Firefox, Safari, ...
  } else
  if (window.ActiveXObject) {
    xhr = new ActiveXObject("Microsoft.XMLHTTP"); //  Internet Explorer
  } else
    throw "no http request backend";
      
  elem.innerHTML = "Connecting...";

  // request callback
  xhr.onreadystatechange = function() {
    if (xhr.readyState == 1) {
      elem.innerHTML = "Sending the request...";
    } else
    if (xhr.readyState == 2) {
      elem.innerHTML = "Waiting for the server...";
    } else
    if (xhr.readyState == 3) {
      elem.innerHTML = "Receiving data...";
    } else
    if (xhr.readyState  == 4) {
      if (xhr.status  == 200) {
        elem.innerHTML = xhr.responseText;
        ondone();
      } else {
        elem.innerHTML = "Error code " + xhr.status;
      }
    }
  }
      
  // do the request
  xhr.open( "GET", request, true);
  xhr.send(null);

  return false;
}

function showmessage(article,id,group) {
  dynmsg = document.getElementById("msg"+id);
  
  if (dynmsg.style.display == "block") {
    dynmsg.style.display = "none";
  } else {
    dynmsg.style.display = "block";

    ondone = function (){};
    
    if (dynmsg.innerHTML == "")
      dynamicload(dynmsg,"/"+article+"?dyn=true&group="+group+"&id="+id,ondone); 
  }
  return false;
}

function showthread(thread,first,last,group) {
  bottom = document.getElementById("bottom");
  bottom.id = "";
  
  ondone = function (){};

  dynamicload(bottom, "/"+thread+"?dyn=true&group="+group+"&first="+first+"&last="+last,ondone);
  
  return false;
}

function showanswer(post,id,group) {
  reply = document.getElementById("rpl"+id);
  
  ondone = function () {
    cap = document.getElementById("cap"+id);
    
    public_key = cap.innerHTML;
    cap.innerHTML = "Requesting captcha...";
  
    Recaptcha.create(public_key,cap.id,
      {
        theme: "white",
        callback: Recaptcha.focus_response_field
      }
    );
  }

  dynamicload(reply, "/"+post+"?dyn=true&type=reply&group="+group+"&id="+id,ondone);

  return false;
}