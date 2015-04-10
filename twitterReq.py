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
# assuming twitter_authentication.py contains each of the 4 oauth elements (1 per line)
#from twitter_authentication import API_KEY, API_SECRET, ACCESS_TOKEN, ACCESS_TOKEN_SECRET
API_KEY = 'V6LusI8cyP0ePYKwIQCgKiPoe'
API_SECRET = 'NnY2sC0JZWw3BHfeqMSTY9iUQfwUHv4lRAKs3YvdOt70EAkpTI'
ACCESS_TOKEN = '2399957756-FdFxU8AAykuhA7ieWSyIbX4WCHNG9ZENNvDjRQY'
ACCESS_TOKEN_SECRET = 'GDJgTSWBjtxKtj8TKgIlouN0cOjnpIFizq07e4WgTcuUa'

def search(keyword, amount):

    auth = tweepy.OAuthHandler(API_KEY, API_SECRET)
    auth.set_access_token(ACCESS_TOKEN, ACCESS_TOKEN_SECRET)
    api = tweepy.API(auth)
    text = api.search(q=keyword, rpp=500, count=100, include_entities=True)
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
