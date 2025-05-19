
: ${PORT:=10000}

# Xóa cache
php artisan config:clear
php artisan route:clear
php artisan optimize

# Chạy PHP server
exec php -S 0.0.0.0:$PORT -t public