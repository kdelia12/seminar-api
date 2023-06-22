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
        "email": "email@email.com",
        "email_verified_at": null,
        "no_hp": "no hape",
        "role": "admin",
        "created_at": "2023-06-15T12:48:56.000000Z",
        "updated_at": "2023-06-15T13:11:14.000000Z",
        "seminar_applied": "[2, 1]"
    },
    "token": "$Token"
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
    - ```/logout```
* Method
    - POST
* Headers
    - ``Authorization`` : ``Bearer <token>``

* Response
```json
{
    "success": True,
    "message": "Success Logout!"
}
```

## Change Password
* URL
    - ```user/changepassword```
* Method
    - POST
* Headers
    - ``Authorization`` : ``Bearer <token>``
* Request Body
    - ``old_password``
    - ``new_password``
    - ``new_password_confirmation``
* Response
```json
{
    "success": false,
    "message": "Password Lama Tidak Sesuai"
}
{
    "success": false,
    "message": "Password Baru Tidak Sesuai"
}
{
    "success": true,
    "message": "Password Berhasil Diubah"
}
```

## ShowUserData
* URL
    - ```/user```
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
        "name": "Seminar Javascript dan SQL",
        "short_description": "Tentang Javascript dan SQL",
        "full_description": null,
        "date_and_time": "2023-07-27 20:00:00",
        "quota": 10,
        "participant_count": 4,
        "speaker": "Ridwan Solar"
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
        "id": 12,
        "name": "Mabar Sekuy",
        "short_description": "Anjay Mabar",
        "full_description": "Mari kita mabar lah gaskan cuyy",
        "speaker": "Mabar kun",
        "participants": null,
        "category": "Gaming",
        "lokasi": null,
        "alamat": null,
        "participant_count": null,
        "quota": 102,
        "date_and_time": "2023-06-01 23:52:00",
        "updated_at": "2023-06-21T16:53:00.000000Z",
        "created_at": "2023-06-21T16:53:00.000000Z"
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
            "seminar_name": "Seminar Javascript dan SQL",
            "seminar_shortdesc": "Tentang Javascript dan SQL",
            "seminar_speaker": "Ridwan Solar",
            "seminar_date": "2023-07-27 20:00:00"
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
    "message": "Anda Sudah Mendaftar"
}
```

## Add Seminar
* URL
    - ```/seminars```
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
    - ``lokasi`` (Offline/Online)
    - ``alamat``
    - ``category``
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
    - ```/seminars/{idseminar}```
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

## Get Seminar average rating
* URL
    - ```/seminars/{idseminar}/stars```
* Method
    - GET
* Headers
    - ``Authorization`` : ``Bearer <token>``
* Response
```json
{
    "average_rating": 5
}
```

## Add Review
* URL
    - ```/ratings/add```
* Method
    - GET
* Headers
    - ``Authorization`` : ``Bearer <token>``
* Request Body
    - ``id_seminar`` 
    - ``stars``
    - ``review``
* Response
```json
{
    "average_rating": 5
}
```
## Add Review
* URL
    - ```/ratings/add```
* Method
    - GET
* Headers
    - ``Authorization`` : ``Bearer <token>``
* Request Body
    - ``id_seminar`` 
    - ``stars``
    - ``review``
* Response
```json
{
    "average_rating": 5
}
```