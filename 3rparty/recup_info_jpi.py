import requests
url_address = ['http://192.168.1.201:8080/?action=__NET_CMD__&__FROM_MAIN_APP__=true&net=action&action_ex=_GET_ACTIONS_EVENTS_']
for i in url_address:
    resp = requests.get(i).json()
print resp