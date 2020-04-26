# Chatty - PHP Back-end Assignment
This is a basic chat application back-end written in PHP. All communications happen via JSON over HTTP requests. Back-end was prioritized rather than front-end or client-side. GUI is not compatible with mobile (Not responsive) and best used at 100% scaling.

# Usage

No registration/authentication is needed to use the service. A simple username is used to identify the person using. The username is stored as a cookie for a day.

## Messaging

In order to message someone, the receiver must have an account beforehand. Sending messages to non-existing usernames is not possible and it is enforced via **client side** script.

## Receiving a message

A function is called every second to refresh the conversation between sender and selected receiver. This is done by requesting for the **entire** chat log between sender and receiver. This can be optimized later on if requested by simply keeping track of the time of the latest received message and requesting the messages *after* that time.

## Sending a message

In order to send a message, there needs to be a valid receiver/target. If so, this can be done via the bottom of the right panel. Message cannot be empty. Sending a message also triggers a function to refresh the chat/conversation list.

# Project Structure

Client side of the project is located inside the `client/` directory. `index.html` file is the entry point to client. This file then includes cascaded style sheet from `client/css/` directory, main script file from `client/js/` directory and loads media located in `client/media/` directory.

Server side files are located under the `server/` directory. `server/api/` directory holds all the endpoints for the application and those are easily scale-able. `server/entities` directory includes the entities for all the endpoints. `server/database` directory stores the database deceleration/constructor file and stores the actual database file. `server/utils` directory includes the helpers/utils such as error/success messages and finally, the `server/config` directory stores the database setup file for now. Latter can be improved greatly on further implementations.

# Installation

 A web server is advised to use this application, but not required. If separate local servers are used for back-end (such as `php -S localhost:5500`) and client (Like live server for http serving?), there is a huge chance to receive cross origin errors. 

* Install required components. Running the command below for distros based on Pacman package manager.
```
sudo pacman -S sqlite php php-fpm php-sqlite nginx
```
or for distros based on Apt package manager
```
sudo apt install sqlite3 php7.2 php7.2-fpm php7.2-sqlite nginx
```

* Include PHP execution in server configuration file:
```
location ~ \.php$ {
	include snippets/fastcgi-php.conf;
	fastcgi_pass unix:/var/run/php/php7.2-fpm.sock;
}
```

* Set the back-end server address in the `main.js` file located at `client/js/` directory. Default address is set to the address that the example server runs.

* Run the database creator `setup.php` in the `server/config/` directory. Using the command `php setup.php`.

* Give/set the permissions to server or database files to prevent access errors.

## API References

All requests returns a JSON object. The requests resulted in error will always include a `code` and a `message` attribute. Error codes are ranging between `2` and `8`. The requests that are resulted in success might include `code` and `message` or `code` and `data` depending on the operation. Create operations such as user creating or message creating will return code `0` and message stating that it was successful. Read operations that are resulted in success will return code `1` and `data`. The blow table show all the possible API routes and request methods.


| API Route | Data| Method | Success Data | Fail Data |
|-----------|-----|--------|--------------|-----------|
| /api/v1/user/create.php | [JSON]: { username: [string] }| POST | [JSON]: { code: 0, message: [message] } | [JSON Object]: {   code: 2-8,   message: [message] } |
| /api/v1/user/read.php | &username: [string]| GET | [JSON]: { code: 1, data: [JSON] } | [JSON Object]: {   code: 2-8,   message: [message] } |
| /api/v1/message/create.php | [JSON]: { sender: [string], receiver: [string], body: [string] }| POST | [JSON]: { code: 0, message: [message] } | [JSON Object]: {   code: 2-8,   message: [messsage]
| /api/v1/message/read.php | &id: [number]| GET | [JSON]: { code: 1, data: [JSON] } | [JSON Object]: {   code: 2-8,   message: [message] } |age] } |
| /api/v1/messages/read.php | &sender: [string], &receiver: [string]| GET | [JSON]: { code: 1, data: [JSON] } | [JSON Object]: {   code: 2-8,   message: [message] } |age] } |
| /api/v1/charlist/read.php | &sender: [string]| GET | [JSON]: { code: 1, data: [JSON] } | [JSON Object]: {   code: 2-8,   message: [message] } |age] } |
