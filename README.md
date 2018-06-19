# README

# File Sharing application 

Application allows managememt supprted files on your local FileSystem.  

[ Supported types ] 
 Images: jpg, jpeg, png, gif, tiff
 Audio: mp3, ogg, mpga
 Video: txt, doc, docx, pdf, odt

More types can be supported by adding file extension types ( FileController )


## Deployment Requirements [ Valet ]

Homebrew php72 [ php 7.2.4 ]
```sh
brew install homebrew/php/php72
```
Install Valet with Composer 
```sh
$ composer global require laravel/valet
```
Create symlink to public storage
```sh
php artisan storage:link
```
Run Valet install 
```sh
$ valet install 
```
Run valet 
```sh
$ valet start 
```
Composer Install  
```sh
$ composer install 
```
NPM 
local development 
```sh
npm run development
```



