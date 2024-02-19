import csv
import smtplib
import time
from email.mime.text import MIMEText
from email.mime.multipart import MIMEMultipart

# SMTP server configuration
smtp_server = 'email-smtp.ap-south-1.amazonaws.com'
smtp_port = 587  # Choose one of the ports: 25, 587, or 2587
smtp_username = 'AKIAUCJB4IREAN7YN34F'  # Your SMTP username provided by Amazon SES
smtp_password = 'BB+xso+iRAOq+yrz4ZIOf3q79FbuartlBPhR47t88Dle'  # Your SMTP password provided by Amazon SES

print(f'Script Started')

# Load registration data from CSV
file_path = 'participants.csv'

def load_data_from_csv(file_path):
    data = []
    with open(file_path, 'r') as csvfile:
        reader = csv.DictReader(csvfile)
        for row in reader:
            data.append(row)
    return data

# Read HTML content from email.html
def read_html_content(filename):
    with open(filename, 'r') as file:
        return file.read()

# Send email with HTML content
def send_registration_email(sender, recipient, id, name, qr, event, html_content, reply_to):
    subject = f"Registration for {name} at Crescendo {event} 2024"
    body_html = html_content.replace('{id}', id).replace('{name}', name).replace('{event}', event).replace('{QRImage}', qr)

    message = MIMEMultipart()
    message['From'] = sender
    message['To'] = recipient
    message['Subject'] = subject
    message['Reply-To'] = reply_to

    # Attach HTML content
    message.attach(MIMEText(body_html, 'html'))

    with smtplib.SMTP(smtp_server, smtp_port) as server:
        server.starttls()
        server.login(smtp_username, smtp_password)
        server.sendmail(sender, recipient, message.as_string())

# Load registration data from CSV file
registrations_data = load_data_from_csv(file_path)

# Read HTML content from email.html
html_content = read_html_content('email.html')

# Set the number of emails to send per hour
emails_per_hour = 3000

# Calculate the time interval in seconds for sending each batch of emails
time_interval = 3600 / emails_per_hour

# Set the sender name and email
sender_name = 'CRESCENDO Registrations'
sender_email = 'registrations@crescendo.hxrsh.tech'
reply_to_address = 'support@crescendo.hxrsh.tech'

# Send emails in batches
for i in range(0, len(registrations_data), emails_per_hour):
    for data in registrations_data[i:i+emails_per_hour]:
        send_registration_email(f'{sender_name} <{sender_email}>', data['EMAIL'], data['ID'], data['NAME'], data['QR'], data['EVENT'], html_content, reply_to_address)
        
        print(f'Sent email to: {data["EMAIL"]}')
        time.sleep(time_interval)

print('All emails sent!')
