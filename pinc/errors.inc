<?php

function try_query($sql)
{
    $res = mysqli_query(DPDatabase::get_connection(), $sql);
    if(!$res)
    {
        $error = "The following mysql query:\n$sql\nraised the following error:\n" . mysqli_error(DPDatabase::get_connection());
        throw new Exception($error);
    }
    return $res;
}
