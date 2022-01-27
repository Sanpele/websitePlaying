# Shopify_Developer_Intern_2022

My (Colin Waughs) submission to the shopify data engineer intern challange

## Simple Image Repo Implemented With PHP

---

### Features

* User sign in.
* Cookies to remember users.
* Guest option for viewing all images in repository
* Users can upload images < 2 mb in size.
* Users also allowed overall quota of 10 mb.
* Users can toggle between viewsing all repo Images and only their pictures.

---

### Selling Points

Realitively clean coding that allows for extension.

Mostly php allowing for lots of control for the developer. 

...

Lots of excercise for your fingers.

Looks like it was built in 2005.

---

### Running:

A simple way to run the code is with php7 installed on your machine to run the project. 

Next you will have to clone the github repo.

Navigate into the project directory, then navigate into the src/ directory.

Once in the src directory, type the following command into the terminal to start a simple development server.

php -S localhost:8000

And now if you open your web browser and type in the address

localhost:8000

The Image repository will display in all its glory.

To view testing, Change the $testing boolean in the index.php file to TRUE and reload the webpage. The results of the testing.php file will be loaded instead of the repo code.

:D

---

### How to Use


You can use the website as a user or guest. 

Initially you will be prompted login. There are a couple of preloaded accounts you can use to sign in.

Username : abc

Password : 123

or  

Username : hello

Password : its_me

Likewise each account already has a few images stored, and a folder has been provided in the project directory containing some sample images.

However, feel free to create your own account and add images you want to your repository. If you do want to create your own account, type in a username/password not chosen and you will be redirected to a very similar looking screen providing you the opportunity to type in the same values you just typed in, or you can continue as a guest.

Finally once you have signed in/up you will redirected to the image viewing page. This will allow you to view all the images in the repository. And if you are signed-in your name, remaining quota and privacy are displayed. All the pictures in the repository are displayed at the bottom of the screen in rows of 4 pictures each. 

Finally you can upload images by selecting the Brouse... button, navigating to your image on your computer and by clicking the Upload Image button. You will be navigated to a screen that will print out the result of the upload providing the user with some feedback if unsuccessful.

Thats about it, :D.

---

### Legitimate avenues for improvement.

Where to start. 

Bigger uploads:
---

Php file uploading was rather nice to implement, and allows for up to 20 files at once to be uploaded. I only implemnted the simpler single file upload however it would be intersting to try out the 20 concurrent uploads and see if this could be chained together to upload huge data ammounts. 

Labeling Images:
---
Another avenue I could see would be either labeling all images with their uploader or potentially watermarking the images with the uploader. With images linked to users this would give more context to the collection of images for users to browse. 

Private Images:
---
Additionally I wanted to allow users to set the privacy requriements on their images, whether it be for all images or specific ones. 

Better testing:
---
I only gave myself time for cursory unit testing that definetely wasn't a strength of this handin. So definetely more comprehensive unit testing would improve my submission but considering the simple menu i implemented some acceptance tests woulden't hurt.

Adaptive Picture Display:
---
Given the likelyhood of a variety of different picture sizes and dimensions i chose a simpler hard coded image size. However displaying the images with their oritinal aspect ratio would be more true to the images and improve the repository quality. 

The list could go on but i will stop myself here.