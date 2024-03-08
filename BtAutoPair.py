# uncompyle6 version 3.7.4
# Python bytecode 2.7 (62211)
# Decompiled from: Python 2.7.16 (default, Oct 10 2019, 22:02:15) 
# [GCC 8.3.0]
# Embedded file name: /home/pi/ledbox/BtAutoPair.py
# Compiled at: 2021-02-18 07:58:54
import sys, time, pexpect, subprocess

class BluetoothctlError(Exception):
    """This exception is raised, when bluetoothctl fails to start."""
    pass


class BtAutoPair:
    """Class to auto pair and trust with bluetooth."""

    def __init__(self):
        p = subprocess.Popen(["/usr/local/bin/auto-agent"], shell=False)
        out = subprocess.check_output('/usr/sbin/rfkill unblock bluetooth', shell=True)
        self.child = pexpect.spawn('bluetoothctl', echo=False)

    def get_output_old(self, command, pause=0):
        """Run a command in bluetoothctl prompt, return output as a list of lines."""
        self.child.send(command + '\n')
        time.sleep(pause)
        start_failed = self.child.expect(['bluetooth', pexpect.EOF])
        if start_failed:
            raise BluetoothctlError('Bluetoothctl failed after running ' + command)
        return self.child.before.split('\r\n')

    def get_output(self,command, response = "succeeded"):
        """Run a command in bluetoothctl prompt, return output as a list of lines."""
        self.child.send(command + "\n")
        pause = 0
        time.sleep(pause)
        start_failed = self.child.expect([response, pexpect.EOF])

        if start_failed:
            raise BluetoothctlError("Bluetoothctl failed after running " + command)
            
        return self.child.before.split("\r\n".encode())

    def enable_pairing(self, name):
        """Make device visible to scanning and enable pairing."""
        print ('pairing enabled')
        try:
            
            out = self.get_output('system-alias '+ name)
            out = self.get_output('power on')
            out = self.get_output('discoverable on')
            out = self.get_output('pairable on')
            out = self.get_output("agent off", "unregistered")            
            #out = self.get_output('agent off')
        except BluetoothctlError as e:
            print ('ERROR ' + e)
            return

        return

    def disable_pairing(self):
        """Disable devices visibility and ability to pair."""
        try:
            out = self.get_output('discoverable off')
            out = self.get_output('pairable off')
        except BluetoothctlError as e:
            print ('ERROR ' + e)
            return

        return
