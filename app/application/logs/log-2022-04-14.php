<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2022-04-14 06:24:31 --> 404 Page Not Found: Faviconico/index
ERROR - 2022-04-14 12:41:05 --> 404 Page Not Found: Faviconico/index
ERROR - 2022-04-14 12:43:15 --> 404 Page Not Found: Faviconico/index
ERROR - 2022-04-14 12:57:14 --> Severity: error --> Exception: Argument 1 passed to grotemAPI2::rebuildData() must be of the type array, object given, called in /var/www/html/application/controllers/GrotemAPI2.php on line 59 /var/www/html/application/controllers/GrotemAPI2.php 7
ERROR - 2022-04-14 12:57:14 --> Severity: Warning --> Cannot modify header information - headers already sent by (output started at /var/www/html/application/controllers/GrotemAPI2.php:57) /var/www/html/system/core/Common.php 570
ERROR - 2022-04-14 12:58:37 --> Severity: error --> Exception: Argument 1 passed to grotemAPI2::rebuildData() must be of the type array, object given, called in /var/www/html/application/controllers/GrotemAPI2.php on line 59 /var/www/html/application/controllers/GrotemAPI2.php 7
ERROR - 2022-04-14 13:28:56 --> Severity: Warning --> array_push() expects parameter 1 to be array, null given /var/www/html/application/controllers/GrotemAPI2.php 25
ERROR - 2022-04-14 13:28:56 --> Severity: Warning --> array_push() expects parameter 1 to be array, null given /var/www/html/application/controllers/GrotemAPI2.php 25
ERROR - 2022-04-14 13:29:37 --> Severity: error --> Exception: syntax error, unexpected '}' /var/www/html/application/controllers/GrotemAPI2.php 27
ERROR - 2022-04-14 13:31:16 --> Severity: Notice --> Undefined index: standart__new_schema /var/www/html/application/controllers/GrotemAPI2.php 22
ERROR - 2022-04-14 13:31:16 --> Severity: Notice --> Undefined index: standart__new_schema /var/www/html/application/controllers/GrotemAPI2.php 23
ERROR - 2022-04-14 13:31:16 --> Severity: Notice --> Undefined index: action__new_schema /var/www/html/application/controllers/GrotemAPI2.php 22
ERROR - 2022-04-14 13:31:16 --> Severity: Notice --> Undefined index: action__new_schema /var/www/html/application/controllers/GrotemAPI2.php 23
ERROR - 2022-04-14 13:32:52 --> Severity: Notice --> Undefined index: standart__new_schema /var/www/html/application/controllers/GrotemAPI2.php 18
ERROR - 2022-04-14 13:32:52 --> Severity: Notice --> Undefined index: action__new_schema /var/www/html/application/controllers/GrotemAPI2.php 18
ERROR - 2022-04-14 13:52:31 --> Severity: error --> Exception: Call to undefined method CI_DB_mysqli_driver::num_rows() /var/www/html/application/controllers/GrotemAPI2.php 37
ERROR - 2022-04-14 13:52:31 --> Severity: Warning --> Cannot modify header information - headers already sent by (output started at /var/www/html/application/controllers/GrotemAPI2.php:84) /var/www/html/system/core/Common.php 570
ERROR - 2022-04-14 14:48:45 --> Severity: Warning --> array_keys() expects parameter 1 to be array, string given /var/www/html/application/controllers/GrotemAPI2.php 47
ERROR - 2022-04-14 14:48:45 --> Severity: Warning --> array_keys() expects parameter 1 to be array, string given /var/www/html/application/controllers/GrotemAPI2.php 47
ERROR - 2022-04-14 14:49:21 --> Severity: Notice --> Array to string conversion /var/www/html/application/controllers/GrotemAPI2.php 47
ERROR - 2022-04-14 14:49:21 --> Severity: Notice --> Array to string conversion /var/www/html/application/controllers/GrotemAPI2.php 47
ERROR - 2022-04-14 14:58:43 --> Query error: Unknown column 'program' in 'field list' - Invalid query: SELECT DISTINCT (program, program_syn)
FROM `programm_margin`
WHERE `active` IS NULL
AND `program_syn` IN('Стандарт', 'Стандарт 3', 'Оптимальный', 'Удобный 2')
ERROR - 2022-04-14 14:59:10 --> Query error: Unknown column 'program_syn' in 'field list' - Invalid query: SELECT DISTINCT (programm, program_syn)
FROM `programm_margin`
WHERE `active` IS NULL
AND `program_syn` IN('Стандарт', 'Стандарт 3', 'Оптимальный', 'Удобный 2')
ERROR - 2022-04-14 14:59:27 --> Query error: Operand should contain 1 column(s) - Invalid query: SELECT DISTINCT (programm, programm_syn)
FROM `programm_margin`
WHERE `active` IS NULL
AND `program_syn` IN('Стандарт', 'Стандарт 3', 'Оптимальный', 'Удобный 2')
ERROR - 2022-04-14 14:59:50 --> Query error: Unknown column 'program_syn' in 'where clause' - Invalid query: SELECT DISTINCT (programm)
FROM `programm_margin`
WHERE `active` IS NULL
AND `program_syn` IN('Стандарт', 'Стандарт 3', 'Оптимальный', 'Удобный 2')
ERROR - 2022-04-14 15:16:17 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near 'WHERE programm_syn IN( 'Стандарт', 'Стандарт 3', 'Оптим...' at line 1 - Invalid query: SELECT * FROM programm_margin WHERE active IS NULL AND WHERE programm_syn IN( 'Стандарт', 'Стандарт 3', 'Оптимальный', 'Удобный 2')
ERROR - 2022-04-14 15:16:37 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near 'WHERE programm_syn IN ( 'Стандарт', 'Стандарт 3', 'Оптим...' at line 1 - Invalid query: SELECT * FROM programm_margin WHERE active IS NULL AND WHERE programm_syn IN ( 'Стандарт', 'Стандарт 3', 'Оптимальный', 'Удобный 2')
ERROR - 2022-04-14 15:17:22 --> Severity: error --> Exception: Call to undefined method CI_DB_mysqli_result::get() /var/www/html/application/controllers/GrotemAPI2.php 53
ERROR - 2022-04-14 15:18:21 --> Severity: error --> Exception: Call to undefined method CI_DB_mysqli_result::resultArray() /var/www/html/application/controllers/GrotemAPI2.php 53
ERROR - 2022-04-14 15:39:41 --> Severity: Notice --> Undefined variable: queryArray /var/www/html/application/controllers/GrotemAPI2.php 53
ERROR - 2022-04-14 15:39:41 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near '' at line 1 - Invalid query: SELECT DISTINCT(`programm_syn`) FROM programm_margin WHERE `active` IS NULL AND `programm_syn` IN 
ERROR - 2022-04-14 15:39:41 --> Severity: Warning --> Cannot modify header information - headers already sent by (output started at /var/www/html/system/core/Exceptions.php:271) /var/www/html/system/core/Common.php 570
ERROR - 2022-04-14 15:40:04 --> Severity: Notice --> Undefined variable: queryArray /var/www/html/application/controllers/GrotemAPI2.php 53
ERROR - 2022-04-14 15:40:04 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near '' at line 1 - Invalid query: SELECT DISTINCT(`programm_syn`) FROM programm_margin WHERE `active` IS NULL AND `programm_syn` IN 
ERROR - 2022-04-14 15:40:04 --> Severity: Warning --> Cannot modify header information - headers already sent by (output started at /var/www/html/system/core/Exceptions.php:271) /var/www/html/system/core/Common.php 570
ERROR - 2022-04-14 15:52:48 --> Query error: Unknown column 'exceptions_specstoc' in 'field list' - Invalid query: SELECT `сlient_name`,`exceptions_standard`,`exceptions_stock`,`exceptions_specstoc` FROM programm_line WHERE `client_name` IN ( 'Стандарт', 'Стандарт 3', 'Оптимальный', 'Удобный 2')
ERROR - 2022-04-14 15:53:10 --> Query error: Unknown column 'client_name' in 'where clause' - Invalid query: SELECT `сlient_name`,`exceptions_standard`,`exceptions_stock`,`exceptions_specstock` FROM programm_line WHERE `client_name` IN ( 'Стандарт', 'Стандарт 3', 'Оптимальный', 'Удобный 2')
ERROR - 2022-04-14 15:54:19 --> Severity: Notice --> Undefined variable: queryExeptionsForPrograms /var/www/html/application/controllers/GrotemAPI2.php 69
ERROR - 2022-04-14 16:03:24 --> Severity: Notice --> Undefined offset: 0 /var/www/html/application/controllers/GrotemAPI2.php 71
ERROR - 2022-04-14 16:03:24 --> Severity: Warning --> array_keys() expects parameter 1 to be array, null given /var/www/html/application/controllers/GrotemAPI2.php 71
ERROR - 2022-04-14 16:03:24 --> Severity: Notice --> Undefined offset: 1 /var/www/html/application/controllers/GrotemAPI2.php 71
ERROR - 2022-04-14 16:03:24 --> Severity: Warning --> array_keys() expects parameter 1 to be array, null given /var/www/html/application/controllers/GrotemAPI2.php 71
ERROR - 2022-04-14 16:27:09 --> Severity: Warning --> preg_replace() expects at least 3 parameters, 2 given /var/www/html/application/controllers/GrotemAPI2.php 76
ERROR - 2022-04-14 16:27:09 --> Severity: 8192 --> strpos(): Non-string needles will be interpreted as strings in the future. Use an explicit chr() call to preserve the current behavior /var/www/html/application/controllers/GrotemAPI2.php 79
ERROR - 2022-04-14 16:27:09 --> Severity: 8192 --> strpos(): Non-string needles will be interpreted as strings in the future. Use an explicit chr() call to preserve the current behavior /var/www/html/application/controllers/GrotemAPI2.php 79
ERROR - 2022-04-14 16:27:09 --> Severity: 8192 --> strpos(): Non-string needles will be interpreted as strings in the future. Use an explicit chr() call to preserve the current behavior /var/www/html/application/controllers/GrotemAPI2.php 79
ERROR - 2022-04-14 16:36:05 --> Severity: Warning --> preg_replace(): No ending delimiter '^' found /var/www/html/application/controllers/GrotemAPI2.php 76
ERROR - 2022-04-14 16:36:05 --> Severity: 8192 --> strpos(): Non-string needles will be interpreted as strings in the future. Use an explicit chr() call to preserve the current behavior /var/www/html/application/controllers/GrotemAPI2.php 79
ERROR - 2022-04-14 16:36:05 --> Severity: 8192 --> strpos(): Non-string needles will be interpreted as strings in the future. Use an explicit chr() call to preserve the current behavior /var/www/html/application/controllers/GrotemAPI2.php 79
ERROR - 2022-04-14 16:36:05 --> Severity: 8192 --> strpos(): Non-string needles will be interpreted as strings in the future. Use an explicit chr() call to preserve the current behavior /var/www/html/application/controllers/GrotemAPI2.php 79
ERROR - 2022-04-14 16:36:24 --> Severity: Warning --> preg_replace(): Unknown modifier '*' /var/www/html/application/controllers/GrotemAPI2.php 76
ERROR - 2022-04-14 16:36:24 --> Severity: 8192 --> strpos(): Non-string needles will be interpreted as strings in the future. Use an explicit chr() call to preserve the current behavior /var/www/html/application/controllers/GrotemAPI2.php 79
ERROR - 2022-04-14 16:36:24 --> Severity: 8192 --> strpos(): Non-string needles will be interpreted as strings in the future. Use an explicit chr() call to preserve the current behavior /var/www/html/application/controllers/GrotemAPI2.php 79
ERROR - 2022-04-14 16:36:24 --> Severity: 8192 --> strpos(): Non-string needles will be interpreted as strings in the future. Use an explicit chr() call to preserve the current behavior /var/www/html/application/controllers/GrotemAPI2.php 79
ERROR - 2022-04-14 16:49:20 --> Severity: error --> Exception: syntax error, unexpected 'echo' (T_ECHO) /var/www/html/application/controllers/GrotemAPI2.php 66
ERROR - 2022-04-14 16:49:46 --> Severity: error --> Exception: syntax error, unexpected 'echo' (T_ECHO) /var/www/html/application/controllers/GrotemAPI2.php 66
ERROR - 2022-04-14 17:27:06 --> Severity: error --> Exception: Class 'app\controllers\CI_Controller' not found /var/www/html/application/controllers/GrotemAPI2.php 7
ERROR - 2022-04-14 17:28:13 --> Severity: error --> Exception: Class 'app\controllers\CI_Controller' not found /var/www/html/application/controllers/GrotemAPI2.php 8
ERROR - 2022-04-14 17:28:37 --> Severity: error --> Exception: /var/www/html/application/models/Logger.php exists, but doesn't declare class Logger /var/www/html/system/core/Loader.php 340
ERROR - 2022-04-14 17:28:59 --> Severity: error --> Exception: /var/www/html/application/models/Logger.php exists, but doesn't declare class Logger /var/www/html/system/core/Loader.php 340
ERROR - 2022-04-14 17:29:21 --> Severity: error --> Exception: Class 'parrent' not found /var/www/html/application/models/Logger.php 8
