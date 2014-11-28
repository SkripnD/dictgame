set :stage, :develop

set :branch,        'master'
set :deploy_to,     ''
set :domain,        ''
set :url,	        'http://' + fetch(:domain)
set :http_auth,     false
set :http_login,    ''
set :http_password, ''
set :http_hash,     ''

server fetch(:domain), user: '', roles: %w{web app},
ssh_options: 
{
    forward_agent: true,
}