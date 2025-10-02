# Autoboxex Backend
![](https://bahiaxip.com/image/post/main/ic8Py6OvnFyJCUHCxM1J1s0DPkHnLnPmLAZsU6Mu.jpeg)
Proyecto backend para administración de taller Autoboxex, construido con Laravel 12, basado en PHP 8.3.4 y DB SQL Server.

## Como iniciar el proyecto
Es importante tener el archivo env para la correcta conexión a la base de datos. La conexión requiere los drivers de SQL Server provistos por Microsoft para PHP 8.3
```sh
git init
git checkout -b <rama>
git remote add <variable_remota> <url_repo>
git pull <rama> <origen_remoto>
```

```sh
php artisan key:generate
php artisan migrate:refresh --seed
php artisan serve --port=80
```