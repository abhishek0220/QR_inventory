import logging
import azure.functions as func
from azure.cosmos import exceptions, CosmosClient, PartitionKey
import hashlib
import os

def encodetxt(password):
    encodetxt = hashlib.md5(password.encode()) 
    encode_pass = encodetxt.hexdigest()
    return encode_pass

def main(req: func.HttpRequest) -> func.HttpResponse:
    logging.info('Python HTTP trigger function processed a request.')
    try:
        req_body = req.get_json()
        username = req_body['username']
        password = req_body['password']
    except:
        return func.HttpResponse(
             "Please pass username and password in the request body",
             status_code=400
        )
    try:
        endpoint = "https://localhost:8081/" 
        key = os.environ.get('Datakey')
        client = CosmosClient(endpoint, key)
    except:
        return func.HttpResponse(
             "Invalid Database Credentials",
             status_code=400
        )
    database_name = 'Air-Inventory'
    database = client.create_database_if_not_exists(id=database_name)
    container_name = 'user'
    container = database.create_container_if_not_exists(
        id=container_name, 
        partition_key=PartitionKey(path="/username"),
        offer_throughput=400
    )
    query = "SELECT * FROM ALL"
    items = list(container.query_items(
        query=query,
        enable_cross_partition_query=True
    ))
    logined = False
    for item in items:
        if(str(item['username']) == username):
            container.delete_item(item,item['username'])
            new_it = item
            new_it['password'] = encodetxt(password)
            container.create_item(body=new_it)
            return func.HttpResponse(f"Updated password")
    return func.HttpResponse(
        "Unable to change",
        status_code=400
    )
