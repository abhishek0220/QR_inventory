import logging
import azure.functions as func
from azure.cosmos import exceptions, CosmosClient, PartitionKey
from sendgrid import SendGridAPIClient
from sendgrid.helpers.mail import Mail
import hashlib
import uuid
import os

def new_user(name, username, password, email):
    encodetxt = hashlib.md5(password.encode()) 
    encode_pass = encodetxt.hexdigest()
    sample_user = {
        'id': str(uuid.uuid4()),
        'name' : name,
        'username' : username,
        'password' : encode_pass,
        'emailid' : email 
    }
    return sample_user
def main(req: func.HttpRequest) -> func.HttpResponse:
    logging.info('Python HTTP trigger function processed a request.')
    try:
        req_body = req.get_json()
        name = req_body['name']
        email = req_body['email']
        username = req_body['username']
        password = req_body['password']
        admin = req_body['admin']
        adminpass = req_body['adminpass']
    except:
        return func.HttpResponse(
             "Please pass all details name, email, username, password, admin, adminpass in the request body",
             status_code=400
        )
    if(str(admin+adminpass) != "abhishek0220createuser"):
        return func.HttpResponse(
             "Authentication Failed",
             status_code=401
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
    for item in items:
        if(str(item['username']) == username):
            return func.HttpResponse(
            "Username Exists",
            status_code=406
        )
    newuser = new_user(name, username, password, email)
    container.create_item(body=newuser)
    link = "http://localhost/sih/index.php"
    message = Mail(
        from_email='admin@binarybeasts.com',
        to_emails=email,
        subject='Welcome to QR Based Inventory',
        html_content='Welcome to QR Based Inventory web application developed by Binary Beasts. Note down your Credientials - <br>Username : <strong>'+username+'</strong><br>Password : <strong>'+password+'</strong><br>Link : <strong>'+link+'</strong><br><br><br>Message by - Abhishek Chaudhary<br>abhishek0220@studentpartner.com')
    try:
        sg = SendGridAPIClient(os.environ.get('SENDGRID_API_KEY'))
        response = sg.send(message)
    except Exception as e:
        print(e.message)
    return func.HttpResponse(f"User Added")
