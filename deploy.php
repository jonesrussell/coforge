<?php

namespace Deployer;

require 'recipe/laravel.php';

// Config

set('repository', 'git@github-coforge:jonesrussell/coforge.git');
set('keep_releases', 5);

add('shared_files', []);
add('shared_dirs', []);
add('writable_dirs', []);

task('deploy:build_assets', function (): void {
    run('bash -lc "source ~/.nvm/nvm.sh 2>/dev/null; cd {{release_path}} && npm ci && npm run build:ssr"');
});
after('deploy:vendors', 'deploy:build_assets');

// Hosts

host('coforge.xyz')
    ->set('remote_user', 'deployer')
    ->set('deploy_path', '~/coforge');

// Hooks

after('deploy:failed', 'deploy:unlock');
before('deploy:symlink', 'artisan:migrate');

task('deploy:restart_services', function (): void {
    run('cd {{release_path}} && {{bin/php}} artisan horizon:terminate', ['allow_failure' => true]);
    run('cd {{release_path}} && {{bin/php}} artisan inertia:stop-ssr', ['allow_failure' => true]);
    run('systemctl --user restart coforge-schedule-work.service', ['allow_failure' => true]);
});
after('deploy:symlink', 'deploy:restart_services');
task('deploy:reload_php_fpm', function (): void {
    run('sudo systemctl restart php8.4-fpm', ['allow_failure' => true]);
});
after('deploy:restart_services', 'deploy:reload_php_fpm');
