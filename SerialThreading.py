# uncompyle6 version 3.7.4
# Python bytecode 2.7 (62211)
# Decompiled from: Python 2.7.16 (default, Oct 10 2019, 22:02:15) 
# [GCC 8.3.0]
# Embedded file name: /home/pi/ledbox/SerialThreading.py
# Compiled at: 2021-02-18 07:58:54
import threading, io, time, serial, ledboxApp as app, gzip, ledboxFileUploadServer

class SerialThreading(threading.Thread):

    def __init__(self, serialPort, name):
        threading.Thread.__init__(self)
        self.name = name
        self.serialPort = serialPort
        self.serialUpload = ledboxFileUploadServer.socketFileUploadServer('usb')
        self.serialUpload.enable = False
        self.serialUpload.setSerialUSB(serialPort)
        self.eol = '\r\n'

    def send(self, data):
        self.serialPort.write(data)

    def sendToClient(self, message, client='', compress=True):
        if compress:
            out = io.BytesIO()
            with gzip.GzipFile(fileobj=out, mode='wb') as (gz):
                gz.write(message)
                gz.close()
            message = out.getvalue()
        try:
            self.serialPort.write(message)
            self.serialPort.write(self.eol)
            time.sleep(0.2)
        except Exception as e:
            print ('ERROR Send serial message ' + str(e))
            if client.address == 'USB2':
                self.serialPort.close()
                time.sleep(500)
                self.serialPort.open()

    def sendPreview(self):
        time.sleep(0.01)
        self.serialPort.write(open('www/buffer_compressed.png').read())
        self.serialPort.write('\n<<EOF>>\n')

    def run(self):
        print ('Serial Port ' + self.serialPort.port + ' opened\n')
        data = ''
        leneol = len(self.eol)
        data = bytearray()
        client = app.addClient(self, self.serialPort, self.name)
        if client.address == 'USB2':
            app.editClient(client.id, '', '', '', 'ledbox', '')
        while True:
            try:
                if self.serialPort.isOpen():
                    c = self.serialPort.read(1)
                    if c:
                        data += c
                        if data[-leneol:] == self.eol:
                            response = app.processMessage(bytes(data[:-leneol]), client, True)
                            if response != None:
                                self.sendToClient(response)
                                if 'Upload' in response and ('"exist": false' in response or '"exist": true' in response and '"forceUpload": true' in response):
                                    print ('Start UPLOAD')
                                    self.serialUpload.enable = True
                                    self.serialUpload.run()
                                    print ('Close UPLOAD')
                            self.sendPreview()
                            data = bytearray()
                    elif len(data) > 0:
                        response = app.processMessage(bytes(data), client, True)
                        if response != None:
                            self.sendToClient(response)
                            if 'Upload' in response and ('"exist": false' in response or '"exist": true' in response and '"forceUpload": true' in response):
                                print ('Start UPLOAD')
                                self.serialUpload.enable = True
                                self.serialUpload.run()
                                print ('Close UPLOAD')
                        self.sendPreview()
                        data = bytearray()
            except Exception as e:
                data = bytearray()
                print ('ERROR ' + str(e))
                if client.address == 'USB2':
                    self.serialPort.close()

        return