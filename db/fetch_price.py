import requests
import time
import json

with open('tokens.json', 'r') as file:
    data = json.load(file)

tokens = [[item['id'], item['address']] for item in data]

with open('prices.json', 'a') as file:
    while True:
        time.sleep(600)
        for token in tokens:
            print(token)
            uri = "https://api.dexscreener.com/latest/dex/tokens/"+ token[1]
            headers = {}
            response = requests.get(
                uri, headers=headers
            )

            if response.status_code == 200:
                data = response.json()
            print(data['pairs'][0]['priceUsd'])
