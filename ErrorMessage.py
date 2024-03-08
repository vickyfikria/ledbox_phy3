# uncompyle6 version 3.7.4
# Python bytecode 2.7 (62211)
# Decompiled from: Python 2.7.16 (default, Oct 10 2019, 22:02:15) 
# [GCC 8.3.0]
# Embedded file name: /home/pi/ledbox/ErrorMessage.py
# Compiled at: 2021-02-18 07:58:54


class ErrorMessageStruct:

    def __init__(self, code=0, message=''):
        self.error_code = code
        self.error_message = message


def getErrorMessage():
    em = ErrorMessageStruct()
    return em


def getMessage(code, args=None):
    em = ErrorMessageStruct()
    em.error_code = code
    if code == 1:
        em.error_message = 'API not avaible'
    if code == 2:
        em.error_message = 'message not formatted in JSON'
    if code == 3:
        em.error_message = "key 'value' not defined"
    if code == 4:
        em.error_message = "key 'cmd' not defined"
    if code == 5:
        em.error_message = 'layout ' + args + ' not present in device'
    if code == 6:
        em.error_message = 'section ' + args + ' not found'
    if code == 7:
        em.error_message = 'format XML layout not correct'
    if code == 8:
        em.error_message = 'App not compatible'
    if code == 9:
        em.error_message = 'key ' + args + ' not defined'
    if code == 99:
        em.error_message = 'Upload not complete'
    print (str(code) + ' - ' + em.error_message)
    return em