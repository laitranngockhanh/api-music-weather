
: ${PORT:=10000}

# Xóa cache
php artisan config:clear
php artisan route:clear
php artisan optimize

# Chạy PHP server với giới hạn upload lớn hơn
exec php \
  -d upload_max_filesize=50M \
  -d post_max_size=55M \
  -d memory_limit=256M \
  -d max_execution_time=300 \
  -d max_input_time=300 \
  -S 0.0.0.0:$PORT \
  -t public