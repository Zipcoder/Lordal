# Lordal
* This project is the new version of the former _portal.zipcode.rocks_
* This project is built with `Larvel 5.7` and `VueJS 2.x`

## Software Prerequisitves
* Ensure that the following software is installed on your local machine before attempting to run this project:
	* PHP 7.2
	* Composer


## Running the Project
* To run locally:
	* copy `.env.example` to the `.env` directory

* Ensure the following variables have been set

```
DB_CONNECTION=sqlite
QUEUE_CONNECTION=database

GITHUB_CLIENT={GITHUB OUTH CLIENT KEY}
GITHUB_CLIENT_SECRET={GITHUB OUTH SECRET KEY}
GITHUB_URL={https://localhost:8000/github/callback}

GITHUB_WEBHOOK_SECRET={webhook secret key}

GOOGLE_CONSUMER_KEY=
GOOGLE_CONSUMER_SECRET=
GOOGLE_URL={https://localhost:8000/auth/google/callback"}
```





### External Instructions
* Click [here](https://laravel.com/docs/5.7/valet) for instructions on setting up a local server for Laravel that can be linked with SSL.
	* Google requires that your app has SSL set up. You can set this up with a self signed cert, but this can be a bit complicated.
* Click [here](https://developer.github.com/apps/building-oauth-apps/) to learn more about oath tokens for github.
* Click [here](https://developers.google.com/adwords/api/docs/guides/authentication) to learn more about oath tokens for google.


### Provisioning the Application
When you set up your local instance with [Laravel Valet](https://laravel.com/docs/5.8/valet), make sure to change your callback urls from `localhost` to whatever url you gave your site.

Once you have all of that set up you should be ready to provision the app.
The local database is set up to use `SQLite`.
However, it is recommended that you run on a local instance of `MySQL`, as that is what the production server runs.
Most likely it will not affect your code.
However, `SQLite` does not support foreign keys and ignores commands for the orm to set them up. If you are working with data types for the ORM then you most likely will have to test it like this first as not doing so can introduce bugs into production. Click [here](https://docs.docker.com/samples/library/mysql/) for information on how to do that securely.

Once you do you can set up the credentials in the `.env` file. 

If you are not working with datatypes in the ORM then you should be good to go with sqlite. Simply create the file with 

```bash
touch database/database.sqlite
```

Once your database is provisioned you need to set up the app keys. You can do so with 

```bash
php artisan key:generate
php artisan passport:keys
php artisan storage:link
```

That should generate secret keys and modify your .env file to have a new app key. 

Once this is done you need to provision the databse. The following should be all you need

```bash
# This command will initialize your databse
# If you add a new migration you can run it 
# again to run new migrations
php artisan migrate

# If your database gets messed up and you want 
# to start from scratch you can also run
php artisan migrate:fresh
```

After that you can fill up the databse with sample data using

```bash
php artisan db:seed
```

Lastly to set up your front end assets (VueJS, bootstrap, webpack, etc), run the asset compilation

```bash
# This will compile your assets in development mode
yarn run dev

# This will compile assets in development mode and 
# then compile every time there is a file change
yarn run watch
```