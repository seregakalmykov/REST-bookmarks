Bookmarks REST
============================

Инструкция по работе с API
-------------------

### Получить список 10 последних добавленных Bookmark
Path: /bookmarks

Method: GET

Ответ:

     0:  {
         "id": 19
         "url": "ajsldf"
         "created_at": 1470691240
     }
     1:  {
         "id": 18
         "url": "http://new-bookmark4"
         "created_at": 1470683157
     }
     ...

### Получить Bookmark (с комментариями) по Bookmark.url
Path: /bookmarks/{url}

Method: GET

Например: /bookmarks/http://test

Ответ:

     {
         "id": 2
         "created_at": 1470396432
         "url": "123"
         "comments":
         0:  {
             "id": 1
             "text": null
             "bookmark_id": 2
             "created_at": 1470419240
             "ip": "128.0.0.1"
         }
     }

### Добавить Bookmark по url и получить Bookmark.uid
Path: /bookmarks

Method: POST

Параметры: url

Ответ:

     {
         "id": 20
         "url": "http://new_bookmark"
         "created_at": "1470694870"
     }

### Добавить Comment к Bookmark (по uid) и получить Comment.uid
Path: /comments

Method: POST

Параметры: text, bookmark_id

Ответ:

     {
         "bookmark_id": "7"
         "text": "123"
         "created_at": "1470694094"
         "ip": "::1"
         "id": 9
     }

### Изменить Comment.text по uid (если он добавлен с этого же IP и прошло меньше часа после добавления)
Path: /comments

Method: PUT

Параметры: id, text

Ответ:

     {
         "id": 9
         "text": "1234"
         "bookmark_id": 7
         "created_at": 1470694094
         "ip": "192.168.0.101"
     }

### Удалить Comment по uid (если он добавлен с этого же IP и прошло меньше часа после добавления)
Path: /comments/{id}

Method: DELETE

Ответ:

     {
         "name": "OK"
         "message": "Comments deleted successfully"
         "code": 0
         "status": 200
         "type": "yii\web\HttpException"
     }