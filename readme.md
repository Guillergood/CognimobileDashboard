![Cognimobile Dashboard](https://github.com/Guillergood/CognimobileDashboard/blob/master/Logo%20final.png?raw=true)

# Cognimobile Dashboard

This dashboard is part of the [Cognimobile Project](https://github.com/Guillergood/CogniMobilePlatform)

## What's inside

- Adminpanel based on [CoreUI theme](https://coreui.io/): with default one admin user (_admin@admin.com/password_) and two roles
- Users/Roles/permissions management function (based on our own code similar to Spatie Roles-Permissions)
- One demo CRUD for Products management - name/description/price
- Everything that is needed for CRUDs: model+migration+controller+requests+views

## Screenshots

![Laravel + CoreUI screenshot 01](https://laraveldaily.com/wp-content/uploads/2019/04/Screen-Shot-2019-04-17-at-5.49.46-AM.png)

![Laravel + CoreUI screenshot 02](https://laraveldaily.com/wp-content/uploads/2019/04/Screen-Shot-2019-04-17-at-5.51.26-AM.png)

![Laravel + CoreUI screenshot 03](https://laraveldaily.com/wp-content/uploads/2019/04/Screen-Shot-2019-04-17-at-5.51.10-AM.png)

![Laravel + CoreUI screenshot 04](https://laraveldaily.com/wp-content/uploads/2019/04/Screen-Shot-2019-04-17-at-5.52.03-AM.png)



## Prerequisites

The server to host the project needs:
- Composer
- A database

## How to use

- Clone the repository with __git clone__
- Copy __.env.example__ file to __.env__ and edit database credentials there
- Run __composer install__
- Run __php artisan key:generate__
- Run __php artisan migrate --seed__ (it has some seeded data for your testing)
- That's it: launch the main URL or go to __/login__ and login with default credentials __admin@admin.com__ - __password__

## License

Basically, feel free to use and re-use any way you want.

---

## More from our LaravelDaily Team

- Check out our adminpanel generator [QuickAdminPanel](https://quickadminpanel.com)
- Read our [Blog with Laravel Tutorials](https://laraveldaily.com)
- FREE E-book: [50 Laravel Quick Tips (and counting)](https://laraveldaily.com/free-e-book-40-laravel-quick-tips-and-counting/)
- Subscribe to our [YouTube channel Laravel Business](https://www.youtube.com/channel/UCTuplgOBi6tJIlesIboymGA)
- Enroll in our [Laravel Online Courses](https://laraveldaily.teachable.com/)
