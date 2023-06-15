## Login

* URL
    - ```/login```
* Method
    - POST
* Request Body
    - ``email`` 
    - ``password`` 
* Response
```json
{
    "success": true,
    "user": {
        "id": 9,
        "name": "Wildan",
        "email": "eweeweewe@ewe.com",
        "email_verified_at": null,
        "no_hp": "0812628265434",
        "role": "admin",
        "created_at": "2023-06-15T12:48:56.000000Z",
        "updated_at": "2023-06-15T13:11:14.000000Z",
        "seminar_applied": "[2, 1]"
    },
    "token": "Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vd2FscnVzLWFwcC1lbHByOC5vbmRpZ2l0YWxvY2Vhbi5hcHAvYXBpL2xvZ2luIiwiaWF0IjoxNjg2ODM3NzExLCJleHAiOjE2ODY4NDEzMTEsIm5iZiI6MTY4NjgzNzcxMSwianRpIjoiNlRzZmZZeWRGYzlhNXJ2cSIsInN1YiI6IjkiLCJwcnYiOiIyM2JkNWM4OTQ5ZjYwMGFkYjM5ZTcwMWM0MDA4NzJkYjdhNTk3NmY3In0.VRYeWQEZyTLC0UNrgJJtftF2I9I8hHuQ21TWbpRtk28"
}
```
## Register
* URL 
    -   ```/register```
* Method
    - POST
* Request Body
    - ``nama`` 
    - ``no_hp`` 
    - ``email`` 
    - ``password`` 
    - ``password_confirmation`` 
* Response 
```json
{
    "success": true,
    "user": {
        "name": "Wildan",
        "email": "eweeweewe@ewe.com",
        "no_hp": "0812628265434",
        "updated_at": "2023-06-15T12:48:56.000000Z",
        "created_at": "2023-06-15T12:48:56.000000Z",
        "id": 9
    }
}
```
## Logout
* URL
    - ```logout```
* Method
    - POST
* Headers
    - ``Authorization`` : ``Bearer <token>``

* Response
```json
{
    "success": false,
    "message": "Token Sudah Tidak Berlaku!"
}
```

## ShowUserData
* URL
    - ```user```
* Method
    - POST
* Headers
    - ``Authorization`` : ``Bearer <token>``
* Response
```json
{
    "success": true,
    "user": {
        "id": 1,
        "name": "Wildan",
        "email": "wild@wild.com",
        "email_verified_at": null,
        "no_hp": null,
        "role": "admin",
        "created_at": "2023-03-28T07:51:05.000000Z",
        "updated_at": "2023-03-28T07:51:05.000000Z"
    }
}
```

## Get Upcoming Seminar
* URL
    - ```/seminars/upcoming```
* Method
    - GET
* Headers
    - ``Authorization`` : ``Bearer <token>``
* Response
```json
{
        "id": 2,
        "name": "Seminar Sek dan Ewe",
        "short_description": "Tentang Sek dan Ewe",
        "full_description": null,
        "speaker": null,
        "participants": "[2, 3, 1]",
        "participant_count": 3,
        "quota": 3,
        "date_and_time": "2023-07-27 20:00:00",
        "updated_at": "2023-04-27T14:30:27.000000Z",
        "created_at": null
}
```

## GetPastSeminar
* URL
    - ```/seminars/past```
* Method
    - GET
* Response
```json
{
        "id": 2,
        "name": "Seminar Sek dan Ewe",
        "short_description": "Tentang Sek dan Ewe",
        "full_description": null,
        "speaker": null,
        "participants": "[2, 3, 1]",
        "participant_count": 3,
        "quota": 3,
        "date_and_time": "2023-03-27 20:00:00",
        "updated_at": "2023-04-27T14:30:27.000000Z",
        "created_at": null
}
```
## Get Seminar Applied by user
* URL
    - ```/seminars/applied```
* Method
    - GET
* Headers
    - ``Authorization`` : ``Bearer <token>``
* Response
```json
{
    "seminars": [
        {
            "seminar_id": 2,
            "seminar_name": "Seminar Sek dan Ewe"
        },
        {
            "seminar_id": 1,
            "seminar_name": "Seminar Segs dan Ewe"
        }
    ]
}
```

## Get Seminar Applied Status
* URL
    - ```/seminars/{idseminar}/check```
* Method
    - GET
* Headers
    - ``Authorization`` : ``Bearer <token>``
* Response
```json
{
    "message": "Anda belum mendaftar"
}
```

## Add Seminar
* URL
    - ```/startsleep```
* Method
    - POST
* Headers
    - ``Authorization`` : ``Bearer <token>``
* Request Body
    - ``name`` 
    - ``short_description``
    - ``full_description``
    - ``speaker``
    - ``quota``
    - ``date_and_time`` (YYYY-MM-DD HH-MM-SS)
* Response
```json
{
    "message": "Seminar Berhasil ditambahkan"
}
```

## Add Seminar
* URL
    - ```/seminars/{seminarid}/apply```
* Method
    - POST
* Headers
    - ``Authorization`` : ``Bearer <token>``
* Response
```json
{
    "message": "Seminar Applied"
}
```

## Delete Seminar
* URL
    - ```/seminars/{idseminar}```
* Method
    - Delete
* Headers
    - ``Authorization`` : ``Bearer <token>``
* Response
```json
{
    "message": "Seminar deleted"
}
```

## Get Seminar Participant data
* URL
    - ```/seminars/{idseminar}/check```
* Method
    - GET
* Headers
    - ``Authorization`` : ``Bearer <token>``
* Response
```json
{
    "applicants": [
        {
            "participant_id": 2,
            "participant_name": "Wildan",
            "participant_email": "wild@dan.com",
            "participant_phone": "0812628265434"
        },
        {
            "participant_id": 1,
            "participant_name": "Wildan",
            "participant_email": "wild@wild.com",
            "participant_phone": null
        },
        {
            "participant_id": 9,
            "participant_name": "Wildan",
            "participant_email": "eweeweewe@ewe.com",
            "participant_phone": "0812628265434"
        }
    ]
}
```
