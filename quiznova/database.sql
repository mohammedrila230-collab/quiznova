-- QuizNova database for ICT 2209 Mini Project
CREATE DATABASE IF NOT EXISTS quiz_nova CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE quiz_nova;

SET FOREIGN_KEY_CHECKS=0;
DROP TABLE IF EXISTS quiz_results;
DROP TABLE IF EXISTS messages;
DROP TABLE IF EXISTS questions;
DROP TABLE IF EXISTS users;
SET FOREIGN_KEY_CHECKS=1;

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(30) NOT NULL UNIQUE,
  email VARCHAR(120) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE questions (
  id INT AUTO_INCREMENT PRIMARY KEY,
  category VARCHAR(50) NOT NULL,
  difficulty ENUM('Easy','Medium','Hard') NOT NULL,
  question VARCHAR(255) NOT NULL,
  option_a VARCHAR(180) NOT NULL,
  option_b VARCHAR(180) NOT NULL,
  option_c VARCHAR(180) NOT NULL,
  option_d VARCHAR(180) NOT NULL,
  correct_answer ENUM('A','B','C','D') NOT NULL,
  INDEX idx_question_track (category, difficulty)
) ENGINE=InnoDB;

CREATE TABLE quiz_results (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  category VARCHAR(50) NOT NULL,
  difficulty ENUM('Easy','Medium','Hard') NOT NULL,
  score INT NOT NULL,
  total_questions INT NOT NULL,
  played_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_result_user (user_id),
  CONSTRAINT fk_results_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE messages (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(120) NOT NULL,
  message TEXT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO questions (category,difficulty,question,option_a,option_b,option_c,option_d,correct_answer) VALUES
('General Knowledge','Easy','What is the capital of France?','Berlin','Paris','Madrid','Rome','B'),
('General Knowledge','Easy','Which ocean is the largest?','Atlantic Ocean','Indian Ocean','Pacific Ocean','Arctic Ocean','C'),
('General Knowledge','Easy','How many continents are commonly recognized?','5','6','7','8','C'),
('General Knowledge','Easy','Which country is home to the city of Tokyo?','China','Japan','South Korea','Thailand','B'),
('General Knowledge','Easy','Which currency is used in the United Kingdom?','Euro','Pound sterling','Dollar','Yen','B'),
('General Knowledge','Medium','Which country is famous for the ancient city of Machu Picchu?','Peru','Mexico','Chile','Spain','A'),
('General Knowledge','Medium','Which language has the most native speakers worldwide?','English','Spanish','Mandarin Chinese','French','C'),
('General Knowledge','Medium','The River Nile flows into which sea?','Red Sea','Mediterranean Sea','Arabian Sea','Black Sea','B'),
('General Knowledge','Medium','Which city is the capital of Canada?','Toronto','Vancouver','Ottawa','Montreal','C'),
('General Knowledge','Medium','Which desert covers much of northern Africa?','Gobi','Sahara','Kalahari','Atacama','B'),
('General Knowledge','Hard','What is the smallest country in the world by area?','Monaco','Vatican City','San Marino','Liechtenstein','B'),
('General Knowledge','Hard','Which country contains the historic city of Timbuktu?','Mali','Niger','Chad','Senegal','A'),
('General Knowledge','Hard','Which line of latitude is located at approximately 23.5 degrees north?','Arctic Circle','Equator','Tropic of Cancer','Tropic of Capricorn','C'),
('General Knowledge','Hard','Which strait separates Spain from Morocco?','Bering Strait','Strait of Gibraltar','Bosporus','Strait of Hormuz','B'),
('General Knowledge','Hard','Which country has the largest land area in the world?','Canada','China','United States','Russia','D'),
('Science','Easy','What gas do humans need for respiration?','Carbon dioxide','Oxygen','Nitrogen','Helium','B'),
('Science','Easy','What is H2O commonly called?','Hydrogen','Salt','Water','Oxygen','C'),
('Science','Easy','Which part of a plant mainly absorbs water from the soil?','Flowers','Roots','Leaves','Fruit','B'),
('Science','Easy','Which planet is closest to the Sun?','Venus','Earth','Mercury','Mars','C'),
('Science','Easy','What force pulls objects toward Earth?','Magnetism','Gravity','Friction','Electricity','B'),
('Science','Medium','Which organ pumps blood around the human body?','Liver','Lungs','Heart','Kidney','C'),
('Science','Medium','What is the chemical symbol for gold?','Ag','Au','Gd','Go','B'),
('Science','Medium','Which planet has the most prominent ring system?','Mars','Venus','Saturn','Mercury','C'),
('Science','Medium','Which process do plants use to convert light energy into chemical energy?','Respiration','Photosynthesis','Fermentation','Transpiration','B'),
('Science','Medium','What is the pH of a neutral solution at about room temperature?','0','5','7','14','C'),
('Science','Hard','What is the SI unit of electric resistance?','Volt','Watt','Ohm','Ampere','C'),
('Science','Hard','Which particle has a negative electric charge?','Proton','Neutron','Electron','Photon','C'),
('Science','Hard','What is the approximate speed of light in vacuum?','300,000 km/s','30,000 km/s','3,000 km/s','3,000,000 km/s','A'),
('Science','Hard','Which law states that pressure and volume of a fixed amount of gas are inversely proportional at constant temperature?','Boyle''s law','Charles''s law','Ohm''s law','Hooke''s law','A'),
('Science','Hard','Which cell organelle is the main site of aerobic respiration?','Nucleus','Ribosome','Mitochondrion','Golgi apparatus','C'),
('Technology','Easy','What does CPU stand for?','Central Processing Unit','Computer Personal Unit','Central Program Utility','Core Processing User','A'),
('Technology','Easy','Which technology is primarily used to style web pages?','HTML','CSS','SQL','PHP','B'),
('Technology','Easy','Which device is commonly used to move a pointer on a desktop computer?','Printer','Mouse','Speaker','Scanner','B'),
('Technology','Easy','What does URL stand for?','Uniform Resource Locator','Universal Routing Link','User Resource Layer','Unified Reference Line','A'),
('Technology','Easy','Which file extension is commonly used for a JavaScript file?','.css','.js','.php','.sql','B'),
('Technology','Medium','Which language runs natively in web browsers for interactive client-side behavior?','JavaScript','PHP','C','SQL','A'),
('Technology','Medium','What does SQL primarily work with?','Images','Databases','Audio files','Animations','B'),
('Technology','Medium','Which protocol is commonly used for secure web browsing?','FTP','HTTP','HTTPS','SMTP','C'),
('Technology','Medium','Which HTML element is commonly used to create a hyperlink?','<link>','<a>','<href>','<nav>','B'),
('Technology','Medium','What is the purpose of a primary key in a relational database table?','To style rows','To uniquely identify each row','To encrypt the database','To create CSS classes','B'),
('Technology','Hard','Which data structure follows the LIFO principle?','Queue','Stack','Tree','Graph','B'),
('Technology','Hard','In networking, what does DNS mainly translate?','Domain names to IP addresses','Emails to passwords','Images to text','Files to folders','A'),
('Technology','Hard','Which Git command copies a remote repository to your computer?','git push','git clone','git merge','git status','B'),
('Technology','Hard','Which HTTP method is conventionally used to retrieve a resource without changing it?','POST','DELETE','GET','PATCH','C'),
('Technology','Hard','In a relational database, which SQL clause filters grouped results after GROUP BY?','WHERE','ORDER BY','HAVING','LIMIT','C'),
('Sports','Easy','How many players does one football team normally have on the field at the start of a match?','9','10','11','12','C'),
('Sports','Easy','Which sport uses a bat, ball and wickets?','Cricket','Tennis','Basketball','Swimming','A'),
('Sports','Easy','How many rings are in the Olympic symbol?','4','5','6','7','B'),
('Sports','Easy','Which sport is played at Wimbledon?','Tennis','Golf','Rugby','Cricket','A'),
('Sports','Easy','In basketball, how many points is a free throw worth?','1','2','3','4','A'),
('Sports','Medium','In tennis, what is the score called when both players have 40?','Tie','Deuce','Match point','Love','B'),
('Sports','Medium','A standard basketball team has how many players on the court at one time?','4','5','6','7','B'),
('Sports','Medium','Which country won the FIFA World Cup in 2022?','France','Brazil','Argentina','Germany','C'),
('Sports','Medium','In cricket, what is a score of zero by a batter commonly called?','Duck','Blank','Nil ball','Zero run','A'),
('Sports','Medium','How long is an Olympic swimming pool?','25 metres','40 metres','50 metres','100 metres','C'),
('Sports','Hard','In cricket, how many legal balls are in a standard over?','5','6','7','8','B'),
('Sports','Hard','Which event is part of a decathlon?','100 metres','Marathon','Swimming','Cycling','A'),
('Sports','Hard','In volleyball, how many players from one team are on court at a time?','5','6','7','8','B'),
('Sports','Hard','What is the maximum break possible in snooker under normal play without a free ball?','147','155','180','100','A'),
('Sports','Hard','In Formula One, what flag signals the end of a race?','Red flag','Blue flag','Chequered flag','Yellow flag','C'),
('Movies','Easy','Which film features a young lion named Simba?','Frozen','The Lion King','Toy Story','Finding Nemo','B'),
('Movies','Easy','Which animated film features sisters Elsa and Anna?','Frozen','Cars','Shrek','Moana','A'),
('Movies','Easy','What is the name of the cowboy toy in Toy Story?','Buzz','Woody','Rex','Andy','B'),
('Movies','Easy','Which superhero is also known as Bruce Wayne?','Superman','Batman','Spider-Man','Thor','B'),
('Movies','Easy','Which film series features a school called Hogwarts?','Harry Potter','The Matrix','Rocky','Jurassic Park','A'),
('Movies','Medium','Who directed the film Titanic?','Steven Spielberg','James Cameron','Christopher Nolan','Peter Jackson','B'),
('Movies','Medium','Which movie series features the character Jack Sparrow?','Harry Potter','Pirates of the Caribbean','The Matrix','Mission: Impossible','B'),
('Movies','Medium','Which fictional school does Harry Potter attend?','Hogwarts','Narnia Academy','Xavier Institute','Nevermore','A'),
('Movies','Medium','Which actor voices Woody in the original English-language Toy Story films?','Tom Hanks','Tim Allen','Robin Williams','Billy Crystal','A'),
('Movies','Medium','In The Lord of the Rings, what object must be destroyed in Mount Doom?','A crown','A sword','The One Ring','A crystal','C'),
('Movies','Hard','Which film won the Academy Award for Best Picture at the 2020 ceremony?','1917','Joker','Parasite','Ford v Ferrari','C'),
('Movies','Hard','Who directed Inception?','Christopher Nolan','Denis Villeneuve','Ridley Scott','David Fincher','A'),
('Movies','Hard','Which 1999 science-fiction film features the character Neo?','The Matrix','Blade Runner','Alien','Minority Report','A'),
('Movies','Hard','Which director made the films Pulp Fiction and Kill Bill?','Martin Scorsese','Quentin Tarantino','David Fincher','Francis Ford Coppola','B'),
('Movies','Hard','Which 1972 film centers on the Corleone crime family?','Goodfellas','Scarface','The Godfather','Taxi Driver','C');
