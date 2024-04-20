
class LedboxPlugin:
    __name = ''
    __version = 0.1
    __config = None
    _clientid = ''

    def __init__(self, version):
        self.__name = str.replace(self.__class__.__name__, 'Plugin', '')
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
            self.__config = configparser.ConfigParser()
            self.__config.read('plugin/' + self.__name + '.ini')
