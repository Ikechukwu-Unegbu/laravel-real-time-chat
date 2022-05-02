<?php
return [
/*
|CHAT CONFIG
*/
  
/*
|-------------------------------------
| Attachments
|-------------------------------------
*/
  'attachments' => [
    'folder' => 'attachments',
    'download_route_name' => 'attachments.download',
    'allowed_images' => (array) ['png','jpg','jpeg','gif'],
    'allowed_files' => (array) ['zip','rar','txt'],
    'max_upload_size' => 150, // MB
  ],


];