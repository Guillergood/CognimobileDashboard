![Cognimobile Dashboard](https://github.com/Guillergood/CognimobileDashboard/blob/master/images/Logo%20final.png?raw=true)

# Cognimobile Dashboard

This dashboard is part of the [Cognimobile Project](https://github.com/Guillergood/CogniMobilePlatform)

## What's inside

- Adminpanel based on [CoreUI theme](https://coreui.io/): with default one admin user (_admin@admin.com/password_) and two roles
- Users/Roles/permissions management function (based on our own code similar to Spatie Roles-Permissions)
- One demo CRUD for Products management - name/description/price
- Everything that is needed for CRUDs: model+migration+controller+requests+views
- Based on [Laravel + CoreUI: Adminpanel Boilerplate](https://github.com/LaravelDaily/Laravel-CoreUI-AdminPanel)
- Also included a dashboard to control [aware-micro](https://github.com/denzilferreira/aware-micro)

## Screenshots

![Cognimobile Dashboard screenshot 01](https://github.com/Guillergood/CognimobileDashboard/blob/master/images/1.JPG?raw=true)

![Cognimobile Dashboard screenshot 02](https://github.com/Guillergood/CognimobileDashboard/blob/master/images/2.JPG?raw=true)

![Cognimobile Dashboard screenshot 03](https://github.com/Guillergood/CognimobileDashboard/blob/master/images/3.JPG?raw=true)

![Cognimobile Dashboard screenshot 04](https://github.com/Guillergood/CognimobileDashboard/blob/master/images/4.JPG?raw=true)



## Prerequisites

The server to host the project needs:
- Composer
- A database

## How to use

- Clone the repository with __git clone__
- Edit __.env__  database credentials there
- Run __composer install__
- Run __php artisan key:generate__
- Run __php artisan migrate --seed__ (it has some seeded data for your testing)
- That's it: launch the main URL or go to __/login__ and login with default credentials __admin@admin.com__ - __password__

After the dashboard has been set up, it is needed to configure "aware-micro":
- Edit the *aware-config.json* database credentials inside the /aware-micro folder, it must be the same as __.env__ file edited before.
- To configure more about the study information, sensors and plugins, it should be modified through the *Settings* menu in the dashboard  

## Deployment
It is needed to deploy both instances:
For Cognimobile Dashboard
```
php artisan serve
```

For aware-micro
```
cd aware-micro
screen
./gradlew run
```
## License

MIT

---