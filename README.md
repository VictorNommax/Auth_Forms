# Auth_Forms
Code-test task(PHP, JS)

Надеюсь, условия понял верно. Реализация на нативных php/js. 
 
Примечания:

1) Для удобства реализовал также форму регистрации.

2) Для демонстрации работы нескольких сессий - добавил ссылку на другую страницу (после авторизации).

3) Защита от брутфорса реализована за счет:
а)Минимальных требований при создании пароля.
б)Счетчика попыток входа(изначально 10), по истечению которых, возможность входа для данного логина блокируется до вмешательства администратора.

4)Парсинг Инстраграма реализован очень просто. К сожалению, чтобы найти рабочий способ парсинга фотографий, без использования апи, требуется потратить больше времени. Однако, имеющийся код парсит имя любого профиля, ссылку на который забьют при регистрации.
