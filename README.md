# Server for Reuse Lists

[API Documentation](./API.md)

### Database config

1. Create a Database, for example, `sample_db`;
2. Copy `config/db.sample.php` to `config/db.php`, fill in your db's information;
3. Add table and data in the db.

#### Sample for DB setting

**TABLE *lists*:**

```
CREATE TABLE IF NOT EXISTS `lists` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(256) NOT NULL,
  `archived` tinyint(1) NOT NULL DEFAULT 0,
  `created` datetime NOT NULL,
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=19 ;
```

**TABLE *items*:**

```
CREATE TABLE IF NOT EXISTS `items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  `checked` tinyint(1) NOT NULL DEFAULT 0,
  `list_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=65 ;
```

**TABLE *users*:**

```
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(32) NOT NULL,
  `password` varchar(32) NOT NULL,
  `email` varchar(32) NOT NULL,
  `timestamp` int(11),
  `created` datetime NOT NULL,
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=65 ;
```

**Sample data for TABLE *lists*:**

```
INSERT INTO `lists` (`id`, `name`, `archived`, `created`, `modified`) VALUES
(1, 'Travel Pack', 0, '2017-05-01 00:35:07', '2017-05-30 17:34:33'),
(2, 'Weekly Shopping List', 0, '2017-05-01 00:35:07', '2017-05-30 17:34:33'),
(3, 'Swimming Pack', 0, '2017-05-01 00:35:07', '2017-05-30 17:34:54'),
```

**Sample data for TABLE *items*:**

```
INSERT INTO `items` (`id`, `name`, `checked`, `list_id`, `created`, `modified`) VALUES
(1, 'Toothbrush', 0, 1, '2017-06-01 01:12:26', '2017-07-31 17:12:26'),
(2, 'Towel', 0, 3, '2017-06-01 01:12:26', '2017-07-31 17:12:26'),
(3, 'Sachsen Milk 1L', 0, 2, '2017-06-01 01:12:26', '2017-07-31 17:12:26');
(4, 'iPad', 0, 1, '2017-06-01 01:12:26', '2017-07-31 17:12:26');
```

**Sample data for TABLE *users*:**

```
INSERT INTO `users` (`id`, `username`, `password`, `email`, `created`, `modified`) VALUES
(1, 'sample_user', 'sample_pass', 'mail@example.com', '2018-01-10 12:22:01', '2018-03-11 05:22:10');
```

### Home url config

1. Copy `config/core.sample.php` to `config/core.php`;
2. Specify your home url (for paging navigation).


