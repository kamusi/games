from datetime import datetime
from time import sleep
from sys import maxint
import re
import csv
import sys
import pickle
import traceback
import tweepy
import json
import urllib
import logging
# assuming pythonInput.txt contains each of the 4 oauth elements (1 per line)
#API_KEY
#API_SECRET
#ACCESS_TOKEN
#ACCESS_TOKEN_SECRET

#logging.captureWarnings(True)

import requests
requests.packages.urllib3.disable_warnings()

def search(keyword, amount):

    file = open('/var/www/passwords/pythonInput.txt')
    fields = []
    for line in file:
        fields.append(line.strip('\n'))

    auth = tweepy.OAuthHandler(fields[0], fields[1])
    auth.set_access_token(fields[2], fields[3])
    api = tweepy.API(auth)
    text = api.search(q=keyword + " -RT", rpp=500, count=100, include_entities=True)
    returnText = []
    i = 1;
   #getting at tweet back based on it s id 
  #  status = api.get_status(id="112652479837110273").text
  #  print status

    for a in text:
        if a.text.lower().count(" " + keyword + " ") > 0:
            conn = urllib.urlopen("http://www.wdyl.com/profanity?q="+ urllib.quote(a.text.encode("utf-8"))) 
            response = conn.read()
            if json.loads(response)["response"] == "false" :
                #if not a.text in returnText :
                returnText.append({"Text": a.text, "Author": a.author.screen_name, "TweetID": a.id})
                i = i+1
                if i > int(amount) :
                     break

    return returnText

def main(keyword, amount):
    print json.dumps(search(keyword, amount))

def remove_non_ascii(text):
    return text.join(i for i in text if ord(i)<128)

def write_log(message):
    f_log = open("log.txt", 'a')
    #f_log.write(message)
    print message
    f_log.close()
    
if __name__ == '__main__':
    main(sys.argv[1], sys.argv[2])
