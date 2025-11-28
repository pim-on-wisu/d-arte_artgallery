# How to Run This Project (MAMP on macOS)

## 1. Move Project to MAMP
Place the folder inside:

/Applications/MAMP/htdocs/DArteArtgallery

## 2. Set MAMP Ports
MAMP → Preferences → Ports:
- Apache: 8888
- MySQL: 8889

## 3. Start Servers
Click "Start Servers" in MAMP.

## 4. Database Setup
Go to: http://localhost:8888/phpMyAdmin  
- Create database: ArtGalleryManagement  
- Import the SQL file  
- DB credentials (default MAMP):
  - Host: 127.0.0.1  
  - Port: 8889  
  - User: root  
  - Pass: root  

## 5. Update DB Connection
Use this in your config:

```php
$pdo = new PDO("mysql:host=127.0.0.1;port=8889;dbname=ArtGalleryManagement", "root", "root");

## 6. Run Website

Open in browser:

http://localhost:8888/DArteArtgallery