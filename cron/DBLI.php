<?php

# Name: Database.class.php
# File Description: mysqli Class to allow easy and clean access to common mysqli commands
# Author: ricocheting
# Web: http://www.ricocheting.com/
# Update: 2010-05-08
# Version: 2.2.5
# Copyright 2003 ricocheting.com


/*
  This program is free software: you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation, either version 3 of the License, or
  (at your option) any later version.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

//require("config.inc.php");
//$db = new Database(DB_SERVER, DB_USER, DB_PASS, DB_DATABASE);
###################################################################################################
###################################################################################################
###################################################################################################
class DatabaseLi {

    var $server = ""; //database server
    var $user = ""; //database login name
    var $pass = ""; //database login password
    var $database = ""; //database name
    var $pre = ""; //table prefix
#######################
//internal info
    var $error = "";
    var $errno = 0;
//number of rows affected by SQL query
    var $affected_rows = 0;
    var $link_id = 0;
    var $query_id = 0;

#-#############################################
# desc: constructor

    function __construct($server, $user, $pass, $database, $pre = '') {
        $this->server = $server;
        $this->user = $user;
        $this->pass = $pass;
        $this->database = $database;
        $this->pre = $pre;
    }

#-#constructor()
#-#############################################
# desc: connect and select database using vars above
# Param: $new_link can force connect() to open a new link, even if mysqli_connect() was called before with the same parameters

    function connect() {
     //   echo "Connecting to Mysql:" . $this->server."\n";
        $this->link = new mysqli($this->server, $this->user, $this->pass, $this->database);
       
        if (!$this->link) {//open failed
            $this->oops("Could not connect to server: <b>$this->server</b>.");
        }
    }

#-#connect()
#-#############################################
# desc: close the connection

    function close() {
        $close = $this->link->close();
        
        if (!$close) {
            echo "close Fail";
           // $this->oops("Connection close failed.");
            
        }else{
        //    echo "Close Connection DB.\n";
        }
    }

#-#close()
#-#############################################
# Desc: escapes characters to be mysqli ready
# Param: string
# returns: string

    function escape($string) {
        if (is_string($string)) {
                $string = stripslashes($string);
                return $this->link->real_escape_string($string);
        } else {
            return $string;
        }
    }

#-#escape()
#-#############################################
# Desc: executes SQL query to an open connection
# Param: (mysqli query) to execute
# returns: (query_id) for fetching results etc

    function query($sql) {
        // do query
        try{
        $this->query_id = $this->link->query($sql);
        } catch (Exception $e) {
            echo "Posible Duplicado".$sql."\n";
        }
        
//        if (!$this->query_id) {
//            $this->oops("<b>mysqli Query fail:</b> $sql");
//            return 0;
//        }

        $this->affected_rows = mysqli_affected_rows($this->link);

        return $this->query_id;
    }

#-#query()
#-#############################################
# desc: fetches and returns results one line at a time
# param: query_id for mysqli run. if none specified, last used
# return: (array) fetched record(s)

    function fetch_array($query_id = -1) {
        // retrieve row
        if ($query_id != -1) {
            $this->query_id = $query_id;
        }

        if (isset($this->query_id)) {
            $record = mysqli_fetch_assoc($this->query_id);
        } else {
            $this->oops("Invalid query_id: <b>$this->query_id</b>. Records could not be fetched.");
        }

        return $record;
    }

#-#fetch_array()
#-#############################################
# desc: returns all the results (not one row)
# param: (mysqli query) the query to run on server
# returns: assoc array of ALL fetched results

    function fetch_all_array($sql) {
        $query_id = $this->query($sql);
        $out = array();

        while ($row = $this->fetch_array($query_id)) {
            $out[] = $row;
        }

        $this->free_result($query_id);
        return $out;
    }

#-#fetch_all_array()
#-#############################################
# desc: frees the resultset
# param: query_id for mysqli run. if none specified, last used

    function free_result($query_id = -1) {
        if ($query_id != -1) {
            $this->query_id = $query_id;
        }
        if ($this->query_id != 0 && !mysqli_free_result($this->query_id)) {
            $this->oops("Result ID: <b>$this->query_id</b> could not be freed.");
        }
    }

#-#free_result()
#-#############################################
# desc: does a query, fetches the first row only, frees resultset
# param: (mysqli query) the query to run on server
# returns: array of fetched results

    function query_first($query_string) {
        $query_id = $this->query($query_string);
        $out = $this->fetch_array($query_id);
        $this->free_result($query_id);
        return $out;
    }

#-#query_first()
#-#############################################
# desc: does an update query with an array
# param: table (no prefix), assoc array with data (doesn't need escaped), where condition
# returns: (query_id) for fetching results etc

    function query_update($table, $data, $where = '1') {
        $q = "UPDATE `" . $this->pre . $table . "` SET ";

        foreach ($data as $key => $val) {
              if (is_a($val, "Datetime")) {
                $q.= "`$key`='" . $val->format("Y-m-d H:i:s") . "', ";
            } else {
                $q.= "`$key`='" . $this->escape($val) . "', ";
            }
        }

        $q = rtrim($q, ', ') . ' WHERE ' . $where . ';';

        return $this->query($q);
    }

#-#query_update()
#-#############################################
# desc: does an insert query with an array
# param: table (no prefix), assoc array with data
# returns: id of inserted record, false if error

    function query_insert($table, $data) {
        $q = "INSERT INTO `" . $this->pre . $table . "` ";
        $v = '';
        $n = '';

        foreach ($data as $key => $val) {
            $n.="`$key`, ";
            if (is_a($val, "Datetime")) {
                $v.= "'" . $val->format("Y-m-d H:i:s") . "', ";
            } else {
                $v.= "'" . $this->escape($val) . "', ";
            }
        }


        $q .= "(" . rtrim($n, ', ') . ") VALUES (" . rtrim($v, ', ') . ");";

        if ($this->query($q)) {
            //$this->free_result();
            return mysqli_insert_id($this->link);
        }
        else
            return false;
    }

#-#query_insert()
#-#############################################
# desc: does an insert query with an array
# param: table (no prefix), assoc array with data
# returns: id of inserted record, false if error

    function query_insert_ignore($table, $data) {
        $q = "INSERT IGNORE INTO `" . $this->pre . $table . "` ";
        $v = '';
        $n = '';

        foreach ($data as $key => $val) {
            $n.="`$key`, ";
            if (is_a($val, "Datetime")) {
                $v.= "'" . $val->format("Y-m-d H:i:s") . "', ";
            } else {
                $v.= "'" . $this->escape($val) . "', ";
            }
        }


        $q .= "(" . rtrim($n, ', ') . ") VALUES (" . rtrim($v, ', ') . ");";

        if ($this->query($q)) {
            //$this->free_result();
            return mysqli_insert_id($this->link);
        }
        else
            return false;
    }

#-#query_insert_ignore()
#-#############################################
# desc: does an insert query with an array
# param: table (no prefix), assoc array with data
# returns: id of inserted record, false if error

    function query_replace($table, $data) {
        $q = "REPLACE  INTO `" . $this->pre . $table . "` ";
        $v = '';
        $n = '';

        foreach ($data as $key => $val) {
            $n.="`$key`, ";
            if (is_a($val, "Datetime")) {
                $v.= "'" . $val->format("Y-m-d H:i:s") . "', ";
            } else {
                $v.= "'" . $this->escape($val) . "', ";
            }
        }


        $q .= "(" . rtrim($n, ', ') . ") VALUES (" . rtrim($v, ', ') . ");";

        if ($this->query($q)) {
            //$this->free_result();
            return mysqli_insert_id($this->link);
        }
        else
            return false;
    }

#-#query_replace()
#-#############################################
# desc: throw an error message
# param: [optional] any custom error to display

    function oops($msg = '') {
        
            $this->error = mysqli_error($this->link);
            $this->errno = mysqli_errno($this->link);



        echo "Database Error:";
        echo "\n";
        echo $msg;
        echo "\n";
        echo $this->error . " - " . $this->errno;
        echo "\n";
        // echo $this->errno;
        // echo "Msg:".$msg;
        echo "\n";
    }

#-#oops()
}

//CLASS Database
###################################################################################################
?>