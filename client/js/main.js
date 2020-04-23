var chat = document.getElementById('chat');
chat.scrollTop = chat.scrollHeight - chat.clientHeight;

var username;

function init() {
    var cookieUsername = getCookie("username");
    console.log("cookie username is: " + cookieUsername);

    if (cookieUsername != "") {
        username = cookieUsername;
        displayChat();
    }
}

function login(event) {
    // I don't want this page to be submitted.
    event.preventDefault();

    // Get current login ID.
    username = document.querySelector("#loginName").value;
    setCookie("username", username, 1);

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