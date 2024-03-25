import ledboxApp as app
from websocket_server import WebsocketServer
import json

class WebSocketServerClass:
    server = None
    connections = []
    name = 'WebSocket'

    def __init__(self):
        self.server = WebsocketServer(port=50007, host='0.0.0.0')
        self.server.set_fn_new_client(self.new_client)
        self.server.set_fn_message_received(self.onMessage)
        self.server.set_fn_client_left(self.client_left)

    def run(self):
        self.server.run_forever()
        return self.server

    def new_client(self, client, server):
        data = {}
        data['status'] = 'ok'
        data['sender'] = 'Connect'
        json_data = json.dumps(data)
        self.connections.append(client)
        app.addClient(self, client)
        server.send_message(client, json_data)
        print('New Client'+str(client['id'])+ ' comes')

    def client_left(self, client, server):
        app.removeClient(self, client)
        if client != None:
            self.connections.remove(client)
        print(str(client['id'])+ ' Client left')
        return

    def onMessage(self, client, server, message):
        c = app.getClientBySocketClient(self, client)
        response = app.processMessage(message, c)
        print('[WS] Response from client ' + str(client['id']))
        print(response)
        server.send_message(client, response)

    def send(self, message):
        self.server.send_message_to_all(message)

    def sendToClient(self, message, client, compress=True):
        self.server.send_message(client, message)
