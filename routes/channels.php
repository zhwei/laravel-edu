<?php

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

use App\User;

Broadcast::channel('student.{id}', function (User $user, $id) {

    $user->student_school_id;

    return (int)$user->id === (int)$id;
});


Broadcast::channel('teacher.{id}', function (User $user, $id) {
    return (int)$user->id === (int)$id;
});


Broadcast::channel('room.{teacherId}.{studentId}', function (User $user, $teacherId, $studentId) {
    logger(__FILE__, func_get_args());
    return true;
});

