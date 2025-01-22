import requests
import time
import json
from datetime import datetime

with open('db/tokens.json', 'r') as file:
    data = json.load(file)

tokens = [[item['id'], item['address']] for item in data]

while True:
    with open('db/prices.json', 'r') as file:
        
        try:
            data = json.load(file)
        except json.JSONDecodeError:
            data = []

        for token in tokens:
            
            uri = "https://api.dexscreener.com/latest/dex/tokens/"+ token[1]
            headers = {}
            response = requests.get(
                uri, headers=headers
            )

            if response.status_code == 200:
                response = response.json()
                
            price = response['pairs'][0]['priceUsd']
            
            token_id = str(time.time()).replace('.','')
            
            new_price = {
                "id": int(token_id),
                "token_id": token[0],
                "date": datetime.now().isoformat(),
                "price": price
            }
            
            print(data)
            data.append(new_price)
            print(data)

            with open('db/prices.json', 'w') as output:
                json.dump(data, output, indent=4)
            time.sleep(60)
