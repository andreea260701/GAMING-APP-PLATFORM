-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Gazdă: 127.0.0.1
-- Timp de generare: sept. 16, 2024 la 05:32 PM
-- Versiune server: 10.4.28-MariaDB
-- Versiune PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Bază de date: `quizdb`
--

-- --------------------------------------------------------

--
-- Structură tabel pentru tabel `games`
--

CREATE TABLE `games` (
  `id` int(6) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Eliminarea datelor din tabel `games`
--

INSERT INTO `games` (`id`, `name`, `description`, `price`, `created_at`) VALUES
(1, 'Tetris', 'Acesta este un joc numit tetris', 50.00, '2024-06-10 19:55:40'),
(2, 'Flappy Bird', 'Flappy bird', 60.00, '2024-06-15 14:42:53'),
(3, 'Pong', 'Acesta este jocul pong', 30.00, '2024-06-15 15:23:47'),
(4, 'Breakout', 'Acesta este jocul Breakout.', 55.00, '2024-06-15 15:24:21');

-- --------------------------------------------------------

--
-- Structură tabel pentru tabel `invoices`
--

CREATE TABLE `invoices` (
  `id` int(6) UNSIGNED NOT NULL,
  `purchase_id` int(6) UNSIGNED NOT NULL,
  `user_id` int(6) UNSIGNED NOT NULL,
  `invoice_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Eliminarea datelor din tabel `invoices`
--

INSERT INTO `invoices` (`id`, `purchase_id`, `user_id`, `invoice_date`) VALUES
(2, 2, 6, '2024-06-11 11:54:37'),
(12, 12, 6, '2024-06-16 00:34:44'),
(13, 13, 6, '2024-06-16 00:35:40'),
(14, 14, 6, '2024-06-16 00:35:46'),
(21, 21, 12, '2024-06-25 12:53:52');

-- --------------------------------------------------------

--
-- Structură tabel pentru tabel `purchases`
--

CREATE TABLE `purchases` (
  `id` int(6) UNSIGNED NOT NULL,
  `user_id` int(6) UNSIGNED NOT NULL,
  `game_id` int(6) UNSIGNED NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `purchase_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Eliminarea datelor din tabel `purchases`
--

INSERT INTO `purchases` (`id`, `user_id`, `game_id`, `amount`, `purchase_date`) VALUES
(2, 6, 1, 50.00, '2024-06-11 11:54:37'),
(12, 6, 2, 60.00, '2024-06-16 00:34:44'),
(13, 6, 3, 30.00, '2024-06-16 00:35:40'),
(14, 6, 4, 55.00, '2024-06-16 00:35:46'),
(21, 12, 3, 30.00, '2024-06-25 12:53:52');

-- --------------------------------------------------------

--
-- Structură tabel pentru tabel `questions`
--

CREATE TABLE `questions` (
  `id` int(6) UNSIGNED NOT NULL,
  `question_text` text NOT NULL,
  `choice1` text NOT NULL,
  `choice2` text NOT NULL,
  `choice3` text NOT NULL,
  `choice4` text NOT NULL,
  `correct_answer` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Eliminarea datelor din tabel `questions`
--

INSERT INTO `questions` (`id`, `question_text`, `choice1`, `choice2`, `choice3`, `choice4`, `correct_answer`) VALUES
(1, 'Care este titlul filmului de animație nominalizat la Oscar în 2006, despre un pește chirurg cu memorie scurtă?', 'Finding Dory', 'Shark Tale', 'Finding Nemo', 'The Little Mermaid', 'The Incredibles'),
(3, 'În ce an a fost lansat filmul \"The Departed\", regizat de Martin Scorsese?', '2003', '2006', '2007', '2005', '2006 '),
(4, 'Cine a interpretat rolul lui Tony Stark/Iron Man în filmul Marvel din 2008?', 'Chris Hemsworth', 'Chris Evans', 'Mark Ruffalo', 'Hugh Jackman', 'Robert Downey Jr.'),
(5, 'Care este numele personajului interpretat de Heath Ledger în \"The Dark Knight\" (2008)?', 'Harvey Dent', 'Ra s al Ghul', 'Bane', 'Scarecrow', 'The Joker'),
(6, 'Care dintre următoarele filme a fost inspirat dintr-un personaj Marvel și a fost lansat în 2016?', 'Wonder Woman', 'Doctor Strange', 'Logan', 'Guardians of the Galaxy Vol. 2', 'Deadpool'),
(7, 'Cine a regizat filmul de animație \"Ratatouille\" din 2007 despre un șobolan pasionat de gătit?', 'Pete Docter', 'Andrew Stanton', 'Lee Unkrich', 'Chris Sanders', 'Brad Bird'),
(8, 'Cine a interpretat rolul lui Jack Dawson în filmul \"Titanic\" din 1997?', 'Matt Damon', 'Johnny Depp', 'Brad Pitt', 'Tom Cruise', 'Leonardo DiCaprio'),
(9, 'Care este titlul primului film din seria \"Harry Potter\", lansat în 2001?', 'Harry Potter and the Goblet of Fire', 'Harry Potter and the Half-Blood Prince', 'Harry Potter and the Prisoner of Azkaban', 'Harry Potter and the Chamber of Secrets', 'Harry Potter and the Philosopher s Stone'),
(10, 'Cine a regizat trilogia \"The Lord of the Rings?', 'Steven Spielberg', 'George Lucas', 'Christopher Nolan', 'James Cameron', 'Peter Jackson'),
(11, 'În ce film din 1980, regizat de Stanley Kubrick, familia Torrance se mută într-un hotel izolat și supranatural?', 'Psycho', 'The Exorcist', 'Rosemary s Baby', 'Halloween', 'The Shining'),
(12, 'Cine a interpretat rolul lui Hannibal Lecter în \"The Silence of the Lambs\" (1991)?', 'Jack Nicholson', 'Al Pacino', 'Robert De Niro', 'Kevin Spacey', 'Anthony Hopkins'),
(13, 'Cine a regizat Jurassic Park din 1993, un film despre parc cu dinozauri clonați?', 'James Cameron', 'Ridley Scott', 'Michael Bay', 'Christopher Nolan', 'Steven Spielberg'),
(14, 'Cine a regizat filmul \"The Dark Knight Rises\" (2012), ultima parte a trilogiei Batman?', 'Tim Burton', 'Zack Snyder', 'Joss Whedon', 'Michael Bay', 'Christopher Nolan'),
(15, 'Ce actor a interpretat rolul principal în filmul \"The Wolf of Wall Street\" (2013), regizat de Martin Scorsese?', 'Ryan Gosling', 'Christian Bale', 'Matthew McConaughey', 'Tom Hardy', 'Leonardo DiCaprio'),
(16, 'Care este titlul filmului din 2014, în care Eddie Redmayne îl interpretează pe Stephen Hawking?', 'A Beautiful Mind', 'The Imitation Game', 'Interstellar', 'The Martian', 'The Theory of Everything'),
(17, 'Cine a regizat filmul de groază \"It\" (2017), bazat pe romanul lui Stephen King?', 'James Wan', 'Jordan Peele', 'Sam Raimi', 'Wes Craven', 'Andy Muschietti'),
(18, 'În ce film din 2000, Russell Crowe interpretează rolul lui Maximus, un general roman?', 'Troy', 'Braveheart', '300', 'Kingdom of Heaven', 'Gladiator'),
(19, 'Cine a interpretat rolul principal în filmul \"La La Land\" (2016), regizat de Damien Chazelle?', 'Bradley Cooper', 'Ryan Gosling', 'Anne Hathaway', 'Natalie Portman', 'Emma Stone'),
(20, 'Ce regizor a creat filmul animat \"Spirited Away\" (2001), câștigător al premiului Oscar pentru Cel Mai Bun Film de Animație?', 'Makoto Shinkai', 'Satoshi Kon', 'Mamoru Hosoda', 'Isao Takahata', 'Hayao Miyazaki'),
(21, 'Cine a regizat \"Inception\" (2010), un thriller psihologic despre furtul de informații din vise?', 'David Fincher', 'Darren Aronofsky', 'Denis Villeneuve', 'Guillermo del Toro', 'Christopher Nolan'),
(22, 'În ce film din 2008, Angelina Jolie îl interpretează pe personajul Salt, un agent CIA acuzat de trădare?', 'Mr. & Mrs. Smith', 'Wanted', 'Changeling', 'A Mighty Heart', 'Salt'),
(23, 'Cine a regizat \"The Grand Budapest Hotel\" (2014), un film de comedie și aventură cu Ralph Fiennes în rolul principal?', 'Quentin Tarantino', 'Sofia Coppola', 'Coen Brothers', 'Paul Thomas Anderson', 'Wes Anderson'),
(24, 'Ce actriță a câștigat un premiu Oscar pentru rolul său din filmul \"Black Swan\" (2010), regizat de Darren Aronofsky?', 'Scarlett Johansson', 'Mila Kunis', 'Cate Blanchett', 'Anne Hathaway', 'Natalie Portman'),
(25, 'În ce film din 2009, Christoph Waltz interpretează magistral rolul unui ofițer nazist învingător la Premiile Oscar?', 'Django Unchained', 'The Green Hornet', 'Water for Elephants', 'The Zero Theorem', 'Inglourious Basterds'),
(26, 'Cine a regizat \"Eternal Sunshine of the Spotless Mind\" (2004), un film despre ștergerea amintirilor dintr-o relație?', 'Spike Jonze', 'Charlie Kaufman', 'Wes Anderson', 'Richard Linklater', 'Michel Gondry'),
(27, 'Ce film din 2013 îl are pe Matthew McConaughey în rolul unui astronaut care caută o nouă planetă pentru a salva omenirea?', 'Gravity', 'The Martian', 'Prometheus', 'Moon', 'Interstellar'),
(28, 'Cine a regizat \"The Revenant\" (2015), film pentru care Leonardo DiCaprio a câștigat un premiu Oscar pentru cel mai bun actor?', 'Christopher Nolan', 'Denis Villeneuve', 'Ridley Scott', 'Alfonso Cuarón', 'Alejandro G. Iñárritu'),
(29, 'În ce film din 2004, Denzel Washington interpretează rolul unui fost ofițer de poliție corupt?', 'Man on Fire', 'American Gangster', 'Inside Man', 'The Book of Eli', 'Training Day'),
(30, 'Cine a regizat \"The Shape of Water\" (2017), film câștigător al premiului Oscar pentru Cel Mai Bun Film?', 'Steven Spielberg', 'Martin McDonagh', 'Christopher Nolan', 'Jordan Peele', 'Guillermo del Toro'),
(31, 'Cine a regizat filmul de groază \"A Quiet Place\" (2018), în care familia Abbott trăiește într-o lume în care creaturi extraterestre vânează după sunet?', 'Jordan Peele', 'James Wan', 'Ari Aster', 'Wes Craven', 'John Krasinski'),
(32, 'Ce actor a primit un premiu Oscar pentru interpretarea sa din filmul \"Dallas Buyers Club\" (2013), în care a jucat rolul unui om cu HIV?', 'Christian Bale', 'Jared Leto', 'Bradley Cooper', 'Leonardo DiCaprio', 'Matthew McConaughey'),
(33, 'În ce film din 2016, Emma Stone și Ryan Gosling interpretează personajele principale Mia și Sebastian, doi artiști aspiranți din Los Angeles?', 'Crazy, Stupid, Love', 'Gangster Squad', 'The Notebook', 'Whiplash', 'La La Land'),
(34, 'Cine a regizat \"Birdman\" (2014), un film despre un actor fost superstar de filme de supereroi care încearcă să își revină în lumea teatrală?', 'Christopher Nolan', 'Quentin Tarantino', 'Alfonso Cuarón', 'Martin Scorsese', 'Alejandro G. Iñárritu'),
(35, 'Ce actor a interpretat rolul principal în filmul \"Deadpool\" (2016), un anti-erou cu puteri de regenerare rapidă?', 'Chris Evans', 'Hugh Jackman', 'Andrew Garfield', 'Chris Hemsworth', 'Ryan Reynolds'),
(36, 'În ce film din 2019, Joaquin Phoenix interpretează rolul lui Arthur Fleck, un comediant eșuat care devine Joker-ul?', 'The Dark Knight Rises', 'Suicide Squad', 'Batman v Superman: Dawn of Justice', 'Birds of Prey', 'Joker'),
(37, 'În ce film din 2017, Gal Gadot interpretează rolul principal al Prințesei Diana, cunoscută sub numele de Wonder Woman?', 'Justice League', 'Batman v Superman: Dawn of Justice', 'Aquaman', 'Suicide Squad', 'Wonder Woman'),
(38, 'Ce film animat din 2018, produs de Pixar, explorează viața unui tânăr muzician mexican și a vieții de dincolo?', 'Up', 'Inside Out', 'Toy Story 4', 'Finding Dory', 'Coco'),
(39, 'În \"Jurassic Park\" (1993), cum reușește Tim să repornească sistemul electric al parcului?', 'Apăsând un buton de pe panoul de control', 'Rebootând sistemul informatic', 'Întorcând o manivelă într-o sală de control', 'Contactând un tehnician prin radio', 'Folosind un laptop'),
(40, 'În \"The Matrix Reloaded\" (2003), cum se numește bătălia epică dintre Neo și o mulțime de agenți Smith?', 'Bătălia Zion', 'Bătălia lui Trinity', 'Bătălia lui Morpheus', 'Bătălia din Realitatea Virtuală', 'Bătălia din Matrix'),
(41, 'În \"The Dark Knight Rises\" (2012), care este numele cod al planului lui Bane de a detona o bombă în Gotham City?', 'Operațiunea Terorii', 'Planul Zilei Judecății', 'Operațiunea Ciuma', 'Operațiunea Frica', 'Planul Gotham'),
(42, 'În \"Inception\" (2010), pentru a ieși dintr-un vis mai adânc, ce obiect folosește Dom Cobb?', 'Un clește', 'Un cub rubik', 'Un ceas de buzunar', 'O carte de tarot', 'Un vârf de spinner (topitor)'),
(43, 'În \"The Bourne Identity\" (2002), cum își recunoaște Jason Bourne adevărata identitate?', 'Vizionând o înregistrare video', 'Citi o carte de identitate', 'Prin analize de sânge', 'Recunoscând vocea sa', 'Identificându-se într-un oglinjoară'),
(44, 'În \"Mad Max: Fury Road\" (2015), cum se numește conducătorul societății totalitare aflată sub stăpânirea lui Immortan Joe?', 'Warlord', 'Kingpin', 'The Dictator', 'Overlord', 'Imperator'),
(45, 'În \"Die Hard\" (1988), care este mesajul pe care detectivul McClane îl scrie pe trupul unui terorist mort pentru a-i provoca pe ceilalți?', 'Welcome to the party, pal!', 'Ho-Ho-Ho, Now I Have a Machine Gun', 'I am just a fly in the ointment', 'Come out to the coast, we will get together, have a few laughs', 'Yippee-ki-yay, motherf****r!'),
(46, 'În \"The Shawshank Redemption\" (1994), ce carte le trimite Andy Dufresne închisilor?', 'The Count of Monte Cristo', 'The Art of War', 'The Prince', 'Les Misérables', 'The Bible'),
(47, 'În \"Kill Bill: Vol. 1\" (2003), ce nume de cod primește The Bride înainte de a fi lăsată să moară de către Bill și asasinii săi?', 'Copperhead', 'Cottonmouth', 'California Mountain Snake', 'Sidewinder', 'Black Mamba'),
(48, 'În \"The Revenant\" (2015), pentru ce motiv Hugh Glass, interpretat de Leonardo DiCaprio, este lăsat să moară de către membrii expediției sale?', 'Tradare', 'Lipsa de hrană', 'Boli infecțioase', 'Lipsa de apă', 'Răzbunare'),
(49, 'În \"Interstellar\" (2014), ce rol important joacă gaura de vierme descoperită de NASA în efortul de a salva omenirea?', 'O sursă de energie nelimitată', 'O poartă către o altă dimensiune', 'O cale de a evita prăbușirea Pământului', 'O amenințare la adresa vieții pe Pământ', 'O modalitate de a călători între galaxii'),
(50, 'În \"No Country for Old Men\" (2007), care este arma preferată a personajului Anton Chigurh, interpretat de Javier Bardem?', 'Pistol cu tambur', 'Pușcă cu lunetă', 'Pumnal pneumonic', 'Piston cu pernă de aer', 'Bolt gun (stingător cu pistol)'),
(51, 'În \"The Wolf of Wall Street\" (2013), cum este cunoscută tehnica de vânzare agresivă folosită de Jordan Belfort, interpretat de Leonardo DiCaprio?', 'Stratificarea prețurilor', 'Planul Ponzi', 'Pump and dump', 'Boiler room', 'Penny stocks');

-- --------------------------------------------------------

--
-- Structură tabel pentru tabel `users`
--

CREATE TABLE `users` (
  `id` int(6) UNSIGNED NOT NULL,
  `email` varchar(255) NOT NULL,
  `parola` varchar(255) NOT NULL,
  `nume` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `score` int(6) NOT NULL DEFAULT 0,
  `score_snake` int(6) NOT NULL DEFAULT 0,
  `score_tetris` int(6) NOT NULL DEFAULT 0,
  `score_flappy` int(6) NOT NULL DEFAULT 0,
  `score_pong` float NOT NULL DEFAULT 0,
  `score_breakout` int(6) NOT NULL DEFAULT 0,
  `status` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Eliminarea datelor din tabel `users`
--

INSERT INTO `users` (`id`, `email`, `parola`, `nume`, `username`, `score`, `score_snake`, `score_tetris`, `score_flappy`, `score_pong`, `score_breakout`, `status`) VALUES
(6, 'geta@gmail.com', '$2y$10$cfYlsUChfXYcxNoGZAbVd.Wx62iQtGEWnSGUkL.z0Ak2SaG5hqN4C', 'Geta', 'Geta20', 9, 0, 0, 0, 4.12, 0, 'user'),
(7, 'andreea.vechiu26@gmail.com', '$2y$10$Xgwqdo8vWPiyVtQ9gmvaieNCKOpGSMuV16MZHfUnXBFfCtdJhMCjG', 'Vechiu Florina', 'Andreea07', 0, 0, 0, 0, 0, 0, 'user'),
(12, 'test@gmail.com', '$2y$10$hYmMwjD1WoCtBjsZjbrF2ePH02PG3sz9g6sLc18.0K6tgXgs0MEoC', 'test', 'test', 0, 0, 0, 0, 1.41, 0, 'user'),
(13, 'andreea@gmail.com', '$2y$10$iCWM60.Rf1HbFbnPXt78b.4bLMFFz/UrF/dR240AGS/4AVgpcV4Km', 'Andreea', 'andreea26vechiu', 0, 0, 0, 0, 0, 0, 'admin');

--
-- Indexuri pentru tabele eliminate
--

--
-- Indexuri pentru tabele `games`
--
ALTER TABLE `games`
  ADD PRIMARY KEY (`id`);

--
-- Indexuri pentru tabele `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchase_id` (`purchase_id`),
  ADD KEY `invoices_ibfk_2` (`user_id`);

--
-- Indexuri pentru tabele `purchases`
--
ALTER TABLE `purchases`
  ADD PRIMARY KEY (`id`),
  ADD KEY `game_id` (`game_id`),
  ADD KEY `purchases_ibfk_1` (`user_id`);

--
-- Indexuri pentru tabele `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`);

--
-- Indexuri pentru tabele `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pentru tabele eliminate
--

--
-- AUTO_INCREMENT pentru tabele `games`
--
ALTER TABLE `games`
  MODIFY `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pentru tabele `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT pentru tabele `purchases`
--
ALTER TABLE `purchases`
  MODIFY `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT pentru tabele `questions`
--
ALTER TABLE `questions`
  MODIFY `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT pentru tabele `users`
--
ALTER TABLE `users`
  MODIFY `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constrângeri pentru tabele eliminate
--

--
-- Constrângeri pentru tabele `invoices`
--
ALTER TABLE `invoices`
  ADD CONSTRAINT `invoices_ibfk_1` FOREIGN KEY (`purchase_id`) REFERENCES `purchases` (`id`),
  ADD CONSTRAINT `invoices_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constrângeri pentru tabele `purchases`
--
ALTER TABLE `purchases`
  ADD CONSTRAINT `purchases_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `purchases_ibfk_2` FOREIGN KEY (`game_id`) REFERENCES `games` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
