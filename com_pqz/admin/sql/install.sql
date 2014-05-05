
CREATE TABLE IF NOT EXISTS `#__pqz_lnk_quiz_category` (
  `id_lnk_quiz_category` int(11) NOT NULL AUTO_INCREMENT,
  `id_quiz` int(11) NOT NULL,
  `id_category` int(11) NOT NULL,
  PRIMARY KEY (`id_lnk_quiz_category`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------


CREATE TABLE IF NOT EXISTS `#__pqz_lnk_quiz_question` (
  `id_lnk_quiz_question` int(11) NOT NULL AUTO_INCREMENT,
  `id_question` int(11) NOT NULL,
  `id_quiz` int(11) NOT NULL,
  PRIMARY KEY (`id_lnk_quiz_question`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- ---- pqz_question ----
-- Tutte le domande possibili

CREATE TABLE IF NOT EXISTS `#__pqz_question` (
  `id_question` int(11) NOT NULL AUTO_INCREMENT,
  `question` text NOT NULL,
  `correct_answer` text NOT NULL,
  `wrong_answer` text NOT NULL,
  `difficult_level` smallint(6) NOT NULL,
  `response_type` varchar(64) NOT NULL,
  `tags` varchar(255) NOT NULL,
  PRIMARY KEY (`id_question`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- - Categorie di quiz
-- equivalente delle directory

CREATE TABLE IF NOT EXISTS `#__pqz_quiz_categories` (
  `id_category` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `parent_catecogry` int(11) NOT NULL,
  PRIMARY KEY (`id_category`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- Quiz veri e propri
-- (equivalente dei files XML)

CREATE TABLE IF NOT EXISTS `#__pqz_quiz_name` (
  `id_quiz` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `num_options` smallint(6) NOT NULL,
  `num_question_per_page` smallint(6) NOT NULL,
  `max_question_total` smallint(6) NOT NULL,
  `min_diffucult_level` tinyint(4) NOT NULL,
  `max_diffucult_level` tinyint(4) NOT NULL,
  `default_response_type` varchar(64) NOT NULL,
  `randomize_question` tinyint(1) NOT NULL,
  `reverse_question` tinyint(1) NOT NULL,
  `tags` varchar(255) ,
  `question_filename` varchar(255),
  `description` text,
  `congratulation_file` varchar(255) NOT NULL,

  PRIMARY KEY (`id_quiz`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;



INSERT INTO `#__pqz_quiz_name` (`id_quiz`, `title`, `num_options`, `num_question_per_page`, `max_question_total`, `min_diffucult_level`, `max_diffucult_level`, `default_response_type`, `randomize_question`, `reverse_question`, `tags`, `question_filename`, `description`, `congratulation_file`) VALUES
(1, 'Bandiere', 5, 1, 10, 0, 100, 'options', 1, 0, '', 'components/com_pqz/media/data/src/csv/bandiere.csv', 'Metti alla prova la tua conoscenza delle bandiere del mondo', 'components/com_pqz/media/data/conf/congratulation-belle.csv'),
(2, 'Capitali', 5, 1, 6, 0, 100, 'options', 1, 0, '', 'components/com_pqz/media/data/src/csv/capitali.csv', 'Quante capitali Conosci?', 'components/com_pqz/media/data/conf/congratulation-belle.csv'),
(4, 'Cultura Generale - 1', 5, 1, 0, 0, 100, 'options', 1, 0, '', 'components/com_pqz/media/data/src/csv/cultura-generale.csv', '11 domande (difficili) su storia arte fisica ... ', 'components/com_pqz/media/data/conf/congratulation-belle.csv'),
(5, 'Mappe del Mondo', 5, 1, 10, 0, 100, 'options', 1, 0, '', 'components/com_pqz/media/data/src/csv/mappe.csv', 'Sai identificare dove si trovano i paesi nel mondo?', 'components/com_pqz/media/data/conf/congratulation-belle.csv'),
(6, 'Animali', 5, 1, 8, 0, 100, 'options', 1, 0, '', 'components/com_pqz/media/data/src/csv/animali.csv', 'Sai riconoscere gli animali?', 'components/com_pqz/media/data/conf/congratulation-belle.csv'),
(7, 'N-Atomico Completo', 5, 1, 8, 0, 100, 'options', 1, 0, '', 'components/com_pqz/media/data/src/csv/n-atomico.csv', 'Conosci i numeri atomici degli elementi? Attenzione ... è molto difficile ', 'components/com_pqz/media/data/conf/congratulation-belle.csv'),
(8, 'N-Atomico Facile', 5, 1, 8, 0, 1, 'options', 1, 0, '', 'components/com_pqz/media/data/src/csv/n-atomico.csv', 'Conosci i numeri atomici degli elementi più comuni? ', 'components/com_pqz/media/data/conf/congratulation-belle.csv');

