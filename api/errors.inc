<?php

function throw_sql_error($sql)
{
    $error = "The following mysql query:\n$sql\nraised the following error:\n" . mysqli_error(DPDatabase::get_connection());
    throw new Exception($error);
}
