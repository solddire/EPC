# Задание №2
Знания MySQL + оптимизировать запросы.Имеется 3 таблицы: **info, data, link,** есть запрос для получения данных:select * from data,link,info where link.info_id = info.id and link.data_id = data.id**предложить варианты оптимизации:**
- таблиц
- запроса.

Запросы для создания таблиц:
    CREATE TABLE `info` (
            `id` int(11) NOT NULL auto_increment,
        `name` varchar(255) default NULL,
            `desc` text default NULL,
            PRIMARY KEY (`id`)
    ) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

    CREATE TABLE `data` (
            `id` int(11) NOT NULL auto_increment,
            `date` date default NULL,
            `value` INT(11) default NULL,
            PRIMARY KEY (`id`)
    ) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

    CREATE TABLE `link` (
            `data_id` int(11) NOT NULL,
            `info_id` int(11) NOT NULL
    ) ENGINE=MyISAM DEFAULT CHARSET=cp1251;
# Мое решение
Моя мысль по поводу оптимизации такова:
    1) Добавить индексы на столбцы, которые участвуют в соединениях:
        CREATE INDEX idx_info_id ON link(info_id);
        CREATE INDEX idx_data_id ON link(data_id);

    2)Можно оптимизировать кодировку, но тут смотря по надобности(с cp1251 однажды были проблемы, сталкивался). Я зачастую использую utf8mb4.

    3)Можно оптимизировать запрос с использованием INNER JOIN, чтобы избежать избыточности при использовании селекта, например:

    SELECT data.*, info.* FROM link
    INNER JOIN data ON link.data_id = data.id
    INNER JOIN info ON link.info_id = info.id;

P.S Возможно описал не все в идеале, но я и mysql не использовал для супер больших задач.
