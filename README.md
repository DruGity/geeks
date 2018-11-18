# Test task for 1000geeks
Реализовано два API метода:
POST /generate - post запрос, который генерирует коды определенной длины и состоящие из определенных символов, 
записывает сгенерированные кода в ексель файл(название файла - текущая время и дата), если параметр export == xls.
Параметр nb - количество генерируемых кодов, по умолчанию - 1.
GET {code} -  метод отдающий json массив данных с описанием конкретного кода, если он есть в базе данных. 
Параметр {code} - код о котором предоставляется информация.
# Технологии
PHP 7;
Symfony 4.1;
Mysql;
Doctrine;
# Libraries:
https://github.com/PHPOffice/PhpSpreadsheet
