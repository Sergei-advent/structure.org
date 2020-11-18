# structure.org
API for organization's intranet, which works with organizational and functional company structure
# Installation
1. git clone https://github.com/Sergei-advent/structure.org
2. composer install
3. php artisan key:generate
3. php artisan migrate --seed
# Settings
1. standart Laravel settings in .env
2. MAIN_APP_API - url main app
3. MAIN_APP_AUTH_ENDPOINT - Endpoint in main app for autorization. It must returns user email, token and role_id. List role and permission can be setting in database/seeds/RoleSeed. It must setting before php artisan migrate --seed.
# Link on frontend example
https://github.com/Sergei-advent/structure.org_frontend_example
# TODO
Plans for the future:
1. Add an admin panel where you can configure variable of env file, list roles and permission
2. Add check permission role in CRUD operation
