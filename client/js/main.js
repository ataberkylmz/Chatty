var chat = document.getElementById('chat');
chat.scrollTop = chat.scrollHeight - chat.clientHeight;

var username;
var receier;

function init() {
    var cookieUsername = getCookie("username");
    console.log("cookie username is: " + cookieUsername);

    if (cookieUsername != "") {
        username = cookieUsername;
        displayChat();
    }
}

function getRequest(url, data, callback) {
    const xhr = new XMLHttpRequest();
    xhr.onreadystatechange = () => {
        if (callback && xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 400 || xhr.status === 500) {
                return;
            }
            console.log(xhr.responseText);
            callback(JSON.parse(xhr.responseText));
        }
    };

    xhr.open('GET', url, true);
    // set `Content-Type` header

    xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
    xhr.setRequestHeader('Access-Control-Allow-Headers', 'Content-Type, origin');
    xhr.setRequestHeader('Content-Type', 'application/json');

    // pass `params` to `send()` method
    xhr.send(JSON.stringify(data));
}

function postRequest(url, data, callback) {

}


function login(event) {
    // I don't want this page to be submitted.
    event.preventDefault();

    // Get current login ID.
    username = document.querySelector("#loginName").value;


    setCookie("username", username, 1);
    getRequest("http://127.0.0.1:8000/api/v1/user/read.php", { "username": username }, (response) => {
        if (response.status === 'error') {
            console.log(response);
            return;
        }
    });

    displayChat();

}

function logout(event) {
    document.cookie = "username=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
}

function displayChat() {
    // Hide the login panel.
    var loginDialog = document.querySelector(".login");
    loginDialog.hidden = true;

    // Update username.
    var usernameField = document.querySelector("#username");
    usernameField.innerHTML = username;

    // Show sidebar and chat regions.
    var sideBar = document.querySelector(".contacts");
    sideBar.hidden = false;
    var chat = document.querySelector(".chat");
    chat.hidden = false;
}

function hideChat() {
    // Show the login panel.
    var loginDialog = document.querySelector(".login");
    loginDialog.hidden = false;

    // Show sidebar and chat regions.
    var sideBar = document.querySelector(".contacts");
    sideBar.hidden = true;
    var chat = document.querySelector(".chat");
    chat.hidden = true;
}

function newChat(event) {
    event.preventDefault();
}

function sendMessage(event) {
    event.preventDefault();
}

function selectReceiver(event) {
    receier = event.currentTarget.querySelector(".name").innerText;
    console.log(receier);
}

function updateChat(rec) {

}

function setCookie(cookieName, cookieValue, expireDay) {
    var d = new Date();
    d.setTime(d.getTime() + (expireDay * 24 * 60 * 60 * 1000));
    var expires = "expires=" + d.toUTCString();
    document.cookie = cookieName + "=" + cookieValue + ";" + expires + ";path=/";
}


function getCookie(cookieName) {
    var name = cookieName + "=";
    var cookieArray = document.cookie.split(';');
    for (var i = 0; i < cookieArray.length; i++) {
        var cookieSingle = cookieArray[i];
        while (cookieSingle.charAt(0) == ' ') {
            cookieSingle = cookieSingle.substring(1);
        }
        if (cookieSingle.indexOf(name) == 0) {
            return cookieSingle.substring(name.length, cookieSingle.length);
        }
    }
    return "";
}