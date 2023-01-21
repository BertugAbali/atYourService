## About At You Service

Most of the people these days are tired of working under big companies. For this reason, people who have the ability to work 
from a computer on this platform can offer these skills to people who are looking for service in return for money. This project includes;

- Service provider and customers
- Fully editable profile page
- Responsive structure
- Payment System from Stripe. Paying for customers and earning money for service providers.
- Chat system from Chatify and Pusher. Customers and Service providers conversation with each other.
- Live chat from Chatify. You can help all users.

Github: https://github.com/BertugAbali/atYourService

## How To Deploy

- If any views does not need any login like home page, put these views in HomeController.
- In env. file, there will be a "APP_URL" section. You need to change this to your website index url.
- This project use email verify. If you want to use test email provider, you can sign up to "https://mailtrap.io" and after you created your account, click your profile
,go to "Email Testing" and there will be already created project and inbox but you can delete it and create new one. Click to setting icon and below the smtp settings there will be programming languages. Choose "Laravel 7+" and copy codes right below it. Paste these codes over the same codes in the ".env" file. If you want to use real
email provider then you need to apply one of them and paste their mail codes over the mail codes in the ".env".
- This project use live chat from chatify. You can sign up to chatify and they will give to you your live chat codes. You can paste these codes over the same codes in the "view/includes/footer.blade.php".
- For chat system you need to create a Pusher account (https://pusher.com). After you created, go channels and choose or creat a project. Go app settings and enable clients events. Later, go app keys and copy your keys. Paste these keys right place in ".env" . (there will be pusher codes)
- This project using stripe payment system so you need to create a stripe account. After you create, enable test mode (top right corner). Later, click developers near the "Test mode". Go to "API keys" in left side. Copy your secret and publishable key and paste it to right place in ".env" file. (if you want to use real key not test keys. Do this process after disable test mode). When testing payment, insert "4242 4242 4242 4242" to card number and make valid date for card date.  


## Developer Team

Bertuğ Abalı 2022122882

Youtube Video: https://www.youtube.com/watch?v=erDAHYAeamU
