# -*- coding: utf-8 -*-
#!/usr/bin/env python3
from bluepy.btle import *
import time, sys, json

#AssignedNumbers
cccid = AssignedNumbers.client_characteristic_configuration
hrmid = AssignedNumbers.heart_rate
hrmmid = AssignedNumbers.heart_rate_measurement

try:
	adresseObj = sys.argv[1]
except IndexError:
   print "Need for address as argument"
   print " - sudo python notificationBLE_HeartRateSensor.py 00:00:00:00:00 -"
   sys.exit()



def print_hr(cHandle, data):

			bpm = ord(data[1])
			rr = ord(data[2])
			rr2 = ord(data[3])

			rr = '{:08b}'.format(rr)
			rr2 ='{:08b}'.format(rr2)
			rrInMs = int(rr2+rr,2)
			response = {}
			response['bpm']= bpm;
			response['rr_interval']= rrInMs
			print json.dumps(response)


print "Try to connect to the peripheral"
p = Peripheral()

p.connect(adresseObj,addrType=ADDR_TYPE_RANDOM)
print("Connect to " +  adresseObj)
#Explore GATT architecture
service, = [s for s in p.getServices() if s.uuid==hrmid]
ccc, = service.getCharacteristics(forUUID=str(hrmmid))
desc = p.getDescriptors(service.hndStart,service.hndEnd)
d, = [d for d in desc if d.uuid==cccid]

p.writeCharacteristic(d.handle, '\1\0')
p.delegate.handleNotification = print_hr

while True:
	p.waitForNotifications(5.)
	sys.stdout.flush()
