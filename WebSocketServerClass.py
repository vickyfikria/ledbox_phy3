# uncompyle6 version 3.7.4
# Python bytecode 2.7 (62211)
# Decompiled from: Python 2.7.16 (default, Oct 10 2019, 22:02:15) 
# [GCC 8.3.0]
# Embedded file name: /home/pi/ledbox/WebSocketServerClass.py
# Compiled at: 2021-02-18 07:58:54
import ledboxApp as app
from websockets import WebsocketServer
import json

class WebSocketServerClass:
    server = None
    connections = []
    name = 'WebSocket'

    def __init__(self, port):
        self.server = WebsocketServer(port, host='0.0.0.0')
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

    def client_left(self, client, server):
        app.removeClient(self, client)
        if client != None:
            self.connections.remove(client)
        return

    def onMessage(self, client, server, message):
        c = app.getClientBySocketClient(self, client)
        response = app.processMessage(message, c)
        server.send_message(client, response)

    def send(self, message):
        self.server.send_message_to_all(message)

    def sendToClient(self, message, client, compress=True):
        self.server.send_message(client, message)