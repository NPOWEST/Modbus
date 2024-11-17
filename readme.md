# Modbus

Предназначен для работы по Modbus протоколу.

Установка:
```shell
composer install npowest/modbus
```

**setAddress**(Хост, Порт, Modbus-адрес) - *Установка адреса*

**setMsg**(Сообщение) - *Установка сообщения в hex*

**app**() - *Выполнить запрос и получить результат*

**sendCommand**(Command, Address, Len, Data) - *Отравить команду*
- Command - *команда set или get*
- Address - *адрес*
- Len - *размер*
- Data - *данные для отправки (опционально)*

**getError**() - *Получить сообщение об ошибке*
