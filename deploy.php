<?php
namespace Deployer;

require 'recipe/common.php';

// Project name
set('application', 'my_project');

// Project repository
set('repository', 'https://github.com/Wolfi9307/deploy_test_core.git');

// [Optional] Allocate tty for git clone. Default value is false.
set('git_tty', true);

// Shared files/dirs between deploys 
set('shared_files', []);
set('shared_dirs', []);

// Writable dirs by web server 
set('writable_dirs', []);


// Hosts

host('212.22.75.95')
	->stage('production')
	->user('deployer')
	->set('deploy_path', '/var/www/laravel-deployer-demo');
    

// Tasks

// объявляем метод для подтягивания ресурсов из репозитория
task('get_resources', function (){
	$path = get('deploy_path') . DIRECTORY_SEPARATOR . 'releases' . DIRECTORY_SEPARATOR . get('release_name') . DIRECTORY_SEPARATOR . 'resources';
	run('git clone https://github.com/Wolfi9307/deploy_test_resources.git ' . $path);
});

desc('Deploy your project');
task('deploy', [
    'deploy:info',
    'deploy:prepare',
    'deploy:lock',
    'deploy:release',
    'deploy:update_code',
    // после обновления кода, обновляем ресуры
    'get_resources',
    'deploy:shared',
    'deploy:writable',
   // 'deploy:vendors',
    'deploy:clear_paths',
    'deploy:symlink',
    'deploy:unlock',
    'cleanup',
    'success'
]);

// [Optional] If deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');
