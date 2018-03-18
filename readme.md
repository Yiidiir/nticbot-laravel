# NTICBot
NTICBot is a simple Messenger Bot Server with a backend CRUD Interface that allows students to access resources (courses,TPs etc...) within messenger or via a web interface and uses [Google Drive API](https://developers.google.com/drive/) to fetch files.


## Install
1.  Clone this repository
```
git clone https://github.com/Yiidiir/nticbot-laravel.git && cd nticbot-laravel
```
2. Update ``.env`` file with your details.
3. Run Composer
```
php composer.phar install
```
4. Run artisan 
```
php artisan migrate:fresh --seed
```
## TODO
Things needed to improve (please send a PR if you did :) 

- Setup Google Drive API
- Use pusher for announcements (Messenger)
- Setup cron for delayed announcements
- Use Listeners for Notifications (Messenger)
- Make Messenger as a Service (other than just a controller)
- Add Language Support
- Use Laravel 5.6 & Bootstrap4 ?

## Contributing

Contributions are Welcome from everyone, just open a pull request!


## License

This code is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).
