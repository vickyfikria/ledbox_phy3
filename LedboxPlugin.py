# uncompyle6 version 3.7.4
# Python bytecode 2.7 (62211)
# Decompiled from: Python 2.7.16 (default, Oct 10 2019, 22:02:15) 
# [GCC 8.3.0]
# Embedded file name: /home/pi/ledbox/LedboxPlugin.py
# Compiled at: 2021-02-18 07:58:54
import inspect, string, ConfigParser, os

class LedboxPlugin:
    __name = ''
    __version = 0.1
    __config = None
    _clientid = ''

    def __init__(self, version):
        self.__name = string.replace(self.__class__.__name__, 'Plugin', '')
        self.__version = version
        self._setConfig()

    def setClient(self, id):
        self._clientid = id

    def getInfo(self):
        data = {}
        data['name'] = self.__name
        data['version'] = self.__version
        data['parameters'] = []
        for attribute, value in self.__dict__.items():
            if attribute[:1] != '_':
                param1 = {}
                param1[attribute] = value
                data['parameters'].append(param1)

        return data

    def onAfterMessageProcess(self, message, client):
        return False

    def onBeforeMessageProcess(self, message, client):
        return False

    def getConfig(self):
        return self.__config

    def _setConfig(self):
        if os.path.exists('plugin/' + self.__name + '.ini'):
            self.__config = ConfigParser.ConfigParser()
            self.__config.read('plugin/' + self.__name + '.ini')