# clothen
E-Commerce exploration

# Project installation on your local machine
```
docker-compose build -d
docker-compose up -d
```

Then enter into docker's container to install vendors

```
docker exec -it www_clothen bash
```

Install vendors
```
composer install
```

Create database and load fixtures
```
cd clothen
php bin/console doctrine:databse:create
php bin/console doctrine:migrations:migrate
php bin/console doctrine:fixtures:load
```

# Connect to the platform
You should access to your project at :
http://localhost:8741/login

and connect with this account : 
john@doe.com / password


# MAILDEV
http://localhost:8081

# ADMIN UI
http://localhost:8741/admin

# PHPMYADMIN
http://localhost:8080
