import logging
import azure.functions as func
from azure.cosmos import exceptions, CosmosClient, PartitionKey
from sendgrid import SendGridAPIClient
from sendgrid.helpers.mail import Mail
import os

def main(req: func.HttpRequest) -> func.HttpResponse:
    logging.info('Python HTTP trigger function processed a request.')
    try:
        req_body = req.get_json()
        username = req_body['username']
        code = req_body['code']
    except:
        return func.HttpResponse(
             "Please pass username in the request body",
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
    found = False
    for item in items:
        if(str(item['username']) == username):
            email = item['emailid']
            found = True
            break
    if(found):
        message = Mail(
            from_email='admin@binarybeasts.com',
            to_emails=email,
            subject='Password Reset',
            html_content='OTP to reset password of your QR Based Inventory account <strong>'+code+'</strong>')
        try:
            sg = SendGridAPIClient(os.environ.get('SENDGRID_API_KEY'))
            response = sg.send(message)
            print(response.status_code)
            print(response.body)
            print(response.headers)
        except Exception as e:
            print(e.message)
        return func.HttpResponse(f"Send")
    else:
        return func.HttpResponse(
            "Username not found",
            status_code=400
        )
