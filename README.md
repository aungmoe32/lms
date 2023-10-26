# Learning Management System
Simple Learning Management System
### Used things
- Laravel 8
- Vue 2
- Vuetify for UI
- Vuex and Vue Router
- Laravel sanctum for api

## Table of Contents
- [Installation](#installation)
- [Usage](#usage)
- [Features](#features)
    - [Student](#student)
    - [Instructor](#instructor)
    - [Admin](#admin)
- [Payment](#payment)
- [Contact me](#contact-me)




## Installation
Install npm packages
```
npm install
```
Mix assets
```
npm run dev
```
### For backend
Install composer packages
```
composer install
```
Migrate database tables
```
php artisan migrate
```
Seed tables
```
php artisan db:seed
```
Create symbolic link
```
php artisan storage:link
```
Serve
```
php artisan serve
```



## Usage
- For first admin role, add a record with role_id (3) to role_user table

## Features
- Login and register
- Search courses
- Filter courses with categories, prices and level
- Give ratings and comments on courses
- View instructor lists
- [Paypal sandbox for payment](#payment)
- A course can have many sections and a section can have many lectures
- A lecture content can be one of the four types(video, audio, document, text)
- Accept file types : 
```
video: ['video/mp4', 'video/quicktime'],
audio: ['audio/mpeg', 'audio/wav', 'audio/x-m4a'],
document: ['application/pdf'],
```
- It has three roles.
    - [Student](#student)
    - [Instructor](#instructor)
    - [Admin](#admin)
- Permissions are seperated according to roles
### Student
- Enroll courses
- Learn courses with curriculums
- Give ratings and comments on courses
- Can be an Instructor
### Instructor
- CRUD courses
- CRUD curriculums
- Get an instructor profile
- Monetize with paid courses
### Admin
- CRUD course categories
- CRUD users
- Edit user roles
- Set active or inactive to users
## Payment
Test with Paypal sandbox :
1. Create a Paypal developer account.
2. Copy CLIENT_ID and CLIENT_SECRET
Add CLIENT_ID and CLIENT_SECRET to .env file
```env
...
PAYPAL_SANDBOX_CLIENT_ID=...
PAYPAL_SANDBOX_CLIENT_SECRET=...
```
## Contact Me
aungmoemt32@gmail.com
