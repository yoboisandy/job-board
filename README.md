# Job Board API


## Steps to Setup

 1. Clone this Project
 2. run `composer install`
 3. run `cp .env.example .env`
 4. run `php artisan key:generate`
 5. run `php artisan migrate --seed` which will seed 2 users and 5 job listings.
 6. add your smtp credential in `.env`
 7. run `php artisan queue:work` in separate terminal
 8. then serve the project by running `php artisan serve`
 9. then you can start testing api by using following credentials.
 
 **Job Seeker**
 email: test@user.com
 password: password

**Employer**
email: test@employer.com
password: password

You can access API documentation from this link: https://documenter.getpostman.com/view/16415951/2sA3rwNaB1

If you want to test then click on `Run On Postman` button on the top right corner then you can fork this collection and test all apis using above credentials.
