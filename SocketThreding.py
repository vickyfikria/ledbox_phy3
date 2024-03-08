# uncompyle6 version 3.7.4
# Python bytecode 2.7 (62211)
# Decompiled from: Python 2.7.16 (default, Oct 10 2019, 22:02:15) 
# [GCC 8.3.0]
# Embedded file name: /home/pi/ledbox/SocketThreding.py
# Compiled at: 2021-02-18 07:58:54
import threading

class SocketThreding(threading.Thread):

    def __init__(self, server_sock):
        threading.Thread.__init__(self)
        self.server_sock = server_sock

    def run(self):
        print ('Socket ' + self.server_sock.name + ' ' + self.server_sock.mode + ' opened')
        if self.server_sock.mode == 'master':
            self.server_sock.open()
        else:
            self.server_sock.client_connect()