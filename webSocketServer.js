const WebSocket = require('ws');
const http = require('http');      //import ws library


const server = http.createServer();  // created a server

const wss = new WebSocket.Server({port:3000}); //create a websocket server on top of http server


wss.on('connection', (ws) => {
    console.log('Client Connected');

    ws.send('Welcome to the WebSocket Server');


    ws.on('message', (message)=> {
        console.log('Recieved from client: '+ message); 

        ws.send('Server recieved your message: ' + message);
    });


    ws.on('close', () => {
       console.log('Client disconnected');
    });
});

console.log("WebSocket is now running on ws://localhost:3000");