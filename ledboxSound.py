# uncompyle6 version 3.7.4
# Python bytecode 2.7 (62211)
# Decompiled from: Python 2.7.16 (default, Oct 10 2019, 22:02:15) 
# [GCC 8.3.0]
# Embedded file name: /home/pi/ledbox/ledboxSound.py
# Compiled at: 2021-02-18 07:58:54
import pygame as pg, json, sys

class ledboxSound:
    mp3 = ''
    volume = 0.8
    current_filename = ''

    def __init__(self):
        freq = 44100
        bitsize = -16
        channels = 2
        buffer = 2048
        try:
            pg.mixer.init(freq, bitsize, channels, buffer)
        except:
            print ('Sound module not run')

    def play_music(self, music_file, volume=0.8):
        try:
            pg.mixer.music.set_volume(volume)
            clock = pg.time.Clock()
        except:
            return

        try:
            pg.mixer.music.load(music_file)
        except pg.error:
            print ('File {} not found! ({})').format(music_file, pg.get_error())
            return

        return pg.mixer.music

    def playMusic(self, filename):
        self.current_filename = filename
        self.mp3 = self.play_music(filename, self.volume)
        if self.mp3:
            self.mp3.play()
            print ('Start playing ' + filename)

    def stopMusic(self):
        if self.mp3 != '':
            self.mp3.stop()

    def pauseMusic(self):
        if self.mp3 != '':
            self.mp3.pause()

    def resumeMusic(self):
        if self.mp3 != '':
            self.mp3.unpause()

    def setVolume(self, volume):
        if self.mp3 != '':
            self.mp3.set_volume(volume)

    def playBeep(self):
        try:
            beep = pg.mixer.Sound('media/buzzer.wav')
            beep.set_volume(1.0)
            beep.play()
        except:
            print ('No audio card present')