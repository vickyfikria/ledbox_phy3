# uncompyle6 version 3.7.4
# Python bytecode 2.7 (62211)
# Decompiled from: Python 2.7.16 (default, Oct 10 2019, 22:02:15) 
# [GCC 8.3.0]
# Embedded file name: /home/pi/ledbox/ClientConnection.py
# Compiled at: 2021-02-18 07:58:54
import ledboxApp as app, socket

class ClientConnection:
    id = ''
    client = None
    clienttype = ''
    address = ''
    alias = ''
    sport = ''
    socket = None
    role = ''
    typedevice = 'app'
    filepathToUpload = ''
    typeToUpload = ''
    requestToUpload = ''
    sock_upload = ''
    config = ''

    def connectToUploadServer(self):
        if self.address == 'USB2':
            self.sock_upload = self.socket
        else:
            self.sock_upload = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
            self.sock_upload.setsockopt(socket.SOL_SOCKET, socket.SO_REUSEADDR, 1)
            self.sock_upload.connect((self.address, 12345))

    def sendToUploadServer(self, data):
        print ('Data send to ' + self.address)
        self.sock_upload.send(data)

    def closeUploadServer(self):
        if self.address != 'USB2':
            self.sock_upload.close()