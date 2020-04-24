const server_address = "http://134.122.123.243/Chatty/server";

let username;
let receiver;

function init() {
    // Check if a cookie exists
    const cookieUsername = getCookie("username");

    // If so, use the same username.
    if (cookieUsername != "") {
        username = cookieUsername;
        updateChatList();
        displayChat();
    }
}

// Used in getRequest. Since I cannot make a GET request with a body, I used url parameters.
function objToUrl(obj) {
    return Object.keys(obj).map(x => `${x}=${encodeURI(obj[x])}`).join('&');
}

/**
 * WARNING: 'same-origin' is used in mode, might be faulty if php server and http server are running
 * on different ports? not really ideal for local development :( I HATE CROSS ORIGIN RESTRICTIONS.
 * */
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

/**
 * WARNING: 'same-origin' is used in mode, might be faulty if php server and http server are running
 * on different ports? not really ideal for local development :( I HATE CROSS ORIGIN RESTRICTIONS.
 * */
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

/**
 * Login function
 * @param event To prevent page from actually submitting and refreshing.
 */
function login(event) {
    // I don't want this page to be submitted.
    event.preventDefault();

    // Get current login ID.
    username = document.querySelector("#loginName").value;
    setCookie("username", username, 1);

    // Check if a user already exists in database.
    getRequest(server_address + "/api/v1/user/read.php", { "username": username }, (response) => {
        if (response.code !== 1) {
            // If no user found with that name, create one.
            postRequest(server_address + "/api/v1/user/create.php", { "username": username });
        }
        return;
    });

    // Display chat area and sidebar, then update contact list.
    displayChat();
    updateChatList();
}

// Logout function, basically remove cookie and refresh.
function logout() {
    document.cookie = "username=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
}

function displayChat() {
    // Hide the login panel.
    let loginDialog = document.querySelector(".login");
    loginDialog.hidden = true;

    // Update username.
    let usernameField = document.querySelector("#username");
    usernameField.innerHTML = username;

    // Show sidebar and chatlist regions.
    let sideBar = document.querySelector(".contacts");
    sideBar.hidden = false;
    let chat = document.querySelector(".chat");
    chat.hidden = false;
}

// Yeah, exists but there is literally no use :/ Might be useful later on.
function hideChat() {
    // Show the login panel.
    let loginDialog = document.querySelector(".login");
    loginDialog.hidden = false;

    // Show sidebar and chatlist regions.
    let sideBar = document.querySelector(".contacts");
    sideBar.hidden = true;
    let chat = document.querySelector(".chat");
    chat.hidden = true;
}

/**
 * Used to initiate a new conversation.
 * @param event To prevent page from actually submitting and refreshing.
 */
function createNewChat(event) {
    event.preventDefault();

    try {
        // Get the receiver name.
        let newReceiver = document.querySelector("#newChatElement").value;
        // Check if they are same as the current user.
        if (newReceiver === username)
            throw new Error('You cannot send messages to yourself.');

        // Then, check if the receiver exists or not.
        getRequest(server_address + "/api/v1/user/read.php", { "username": newReceiver }, (response) => {
            if (response.code === 1) {
                // if it exists, update name
                receiver = newReceiver;
                document.querySelector(".bar div.name").innerText = receiver;
                updateChatList();
            } else {
                window.alert("User does not exists!");
            }
        });
    } catch (err) {
        console.log(err);
    }
}

/**
 * Send message to receiver
 * @param event To prevent page from actually submitting and refreshing.
 */
function sendMessage(event) {
    event.preventDefault();

    // Get the receiver name from top.
    receiver = document.querySelector(".bar div.name").innerText;
    let message = document.querySelector("#sendMessageInput").value;

    if (message !== "") {
        const data = {
            "sender": username,
            "receiver": receiver,
            "body": message
        }

        postRequest(server_address + "/api/v1/message/create.php",
            data,
            (res) => {
                // If successful, update the chat log
                updateChat(receiver);
                // Update the chat list on sidebar.
                updateChatList();
                // Scroll to bottom of chat.
                let chatArea = document.querySelector('#chat')
                chatArea.scrollTop = chatArea.scrollHeight;
            })

        // Delete the message from input field.
        document.querySelector("#sendMessageInput").value = "";
    }
}

/**
 * Selects receiver from sidebar (chat list?).
 * @param event
 */
function selectReceiver(event) {
    // Get name and picture (which is totally random)
    receiver = event.currentTarget.querySelector(".name").innerText;
    let receiverAvatar = event.currentTarget.querySelector(".pic");
    // Update the name and pic on top.
    document.querySelector(".bar div.name").innerText = receiver;
    document.querySelector(".bar div.pic").outerHTML = receiverAvatar.outerHTML;

    // Load chat history.
    updateChat(receiver);
}

/**
 * Update chat list on sidebar
 */
function updateChatList() {
    getRequest(server_address + "/api/v1/chatlist/read.php", { "sender": username }, (response) => {
        if (response.data !== undefined) {
            /** @var response.data is an array of names, map over it and update html */
            const conversations = Object.keys(response.data).map(x => (`
                <div class="contact" onclick="selectReceiver(event)">
                <div class="${pics[Math.floor(Math.random() * pics.length)]}"></div>
                <div class="name">
                    ${response.data[x]}
                </div>
                <div class="message">
                    Place holder status.
                </div>
            </div>`));

            document.querySelector('#contactList').innerHTML = conversations.join('\n');
        }

        return;
    });
}

/**
 * Load chat for selected receiver.
 * @param receiver
 */
function updateChat(receiver) {
    getRequest(server_address + "/api/v1/messages/read.php", { "sender": username, "receiver": receiver }, (response) => {
        if (response.data !== undefined) {
            /** @var response.data is an array of messages, map over it and update html */
            const conversations = Object.keys(response.data).map(x => (`
        <div class="message${response.data[x]["sender"] === username ? " sender" : ""}">
            ${response.data[x]["body"]}
        </div>`));

            let chatArea = document.querySelector('#chat')
            chatArea.innerHTML = conversations.join('\n');
            chatArea.scrollTop = chatArea.scrollHeight;
        }
        return;
    });
}

/**
 * Set cookie for username preservation.
 * @param cookieName "username" for all cases now.
 * @param cookieValue value of username, String.
 * @param expireDay expiration day for cookie in days.
 */
function setCookie(cookieName, cookieValue, expireDay) {
    let d = new Date();
    d.setTime(d.getTime() + (expireDay * 24 * 60 * 60 * 1000));
    let expires = "expires=" + d.toUTCString();
    document.cookie = cookieName + "=" + cookieValue + ";" + expires + ";path=/";
}

/**
 * Read cookie
 * @param cookieName
 * @returns {string}
 */
function getCookie(cookieName) {
    let name = cookieName + "=";
    let cookieArray = document.cookie.split(';');
    for (let i = 0; i < cookieArray.length; i++) {
        let cookieSingle = cookieArray[i];
        while (cookieSingle.charAt(0) == ' ') {
            cookieSingle = cookieSingle.substring(1);
        }
        if (cookieSingle.indexOf(name) == 0) {
            return cookieSingle.substring(name.length, cookieSingle.length);
        }
    }
    return "";
}