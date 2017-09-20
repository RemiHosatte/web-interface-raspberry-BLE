# -*- coding: utf-8 -*-
#!/usr/bin/env python3
from bluepy.btle import *
import time, sys, json

try:
    adresseObj = sys.argv[1]
except IndexError:
   print "Need for address as argument"
   print " - sudo python notificationBLE_HM-10.py 00:00:00:00:00 -"
   sys.exit()


handleObj =  18

def notificationHM10(cHandle, data):
    #Creates JSON for export
    response = {}
    response['data']= data;
    print json.dumps(response)

p = Peripheral()

try:
    p.connect(adresseObj)
except BTLEException:
    time.sleep(1)
    p.connect(adresseObj)


p.writeCharacteristic(handleObj, '\1\0') #Activate notifications
p.delegate.handleNotification = notificationHM10 #Assigns notifications to "notificationSensor" function

while True:
    p.waitForNotifications(5.)
    sys.stdout.flush()
