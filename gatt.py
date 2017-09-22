# -*- coding: utf-8 -*-
#!/usr/bin/env python3
from bluepy.btle import *
import time, sys, json

gatt = []
services_array = []
characteristics_array = []
descriptors_array = []
d = {}

cccid = AssignedNumbers.client_characteristic_configuration
try:
	adresseObj = sys.argv[1]
	adress_type = sys.argv[2]
except IndexError:
   print "Need for address as argument"
   print " - sudo python notificationBLE_HeartRateSensor.py mac_adress addrtype-"
   sys.exit()


#print "Try to connect to the peripheral"
p = Peripheral()
if adress_type == 'public':
	p.connect(adresseObj,addrType=ADDR_TYPE_PUBLIC)
if adress_type == 'random':
	p.connect(adresseObj,addrType=ADDR_TYPE_RANDOM)

#print("Connect to " +  adresseObj)
services = p.getServices()

for s in services:
	characteristics = s.getCharacteristics()
	characteristics_array = []
	for c in characteristics:
		#Put properties to array
		propArray = []
		for pro in c.propNames:
		   if (pro & c.properties):
			   propArray.append(c.propNames[pro])

		characteristics_array.append({'uuidName': c.uuid.getCommonName(), 'uuid' : str(c.uuid), 'handle': c.getHandle(), 'propertiesToString': propArray})

	desc = s.getDescriptors()
	descriptors_array = []
	for d in desc:
		if d.uuid==cccid:
			#Add characteristic if uuid is cccid
			descriptors_array.append({'uuidName': d.uuid.getCommonName(), 'uuid' : str(d.uuid), 'handle': d.handle})

	#Add service
	services_array.append({'uuidName': s.uuid.getCommonName(), 'uuid' : str(s.uuid), 'hndStart': s.hndStart, 'hndEnd': s.hndEnd, 'characteristics' : characteristics_array,'descriptors' : descriptors_array})

gatt.append({'services' : services_array})
print json.dumps(gatt)
