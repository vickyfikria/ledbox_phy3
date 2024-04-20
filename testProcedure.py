import ledboxApp as app, time, threading, gzip, json, base64
from io import BytesIO
from io import StringIO
from urllib.request import urlopen
check_usb = 0
check_display = 0
status = 1
stopThread = False
thread_send = None
thread_display = None
thread_check = None
base = 'http://ledbox.tech4sport.com/'

def run(onlineVerify=False):
    app.isTestMode = True
    app.Debug('RUN TEST LEDbox')
    print('#1')
    testDisplay()
    print('#2')
    testSerialCable()
    print('#3')
    if onlineVerify == True:
        thread_check = threading.Thread(target=checkTest)
        thread_check.start()


def checkDeviceVerification():
    global base
    try:
        url = base + 'api.php?task=getVerification&serialnumber=' + app.deviceName
        with urlopen(url) as response :
            body = response.read()
        # response = urlopen(url, timeout=2).read()
        print(bytes(body).decode())
        son = json.loads(body)
        print(son['status'])
        print(son['device']['verified'])
        # son['device']['verified'] = 0 means not yet verified by admin
        # son['device']['verified'] = 1 means already confirm by admin
        # through https://ledbox.tech4sport.com/index.php?view=listDevices  admin page 
	# if this ledbox not yet verified by admin, keep testing sequence testDisplay > testSerialCable >    
        if son['device']['verified'] == 0:
            print(f' {app.deviceName} is verified')
            run(True)
    except Exception as e:
        print(f' {app.deviceName} Device verification fail : {e}')
        pass


def checkTest():
    global check_display
    global check_usb
    global stopThread
    global thread_display
    global thread_send
    while True:
        if check_display > 1 and check_usb > 1:
            app.Debug('Test OK')
            stopThread = True
            thread_display.join()
            thread_send.join()
            app.current_layout = app.layoutManager.loadLayout('waiting')
            r = 'Display OK\nUSB OK'.encode() #this str need to encode to bytes 
            print(r)

            result = base64.b64encode(r)
            print('result : ')
            print(result)

            verify = True
            url = base + 'api.php?task=setVerification&serialnumber=' + app.deviceName + '&verify=' + str(verify) + '&result=' + result.decode()
            print(url)
            '''with urlopen(url) as response:
                body = response.read()
            print(bytes(body).decode())
            #response = urllib.urlopen(url).read()
            app.Debug('Verification Send to ' + url)'''
            checkUSBSendend(0)
            break


def testDisplay():
    global thread_display
    thread_display = threading.Thread(target=changeColorDisplay)
    thread_display.start()


def testSerialCable():
    global thread_send
    thread_send = threading.Thread(target=sendMessageFromUSB)
    thread_send.start()


def changeColorDisplay():
    global check_display
    while True:
        if stopThread == True:
            print('Stop changeColorDisplay thread')
            break
        app.current_layout = app.layoutManager.loadLayout('test')
        app.current_layout.setSection('rectangle', 'color', '255,0,0')
        time.sleep(1)
        app.current_layout.setSection('rectangle', 'color', '0,255,0')
        time.sleep(1)
        app.current_layout.setSection('rectangle', 'color', '0,0,255')
        time.sleep(1)
        app.current_layout.setSection('rectangle', 'color', '255,255,255')
        time.sleep(1)
        check_display = check_display + 1


def sendMessageFromUSB():
    global status
    while True:
        if stopThread == True:
            print('Stop sendMessageFromUSB thread')
            break
        if status == 1:
            data = {}
            data['cmd'] = 'CheckTest'
            data['value'] = 'test'
            json_data = json.dumps(data)
            print(json_data)
            app.Debug('SEND from USB: ' + json_data)
            time.sleep(2)
            out = BytesIO() #use BytesIO for python3 instead StringIO
            with gzip.GzipFile(fileobj=out, mode='wb') as (gz):
                gz.write(json_data.encode('utf-8'))
                gz.close()
                coding_message = out.getvalue() # take value from Buffer out as string
                # problem gzip signature will not 
		# coding_message = out.getvalue() + '/r/n'.encode(), ERROR initial_value must be str
		# coding_message =  b'\x1f\x8b\x08\....\x00\x00\x00/r/n'
            print(f' coding message : {out.getvalue()}')
            print(f' out.read : {out.read()}')

            print(f' out : {out}')
            try:
                app.serialUSBClient.write(out) 
            except Exception as e:
                app.Debug('ERROR SEND USB ' + str(e))
                print ('ERROR - cable disconnected ' + str(e))

            status = 0


def checkUSBSendend(value):
    global check_usb
    global status
    status = 1
    data = {}
    data_value = {}
    data_value['times'] = 1
    data_value['sleep'] = 0.5
    data['value'] = data_value
    app.API.Horn(data)
    check_usb = check_usb + 1
    print('checkUSBSendend done')
