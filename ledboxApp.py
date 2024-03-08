# uncompyle6 version 3.7.4
# Python bytecode 2.7 (62211)
# Decompiled from: Python 2.7.16 (default, Oct 10 2019, 22:02:15) 
# [GCC 8.3.0]
# Embedded file name: /home/pi/ledbox/ledboxApp.py
# Compiled at: 2021-02-18 16:41:20
version = 0.2
MINUMUN_VERSION_APP = 1.36
import serial, time, sys, signal, json, inspect
from pprint import pprint
import re, os, os.path, configparser, pygame as pg
from ClientConnection import ClientConnection
import ErrorMessage, LEDMatrix2, layoutManager, gzip, netifaces as ni
from io import StringIO
from ledboxSound import ledboxSound
from threading import Thread
from PIL import Image
import subprocess, binascii, RPi.GPIO as GPIO, base64, importlib
Config = configparser.ConfigParser()
UserConfig = configparser.ConfigParser()
alias = ''
deviceName = ''
mode = ''
modifier = ''
current_layout = None
server_sock = []
serialUSB = None
serialUSBClient = None
clients = []
plugin = []
sound = ledboxSound()
isTestMode = False
isDebug = True
log_file = ''
isCompiled = True
layoutManager.loadSystemLayout()
widthOut=192
heightOut=64

if isCompiled:
    extension_plugin = '.pyc'
else:
    extension_plugin = '.py'

def load_modules_from_path(path):
    """
   Import all modules from the given directory
   """
    if path[-1:] != '/':
        path += '/'
    if not os.path.exists(path):
        raise OSError('Directory does not exist: %s' % path)
    sys.path.append(path)
    for f in os.listdir(path):
        if len(f) > 4 and f[-4:] == extension_plugin:
            modname = f[:-4]
            __import__(modname, globals(), locals(), ['*'])
            print ('Preload plugin: ' + modname)


def load_class_from_name(fqcn):
    paths = fqcn.split('.')
    modulename = ('.').join(paths[:-1])
    classname = paths[(-1)]
    __import__(modulename, globals(), locals(), ['*'])
    cls = getattr(sys.modules[modulename], classname)
    if not inspect.isclass(cls):
        raise TypeError('%s is not a class' % fqcn)
    return cls


def loadPlugin():
    global plugin
    plugin = []
    load_modules_from_path('plugin')
    for f in os.listdir('plugin/'):
        path_file = 'plugin/' + f
        filename, extension = os.path.splitext(f)
        if os.path.isfile(path_file):
            if extension == extension_plugin:
                class_name = load_class_from_name(filename + '.' + filename + 'Plugin')
                plugin.append(class_name())


def Debug(message):
    if isDebug:
        log = time.strftime('%Y-%m-%d %H:%M:%S', time.gmtime()) + '|' + message + '\n'
        file_debug = open('log.txt', 'a+')
        file_debug.write(log)
        file_debug.close()
        file_debug2 = open(log_file, 'a+')
        file_debug2.write(log)
        file_debug2.close()


def addClient(socket, client, address='', clienttype='client'):
    if socket == None:
        return
    else:
        if client == None:
            return
        c = getClientBySocketClient(socket=socket, client=client)
        if c != None:
            return c
        c = getClient(address, socket.name)
        if c != None:
            removeClientById(c.id)
        if len(clients) == 0:
            id = 1
        else:
            id = clients[(len(clients) - 1)].id + 1
        c = ClientConnection()
        c.id = id
        c.client = client
        c.clienttype = clienttype
        c.address = address
        c.socket = socket
        clients.append(c)
        Debug('ADD CLIENT: Client ' + str(c.id) + ' connected from client ' + c.socket.name + ' address ' + c.address)
        debugClients()
        return c


def editClient(id, alias, sport, role, typedevice, config):
    for c in clients:
        if c.id == id:
            c.alias = alias
            c.sport = sport
            c.role = role
            c.typedevice = typedevice
            c.config = config
            debugClients()
            return


def setUploadClient(id, filepathToUpload, typeToUpload, requestToUpload):
    for c in clients:
        if c.id == id:
            c.filepathToUpload = filepathToUpload
            c.typeToUpload = typeToUpload
            c.requestToUpload = requestToUpload
            return


def removeClient(socket, client):
    for c in clients:
        if c.socket.name == socket.name:
            if c.client == client:
                Debug('REMOVE CLIENT: Client ' + str(c.id) + ' removed (' + c.socket.name + ') ' + c.address)
                clients.remove(c)
                debugClients()
                return


def removeClientById(clientid):
    for c in clients:
        if c.id == clientid:
            Debug('REMOVE CLIENT: Client ' + str(c.id) + ' removed (' + c.socket.name + ') ' + c.address)
            clients.remove(c)
            debugClients()
            return


def getRoleFromClients(id_client=None):
    for c in clients:
        if id_client != None:
            if c.id == id_client:
                if c.role != '':
                    return c.role
        if c.role == 'admin':
            return 'guest'

    return 'admin'


def setRoleToClient(id_client, role):
    for c in clients:
        if c.id == id_client:
            c.role = role


def debugClients():
    file_debug = open('current_users.txt', 'w+')
    for c in clients:
        file_debug.write(str(c.id) + ';' + c.socket.name + ';' + c.address + ';' + c.alias + ';' + c.sport + ';' + c.role + ';' + c.typedevice + '\n')

    file_debug.close()


def getClientBySocketClient(socket, client):
    for c in clients:
        if c.socket.name == socket.name:
            if c.client == client:
                return c

    return


def getClient(address, connection_name):
    for c in clients:
        if c.address == address and c.socket.name == connection_name:
            return c

    return


def getClientById(id):
    for c in clients:
        if c.id == id:
            return c

    return


def checkClients():
    if mode == 'master':
        while True:
            if len(clients) > 0:
                for c in clients:
                    data = createMessage('Ack', '')
                    c.socket.sendToClient(data, c.client)

            time.sleep(5)


def cleanText(text):
    text = re.sub('[^a-z.0-9]+', '', text, flags=re.IGNORECASE)
    return text


def createMessage(cmd, value, alias='', sport='', buffer=''):
    data = {}
    data['cmd'] = cmd
    data['alias'] = alias
    data['sport'] = sport
    data['value'] = value
    if buffer != '':
        data['buffer'] = buffer
    json_data = json.dumps(data)
    return json_data


def createErrorMessage(sender, error_code):
    data = {}
    data['status'] = 'error'
    data['sender'] = sender
    data['error_code'] = error_code
    data['error_message'] = ErrorMessage.getMessage(data['error_code']).error_message
    json_data = json.dumps(data)
    return json_data


def processMessage(message, client=None, compress_message=False):
    for p in plugin:
        p.setClient(client.id)
        p_result = p.onAfterMessageProcess(message, client)
        if p_result != False:
            return p_result

    if compress_message == True:
        buff = StringIO(message)
        with gzip.GzipFile(fileobj=buff) as (gz):
            message = gz.read()
            message.decode('utf-8')
    m = message.split('}{')
    if len(m) > 1:
        message = m[0] + '}'
    value = None
    if client == None:
        client = ClientConnection()
        client.id = ''
        client.address = ''
    data = {}
    Debug('RECEIVED from client ' + str(client.id) + ' address ' + client.address + '=' + message)
    try:
        obj_msg = json.loads(message)
        if 'cmd' in obj_msg:
            id_message = obj_msg['cmd']
        else:
            data['status'] = 'error'
            data['sender'] = ''
            data['error_code'] = 4
            data['error_message'] = ErrorMessage.getMessage(data['error_code']).error_message
            json_data = json.dumps(data)
            return json_data
        if 'value' in obj_msg:
            value = obj_msg['value']
        else:
            data['status'] = 'error'
            data['sender'] = id_message
            data['error_code'] = 3
            data['error_message'] = ErrorMessage.getMessage(data['error_code']).error_message
            json_data = json.dumps(data)
            return json_data
    except ValueError as error:
        data['status'] = 'error'
        data['sender'] = ''
        data['error_code'] = 2
        data['error_message'] = ErrorMessage.getMessage(data['error_code']).error_message
        json_data = json.dumps(data)
        return json_data
        Debug('message not valid: %s' % error)
        return False

    API.setClient(client.id)
    api_cmd = getattr(API, obj_msg['cmd'], None)
    if api_cmd == None:
        for p in plugin:
            p.setClient(client.id)
            api_cmd_plugin = getattr(p, obj_msg['cmd'], None)
            if api_cmd_plugin != None:
                api_cmd = api_cmd_plugin

    try:
        if api_cmd != None:
            if value != None:
                response = api_cmd(obj_msg)
            else:
                response = api_cmd()
            isResend = True
            try:
                if 'noresend' not in response:
                    isResend = True
                else:
                    isResend = False
            except Exception as e:
                pass

            if isResend == True:
                if isTestMode == False:
                    resendMessageBroadcast(message, client)
            if isinstance(response, ErrorMessage.ErrorMessageStruct):
                data['status'] = 'error'
                data['sender'] = obj_msg['cmd']
                data['error_code'] = response.error_code
                data['error_message'] = response.error_message
            else:
                data['status'] = 'ok'
                data['sender'] = obj_msg['cmd']
                if str(type(response)) == "<type 'instance'>":
                    response = response.__dict__
                data['value'] = response
        else:
            data['status'] = 'error'
            data['sender'] = obj_msg['cmd']
            data['error_code'] = 1
            data['error_message'] = ErrorMessage.getMessage(data['error_code']).error_message
        json_data = json.dumps(data, default=obj_dict)
        Debug('RESPONSE=' + json_data)
        for p in plugin:
            p.setClient(client.id)
            p_result = p.onBeforeMessageProcess(message, client)
            if p_result != False:
                return p_result

        return json_data
    except Exception as e:
        Debug('Error processMessage ' + str(e))

    return


def obj_dict(obj):
    return obj.__dict__


def resendMessageBroadcast(message, current_client, compress=True, sendonly='', nosleep=False):
    thread = Thread(target=_resendMessageBroadcast, args=(message, current_client, compress, sendonly, nosleep))
    thread.start()


def _resendMessageBroadcast(message, current_client, compress=True, sendonly='', nosleep=False):
    try:
        for c in clients:
            if isinstance(c, ClientConnection) and isinstance(current_client, ClientConnection):
                if c.id != current_client.id:
                    onResend = False
                    if sendonly != '':
                        if c.typedevice == sendonly:
                            c.socket.sendToClient(message, c.client, compress)
                            onResend = True
                    else:
                        c.socket.sendToClient(message, c.client, compress)
                        onResend = True
                    if onResend:
                        if compress == False:
                            msg = ''
                        else:
                            msg = message
                        Debug('RESEND from client ' + str(current_client.id) + ' (' + current_client.socket.name + ':' + current_client.typedevice + ') ' + current_client.address + ' to client ' + str(c.id) + ' (' + c.socket.name + ') ' + c.address + '|' + msg)

    except Exception as e:
        Debug('RESEND MESSAGE ERROR: ' + str(e))


def sendToClients(message):
    try:
        for c in clients:
            c.socket.sendToClient(message, c.client)
            Debug('SEND to client ' + str(c.id) + ' (' + c.socket.name + ') ' + c.address + '|' + message)

        time.sleep(0.2)
    except Exception as e:
        Debug('SEND MESSAGE ERROR: ' + str(e))


def closeAllSockets():
    for sthread in socketThread:
        sthread.kill()


def enableWifiAccessPoint():
    sudoPassword = 'raspberry'
    command = 'sh startHostapd.sh'
    p = os.system('echo %s|sudo -S %s' % (sudoPassword, command))


def changeWifiSSID(ssidname):
    wifi.set_device_names(ssidname)
    wifi.turn_off_wifi()
    wifi.turn_on_wifi()


def play_beep(volume=0.8):
    sound.playBeep()


def play_buzzer(sleep=0.5):
    GPIO.output(21, 1)
    time.sleep(sleep)
    GPIO.output(21, 0)


def start_playmusic(music_file, volume=0.8):
    try:
        sound.playMusic(music_file)
    except Exception as e:
        print ('ERROR playing music :' + str(e))


def pause_playmusic():
    try:
        sound.pauseMusic()
    except:
        pass


def resume_playmusic():
    try:
        sound.resumeMusic()
    except Exception as e:
        print ('ERROR resume music :' + str(e))


def stop_playmusic():
    try:
        sound.stopMusic()
    except Exception as e:
        print ('ERROR stop music :' + str(e))


def saveConfig():
    configfile = open('user_setting.ini', 'w')
    UserConfig.write(configfile)
    configfile.close()


def getNetworkInfo():
    global current_back_eth_ip
    global current_eth_ip
    global current_wifi_ip
    current_wifi_ip = 'nd'
    current_back_eth_ip = 'nd'
    current_eth_ip = 'nd'
    try:
        current_wifi_ip = getIpCard('wlan0')
        current_back_eth_ip = getIpCard('eth0:0')
        current_eth_ip = getIpCard('eth0')
    except:
        pass

    print ('Current LAN IP ' + current_eth_ip)
    print ('Current WIFI IP ' + current_wifi_ip)


def getIpCard(card):
    cmd = 'ifconfig ' + card + " | grep -oP '(?<=inet\\s)\\d+(\\.\\d+){3}'"
    proc = subprocess.Popen([cmd], stdout=subprocess.PIPE, shell=True)
    out, err = proc.communicate()
    out = out.replace('\n', '')
    return out