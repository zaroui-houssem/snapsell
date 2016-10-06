# config valid only for current version of Capistrano
lock '3.6.1'

set :application, 'snapsell_app'
set :repo_url, 'https://github.com/zaroui-houssem/snapsell.git'

# Default branch is :master
# ask :branch, `git rev-parse --abbrev-ref HEAD`.chomp

# Default deploy_to directory is /var/www/my_app_name
 set :deploy_to, '/var/www/snapsell'

# Default value for :scm is :git
# set :scm, :git

# Default value for :format is :airbrussh.
# set :format, :airbrussh

# You can configure the Airbrussh format using :format_options.
# These are the defaults.
# set :format_options, command_output: true, log_file: 'log/capistrano.log', color: :auto, truncate: :auto

# Default value for :pty is false
# set :pty, true

# Default value for :linked_files is []
set :linked_files, ["app/config/parameters.yml"]

# Default value for linked_dirs is []
set :linked_dirs, ["web/images", "web/uploads/media"]
set :use_composer, true
set :update_vendors, true



# Default value for default_env is {}
# set :default_env, { path: "/opt/ruby/bin:$PATH" }

# Default value for keep_releases is 5
# set :keep_releases, 5
namespace :deploy do
    after :updated, :composer do
        on roles(:web) do
            within release_path do
                execute :composer, :install
            end
        end
    end
end