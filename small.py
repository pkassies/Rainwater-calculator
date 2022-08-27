# Project: Rain Water Tank
# Using Raspberry PI UART interface
# Using waterproof A02YYUW distance sensor

#To test this script
#Run from the command line with python3 watermon.py

#this script is executed every 15 minutes with CRON settings of Raspberry Pi
#0,15,30,45 * * * * python3 /home/pi/watermon.py

# Settings
import time,os
import datetime

# Load sensor class
from DFRobot_RaspberryPi_A02YYUW import DFRobot_A02_Distance as Board
board = Board()

# Take 5 samples of the sensor
def get_distance():
	dist_add = 0
	for x in range(5):
		try:
			distance = board.getDistance()
			# distance is measured in mm, converting to cm
			distance = distance/10
			# Entry to print the sample output
			print (x, "distance: ", distance)
		
			dist_add = dist_add + distance
			time.sleep(.3) # 300ms interval between readings
		
		except Exception as e: 
			pass
	
	# show a list of the samples	
	print ("x: ", x+1, "samples")

	# Take the average of the samples, rounded at 1 digits after the decimal point 
	avg_dist=dist_add/(x+1)
	dist=round(avg_dist,1)

	# show the average result
	print ("distance: ", dist)
	return dist
# end of function

# Function to send the sensor measured distance to the remote database
def sendData_to_remoteServer(url,dist):
	url_full=url+str(dist)
	urlopen(url_full)
	print("sent to url: ",url_full)
	
from urllib.request import urlopen
passcode="password"
url_remote="http://192.168.0.99/db_insert.php?level="
# End of function

# Main logic
distance=get_distance()
sendData_to_remoteServer(url_remote,distance)

# End of program
