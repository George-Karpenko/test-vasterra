## Настройка проекта

Это приложение содержит как серверную часть, так и пользовательскую часть в одном репозитории.

```
├── README.md
├── .docker
├── backend
├── docker-compose.yml
└── frontend
```

### Клонирование репозитория

```bash
git clone https://github.com/George-Karpenko/test-vasterra.git

```

Перейдите в корневой каталог.

```bash
$ cd test-vasterra
```

Копировать в папке backend файл .env.example и переименовать в .env. В файле переменной GOOGLE_EMAIL_DRIVE_PERMISSION присвоить свою почту. GOOGLE_* конфиг для google sheets api.

### Запуск контейнеров

Запустите контейнеры с помощью docker-compose

```bash
$ docker-compose up -d
```

Фронт запускается отдельно через npm.

Так же нужно сделать генерацию ключа в laravel и миграции. Для этого нужно выполнить команды

```bash
$ docker compose exec php bash
$ cd backend
$ composer install
$ php artisan key:generate
$ php artisan migrate
```
