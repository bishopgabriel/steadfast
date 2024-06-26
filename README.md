# SteadFast test:

### Create a simple voting app

<p><strong style="color:orange">NOTE:</strong> Follow the steps below to run the project.</p>
<p>The database is sqlite. A live production will entail either of MySQL, MSSQL, or Postgres...and the changes can be made in the .env file of the project.</p>


## How to setup and run project

- step 1: clone the repository, then run compose

```cmd
cd laravel-api
```
```cmd
composer install
```
- step 2: Change the settings in the .env file accordingly
```php
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=example@gmail.com
MAIL_PASSWORD=2324r4f4f4f
MAIL_ENCRYPTION=tsl
MAIL_FROM_ADDRESS="example@gmail.com"
MAIL_FROM_NAME="${APP_NAME}"
VOTE_EMAIL_RECIPIENT=hello@example.com
```

- Note: the VOTE_EMAIL_RECIPIENT is the email where the scores for the vote will be sent every day at 23:59
```cmd
php artisan serve --port=8000
```

- To see the scheduled jobs, run the command
```cmd
php artisan schedule:list
```


- step 3: Build the frontend

```cmd
cd frontend
```
```cmd
npm install
```
```cmd
npm run dev
```

- to run the production for the frontend, you can use the command
```cmd
npm run build --prod
```

- step 4 (optional): Building docker containers for the project. Navigate to the root of the folder and run
```cmd
docker compose up build -d
```

