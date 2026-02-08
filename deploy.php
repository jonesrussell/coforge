<?php
namespace Deployer;

require 'recipe/laravel.php';

// Config

set('repository', 'git@github-coforge:jonesrussell/coforge.git');

add('shared_files', []);
add('shared_dirs', []);
add('writable_dirs', []);

// Hosts

host('coforge.xyz')
    ->set('remote_user', 'deployer')
    ->set('deploy_path', '~/coforge');

// Hooks

after('deploy:failed', 'deploy:unlock');
