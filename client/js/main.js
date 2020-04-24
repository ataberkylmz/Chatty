const server_address = "http://134.122.123.243/Chatty/server";

var username;
var receiver;

function init() {
    const cookieUsername = getCookie("username");

    if (cookieUsername != "") {
        username = cookieUsername;
        displayChat();
    }
}

function objToUrl(obj) {
    return Object.keys(obj).map(x => `${x}=${encodeURI(obj[x])}`).join('&');
}

function getRequest(url, data, callback) {
    fetch(url + '?' + objToUrl(data), {
            method: 'GET',
            mode: 'same-origin',
            redirect: 'manual'
        }).then((response) => response.json())
        .then((data) => callback(data))
        .catch((error) => {
            console.error('Error:', error);
        });
}

function postRequest(url, data, callback) {
    fetch(url, {
            method: 'POST',
            mode: 'same-origin',
            headers: {
                'Content-Type': 'application/json',
            },
            redirect: 'manual',
            body: JSON.stringify(data),
        }).then((response) => response.json())
        .then((data) => callback(data))
        .catch((error) => {
            console.error('Error:', error);
        });
}

function login(event) {
    // I don't want this page to be submitted.
    event.preventDefault();

    // Get current login ID.
    username = document.querySelector("#loginName").value;
    setCookie("username", username, 1);

    getRequest(server_address + "/api/v1/user/read.php", { "username": username }, (response) => {
        if (response.code !== 1) {
            postRequest(server_address + "/api/v1/user/create.php", { "username": username });
        }
        return;
    });

    displayChat();
    updateChatList();
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

    // Show sidebar and chatlist regions.
    var sideBar = document.querySelector(".contacts");
    sideBar.hidden = false;
    var chat = document.querySelector(".chat");
    chat.hidden = false;
}

function hideChat() {
    // Show the login panel.
    var loginDialog = document.querySelector(".login");
    loginDialog.hidden = false;

    // Show sidebar and chatlist regions.
    var sideBar = document.querySelector(".contacts");
    sideBar.hidden = true;
    var chat = document.querySelector(".chat");
    chat.hidden = true;
}

function createNewChat(event) {
    event.preventDefault();

    try {
        var newReceiver = document.querySelector("#newChatElement").value;
        if (newReceiver === username)
            throw new Error('You cannot send messages to yourself.');
        getRequest(server_address + "/api/v1/user/read.php", { "username": newReceiver }, (response) => {
            if (response.code === 1) {
                document.querySelector(".bar div.name").innerText = newReceiver;
                updateChatList();
            } else {
                window.alert("User does not exists!");
            }
        });
    } catch (err) {
        console.log(err);
    }
}

function sendMessage(event) {
    event.preventDefault();

    receiver = document.querySelector(".bar div.name").innerText;
    var message = document.querySelector("#sendMessageInput").value;
    if (message !== "") {

        const data = {
            "sender": username,
            "receiver": receiver,
            "body": message
        }

        postRequest(server_address + "/api/v1/message/create.php",
            data,
            (res) => {
                updateChat(receiver);
                updateChatList();
                var chatArea = document.querySelector('#chat')
                chatArea.scrollTop = chatArea.scrollHeight;
            })

        document.querySelector("#sendMessageInput").value = "";
    }
}

function selectReceiver(event) {
    receiver = event.currentTarget.querySelector(".name").innerText;
    var receiverAvatar = event.currentTarget.querySelector(".pic");
    document.querySelector(".bar div.name").innerText = receiver;
    document.querySelector(".bar div.pic").outerHTML = receiverAvatar.outerHTML;

    updateChat(receiver);
}

function updateChatList() {
    getRequest(server_address + "/api/v1/chatlist/read.php", { "sender": username }, (response) => {
        if (response.data !== undefined) {
            const conversations = Object.keys(response.data).map(x => (`
            <div class="contact" onclick="selectReceiver(event)">
            <div class="${pics[Math.floor(Math.random() * pics.length)]}"></div>
            <div class="name">
                ${response.data[x]}
            </div>
            <div class="message">
                Place holder :(
            </div>
        </div>`));

            document.querySelector('#contactList').innerHTML = conversations.join('\n');
        }

        return;
    });
}

function updateChat(receiver) {
    getRequest(server_address + "/api/v1/messages/read.php", { "sender": username, "receiver": receiver }, (response) => {
        if (response.data !== undefined) {
            const conversations = Object.keys(response.data).map(x => (`
        <div class="message${response.data[x]["sender"] === username ? " sender" : ""}">
            ${response.data[x]["body"]}
        </div>`));

            var chatArea = document.querySelector('#chat')
            chatArea.innerHTML = conversations.join('\n');
            chatArea.scrollTop = chatArea.scrollHeight;
        }
        return;
    });
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