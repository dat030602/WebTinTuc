<?php 
    function execute($conn, $type, $sql, $data, $selectFields, $tableName, $conditions)
    {
        $errors = [];
        $success = "";
        date_default_timezone_set("Asia/Ho_Chi_Minh");
        try {
            if($type == 'select')
                return recored_select($conn, $selectFields, $tableName, $conditions);
            else if($type == 'insert')
                return recored_insert($conn, $tableName, $data);
            else if($type == 'update')
                return recored_update($conn, $tableName, $data, $conditions);
            else if($type == "delete")
                return recored_delete($conn, $tableName, $conditions);
            else
                return recored_special($conn, $sql);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    function recored_select($conn, $selectFields, $tableName, $conditions) {
        $query = "SELECT " . implode(', ', $selectFields) . " FROM " . $tableName;
        if (!empty($conditions)) {
            $where = [];
            foreach ($conditions as $column => $value) {
                $where[] = $column . " = '" . mysqli_real_escape_string($conn, $value) . "'";
            }
            $query .= " WHERE " . implode(' AND ', $where);
        }
        $result = mysqli_query($conn, $query);
        if (!$result) {
            throw new mysqli_sql_exception(mysqli_error($conn));
        }
        return $result;
    }
    
    function recored_insert($conn, $tableName, $insertData) {
        $columns = [];
        $values = [];
        foreach ($insertData as $column => $value) {
            $columns[] = $column;
            $values[] = "'" . mysqli_real_escape_string($conn, $value) . "'";
        }
    
        $query = "INSERT INTO " . $tableName . " (" . implode(', ', $columns) . ") VALUES (" . implode(', ', $values) . ")";
        
        $result = mysqli_query($conn, $query);
        if (!$result) {
            throw new mysqli_sql_exception(mysqli_error($conn));
        }
        return $result;
    }
    
    function recored_update($conn, $tableName, $updateData, $conditions) {
        $set = [];
        foreach ($updateData as $column => $value) {
            $set[] = $column . " = '" . mysqli_real_escape_string($conn, $value) . "'";
        }
    
        $query = "UPDATE " . $tableName . " SET " . implode(', ', $set);
    
        if (!empty($conditions)) {
            $where = [];
            foreach ($conditions as $column => $value) {
                $where[] = $column . " = '" . mysqli_real_escape_string($conn, $value) . "'";
            }
            $query .= " WHERE " . implode(' AND ', $where);
        }
    
        $result = mysqli_query($conn, $query);
        if (!$result) {
            throw new mysqli_sql_exception(mysqli_error($conn));
        }
        return $result;
    }
    
    function recored_delete($conn, $tableName, $conditions) {
        $query = "DELETE FROM " . $tableName;
        if (!empty($conditions)) {
            $where = [];
            foreach ($conditions as $column => $value) {
                $where[] = $column . " = '" . mysqli_real_escape_string($conn, $value) . "'";
            }
            $query .= " WHERE " . implode(' AND ', $where);
        }
    
        $result = mysqli_query($conn, $query);
        if (!$result) {
            throw new mysqli_sql_exception(mysqli_error($conn));
        }
        return $result;
    }

    function recored_special($conn, $query) {
        $result = mysqli_query($conn, $query);
        if (!$result) {
            throw new mysqli_sql_exception(mysqli_error($conn));
        }
        return $result;
    }
?>