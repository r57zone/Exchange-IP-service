# En: Exchange IP service
Simple IP exchange service.

## API Methods

* Create a user, register a nickname, and obtain a key:

```exip.php?method=create&user=testuser```

* Update the IP address:

```exip.php?method=set&user=testuser&key=<USER_KEY>```

* Get the IP address:

```exip.php?method=get&user=testuser```

* Remove the login:

```exip.php?method=rm&user=testuser&key=<RootPassword>```

## Setup
Create a folder to store data on the server, by default "users", with read and write permissions.


# Ru: Сервис обмена IP адресами
Простая служба обмена IP.


## Методы API

* Создать пользователя, регистрация ника и получение ключа:

```exip.php?method=create&user=testuser```


* Обновление IP адреса:

```exip.php?method=set&user=testuser&key=<USER_KEY>```


* Получение IP адреса:

```exip.php?method=get&user=testuser```


* Удаление логина:

```exip.php?method=rm&user=testuser&key=<RootPassword>```

## Настройка
Создайте папку для хранения данных на сервере, по умолчанию "users", с правами на запись и чтение.