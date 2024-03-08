
import os
import urllib, json
import xml.etree.ElementTree as ET
import ConfigParser
import base64
import sys

version_hw="0.1"
version_sw="0.1"
config={}

#get version sw
tree=ET.parse("../manifest.xml").getroot()
for child in tree:
    if(child.tag=="version"):
        version_sw=child.text

#get version hw
setting = ConfigParser.ConfigParser()
setting.read("../setting.ini")
version_hw=setting.get("LEDBOX","version_hw")

#get config

#CPU
fcpuinfo=open("/proc/cpuinfo","r")
config['cpu']=fcpuinfo.read()
fcpuinfo.close()

faddresseth0=open("/sys/class/net/eth0/address","r")
config['eth0']=faddresseth0.read()
faddresseth0.close()

faddresswlan0=open("/sys/class/net/wlan0/address","r")
config['wlan0']=faddresswlan0.read()
faddresswlan0.close()

json_config=json.dumps(config)

config64=base64.b64encode(bytes(json_config))

url = "http://ledbox.tech4sport.com/api.php?task=RegistrationNewDevice&version_hw="+version_hw+"&version_sw="+version_sw+"&config="+config64

try:
	response = urllib.urlopen(url).read()
	data = json.loads(response)
	print(data['device']['serialnumber']+";"+data['device']['password'])
except:
	pass

