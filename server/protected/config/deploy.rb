# Main settings
# --------------------------------------------------

set :application,    ''
set :repo_url,       ''
set :scm,            :git
set :deploy_via,     :remote_cache
set :keep_releases,  5

set :linked_dirs,  %w{web/public/uploads web/assets protected/runtime vendor protected/config/local}

# Tasks
# --------------------------------------------------
        
namespace :deploy do

  desc 'Prepare application'
  task :prepare do
    on roles(:all) do
      #within release_path do
        execute "cd #{release_path} && composer install"
        execute "cd #{release_path}/protected && php yiic.php migrate --interactive=0"
        
        if fetch(:stage) == :production
          execute "rm -rf #{release_path}/web/html"
        end    
        
        if fetch(:http_auth)
          invoke 'deploy:protect'
        end
         
      #end
    end
  end
  
  desc 'Restart application'
  task :restart do
    on roles(:all) do
    
      curl_options = "-s"
      if fetch(:http_auth)
      	curl_options = curl_options + " --user #{fetch(:http_login)}:#{fetch(:http_password)}"
      end
      
      execute "echo '<?php clearstatcache(true); opcache_reset();?>' > #{release_path}/web/reset.php"
      execute "curl #{curl_options} #{fetch(:url)}/reset.php"
      execute "rm -f #{release_path}/web/reset.php"
      
    end
  end
  
  desc 'Protect through .htpasswd'
  task :protect do
    on roles(:all) do
      execute "echo '#{fetch(:http_login)}:#{fetch(:http_hash)}' >> #{File.join(release_path, '.htpasswd')}"
      execute "echo 'AuthType Basic' >> #{File.join(release_path, '.htaccess')}"
      execute "echo 'AuthName \"Restricted\"' >> #{File.join(release_path, '.htaccess')}"
      execute "echo 'AuthUserFile #{File.join(release_path, '.htpasswd')}' >> #{File.join(release_path, '.htaccess')}"
      execute "echo 'Require valid-user' >> #{File.join(release_path, '.htaccess')}"
    end
  end
  
  desc 'Enable maintenance mode'
  task :lock do
    on roles(:all) do
      execute "cd #{release_path} && php command.php maintain on"
    end
  end

  desc 'Disable maintenance mode'
  task :unlock do
    on roles(:all) do
      execute "cd #{release_path} && php command.php maintain off"
    end
  end

  # Deploy flow
  # --------------------------------------------------
  
  after :updated,    'deploy:prepare'
  after :publishing, 'deploy:restart'
  after :finishing,  'deploy:cleanup'
  
end