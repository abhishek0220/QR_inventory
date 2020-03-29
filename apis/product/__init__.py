import logging
import azure.functions as func
from azure.cosmos import exceptions, CosmosClient, PartitionKey
import os
import json

def main(req: func.HttpRequest) -> func.HttpResponse:
    try:
        req_body = req.get_json()
        Product_ID = req_body['Product-ID']
    except:
        return func.HttpResponse(
         "Please pass Product-ID request body",
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
    try:
        database_name = 'Air-Inventory'
        database = client.create_database_if_not_exists(id=database_name)
        container_name = 'products'
        container = database.create_container_if_not_exists(
            id=container_name, 
            partition_key=PartitionKey(path="/Type"),
            offer_throughput=400
        )
        query = "SELECT * FROM ALL"
        items = list(container.query_items(
            query=query,
            enable_cross_partition_query=True
        ))
        for item in items:
            if(Product_ID == item["id"]):
                tem = {}
                tem['Product-Name'] = item['Product-Name']
                tem['Height'] = item['Height']
                tem['Width'] = item['Width']
                tem['Breadth'] = item['Breadth']
                tem['Weight'] = item['Weight']
                tem['Type'] = item['Type']
                tem['order'] = ['Product-Name','Height','Width','Breadth','Weight']
                out = json.dumps(tem)
                break
    except:
        out="failed"
    return func.HttpResponse(f"{out}")
