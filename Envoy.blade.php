@servers(['prod' => '-p 233 deployer@low.bi'])

@setup
$repository = 'git@git.codegeass.cc:qqjt/lowlog.git';
$releases_dir = '/var/www/lowlog/releases';
$app_dir = '/var/www/lowlog';
$release = date('YmdHis');
$new_release_dir = $releases_dir .'/'. $release;
@endsetup

@story('deploy')
clone_repository
run_composer
update_symlinks
update_cache
fix_file_permissions
@endstory

@task('clone_repository')
echo 'Cloning repository'
[ -d {{ $releases_dir }} ] || mkdir {{ $releases_dir }}
git clone --depth 1 {{ $repository }} {{ $new_release_dir }}
@endtask

@task('run_composer')
echo "Starting deployment ({{ $release }})"
cd {{ $new_release_dir }}
composer install --prefer-dist --no-scripts -q -o
@endtask

@task('update_symlinks')
echo "Linking storage directory"
rm -rf {{ $new_release_dir }}/storage
ln -nfs {{ $app_dir }}/storage {{ $new_release_dir }}/storage

echo 'Linking uploads directory'
ln -nfs {{ $app_dir }}/storage/app/public {{ $new_release_dir }}/public/storage

echo 'Linking .env file'
ln -nfs {{ $app_dir }}/.env {{ $new_release_dir }}/.env

echo 'Linking current release'
ln -nfs {{ $new_release_dir }} {{ $app_dir }}/current
@endtask

@task('update_cache')
echo "Updating cache deployment ({{ $release }})"
cd {{ $app_dir }}/current
php artisan config:cache
php artisan route:cache
php artisan view:clear
php artisan httpcache:clear
@endtask

@task('fix_file_permissions')
echo 'Updating file permissions'
cd {{ $app_dir }}/current
chgrp -R www-data bootstrap/cache
chmod -R ug+rwx bootstrap/cache
@endtask