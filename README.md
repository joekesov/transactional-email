# Laravel Transactional email

## Instalation
1. Clone the repo 
```bash
git clone https://github.com/joekesov/transactional-email.git .
```

2. Copy .env.example file to .env file
```bash
cp .env.example .env
cp codebase/.env.example codebase/.env
```

3. To build the containers
```bash
docker-compose up -d --build
```

4. To enter the container
```bash
docker-compose exec server bash
```

5. To install the project
```bash
composer install
```

6. To migrate the Database 
```bash
php artisan migrate
```

7. In this step you would need to import credentials about the APIs of SendGrid and Mailjet.
You would need accounts for these two systems. I could not provide you any because if there is
   any credentials in this repo they gonna suspend my accounts. I've already tried to put credentials
   here and as a result had to communicate with the support.
To import credentials for Mailjet and Sendgrid you could use the console command:
   ```bash 
   php artisan email_delivery_platform:add
   ```
   7.1. for Mailjet you will have to input
   - What is the url of the Email Delivery Platform []?:
     ```bash
     https://api.mailjet.com/v3.1/send
     ```
   - What is the from email of the Email Delivery Platform []?:
    ```bash
    [the_email_related_with_your_account_into_mailjet_system]
    ```
   - What is the from name of the Email Delivery Platform []?:
    ```bash
    [put_any_name]
    ```
   - Do you wish to add/edit this email delivery platform credentials? (yes/no) [no]:
    ```bash
    yes
    ```
   - What is the name of the Email Delivery Platform? [basicAuth]:
    ```bash
    basicAuth
    ```
   - Please fill up the user name:
    ```bash
    [fill_here_the_mailjet_api_key]
    ```
   - Please fill up the password:
    ```bash
    [fill_the_api_secret_key]
    ```
   7.2. for Sendgrid you will have to input
   - What is the url of the Email Delivery Platform []?:
    ```bash
    https://api.sendgrid.com/v3/mail/send
    ```
   - What is the from email of the Email Delivery Platform []?:
    ```bash
    [once_again_fill_the_email_registered_into_sendgrid]
    ```
   - What is the from name of the Email Delivery Platform []?:
    ```bash
    [any_name]
    ```
    - Do you wish to add/edit this email delivery platform credentials? (yes/no) [no]:
    ```bash
    yes
    ```
    - What is the name of the Email Delivery Platform? [basicAuth]:
    ```bash
    barerToken
    ```
   - Please fill up the token:
    ```bash
    [sendgrid_token]
    ```

8. You should be able to send post request with your favorite tool. I do that with postman.
- in the url field you should fill
```bash
http://localhost:8103/api/send/email
```
- choose headers tab in the tabs bar and add header Accept with value `application/json`
- than choose body -> raw choose `json` as a type and fill with the next json
```bash
{
   "to": [
        {
            "email":"joemailtester@gmail.com",
            "name": "Hacharo"
        }
   ],
   "subject": "Test Email sending with api",
   "contentType": "text/plain",
   "content": "Content"
}
```
The Content Type could be: `text/plain` or `text/html`

9. Once the request is sent you will have to run the queue
```bash
php artisan queue:work
```

10. You could send emails by a console command (the queue should be running)
```bash
php artisan emails:send
```

11. To run the tests
```bash
php artisan test

php artisan test --testsuite=Feature --stop-on-failure
```

##Final thoughts

This task could be structured in a better way. It should be used only as an example.