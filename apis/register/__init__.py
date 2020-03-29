import logging
import azure.functions as func
from azure.cosmos import exceptions, CosmosClient, PartitionKey
import os
import uuid
import json

def new_product(Product_Name,Height,Width,Breadth,Weight,Type):
    sample_list = {
        'id' : str(uuid.uuid4()),
        'Product-Name' : Product_Name,
        'Height' : Height,
        'Width' : Width,
        'Breadth' : Breadth,
        'Weight' : Weight,
        'Type' : Type
    }
    return sample_list

def main(req: func.HttpRequest) -> func.HttpResponse:
    logging.info('Python HTTP trigger function processed a request.')
    name = req.params.get('name')
    if not name:
        try:
            req_body = req.get_json()
            Product_Name = req_body['Product-Name']
            Height = req_body['Height']
            Width = req_body['Width']
            Breadth = req_body['Breadth']
            Weight = req_body['Weight']
            Type = req_body['Type']
        except:
            return func.HttpResponse(
             "Please pass proper request body",
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
    item = new_product(Product_Name,Height,Width,Breadth,Weight,Type)
    try:
        database_name = 'Air-Inventory'
        database = client.create_database_if_not_exists(id=database_name)
        container_name = 'products'
        container = database.create_container_if_not_exists(
            id=container_name, 
            partition_key=PartitionKey(path="/Type"),
            offer_throughput=400
        )
        container.create_item(body=item)
        item["Status"] = "Registered Successfully"
    except:
        item["Status"] = "Registeration Failed"
    item = json.dumps(item)
    return func.HttpResponse(f"{item}")
