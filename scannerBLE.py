from bluepy.btle import Scanner, DefaultDelegate
import json
response = []
details = {}
adressBLE = ""
nameBLE = ""
class ScanDelegate(DefaultDelegate):
    def __init__(self):
        DefaultDelegate.__init__(self)


scanner = Scanner().withDelegate(ScanDelegate())
devices = scanner.scan(4.0)

for dev in devices:
    nameBLE = "Unknown" #Default name
    adressBLE = dev.addr
    for (adtype, desc, value) in dev.getScanData():
        details[desc] = value
        if adtype == 9 or adtype == 8:
            nameBLE = value


    response.append({'adress': adressBLE, 'name' : nameBLE, 'details': details})


print json.dumps(response)
