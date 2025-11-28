-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Nov 28, 2024 at 01:22 PM
-- Server version: 8.0.35
-- PHP Version: 8.2.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ArtGalleryManagement`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `add_exhibition` (IN `p_gallery_name` VARCHAR(100), IN `p_address` VARCHAR(255), IN `p_city` VARCHAR(50), IN `p_state` VARCHAR(50), IN `p_postal_code` VARCHAR(20), IN `p_country` VARCHAR(50), IN `p_e_start_date` DATE, IN `p_e_end_date` DATE, IN `p_admin_id` INT, IN `p_exhibition_image` VARCHAR(255))   BEGIN
    INSERT INTO Exhibition (gallery_name, address, city, state, postal_code, country, e_start_date, e_end_date, admin_id, exhibition_image)
    VALUES (p_gallery_name, p_address, p_city, p_state, p_postal_code, p_country, p_e_start_date, p_e_end_date, p_admin_id, p_exhibition_image);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_exhibition` (IN `p_exhibition_id` INT)   BEGIN
    DELETE FROM Exhibition WHERE exhibition_id = p_exhibition_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `read_exhibition` (IN `p_exhibition_id` INT)   BEGIN
    SELECT * FROM Exhibition WHERE exhibition_id = p_exhibition_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `update_exhibition` (IN `p_exhibition_id` INT, IN `p_gallery_name` VARCHAR(100), IN `p_address` VARCHAR(255), IN `p_city` VARCHAR(50), IN `p_state` VARCHAR(50), IN `p_postal_code` VARCHAR(20), IN `p_country` VARCHAR(50), IN `p_e_start_date` DATE, IN `p_e_end_date` DATE)   BEGIN
    UPDATE Exhibition
    SET gallery_name = p_gallery_name,
        address = p_address,
        city = p_city,
        state = p_state,
        postal_code = p_postal_code,
        country = p_country,
        e_start_date = p_e_start_date,
        e_end_date = p_e_end_date
    WHERE exhibition_id = p_exhibition_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `update_monthly_spotlight` ()   BEGIN
    -- Clear the current Monthly Spotlight records
    DELETE FROM Monthly_Spotlight;

    -- Insert top 7 artworks with the highest like_count into Monthly_Spotlight
    INSERT INTO Monthly_Spotlight (art_id, artist_id, m_date_start, m_date_end, popularity_score)
    SELECT art_id, artist_id, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 1 MONTH), like_count
    FROM Artwork
    ORDER BY like_count DESC
    LIMIT 7;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `Administrator`
--

CREATE TABLE `Administrator` (
  `admin_id` int NOT NULL,
  `admin_password` varchar(50) NOT NULL,
  `admin_username` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `Administrator`
--

INSERT INTO `Administrator` (`admin_id`, `admin_password`, `admin_username`) VALUES
(1, '1234', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `Artist`
--

CREATE TABLE `Artist` (
  `artist_id` int NOT NULL,
  `artist_fname` varchar(50) DEFAULT NULL,
  `artist_lname` varchar(50) DEFAULT NULL,
  `penname` varchar(50) DEFAULT NULL,
  `birth_place` varchar(100) DEFAULT NULL,
  `artist_address` varchar(255) DEFAULT NULL,
  `artist_username` varchar(50) NOT NULL,
  `artist_password` varchar(255) DEFAULT NULL,
  `artist_profile` varchar(255) DEFAULT NULL,
  `admin_id` int DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `Artist`
--

INSERT INTO `Artist` (`artist_id`, `artist_fname`, `artist_lname`, `penname`, `birth_place`, `artist_address`, `artist_username`, `artist_password`, `artist_profile`, `admin_id`) VALUES
(2, 'Pablo', 'Picasso', 'Picasso', 'Malaga, Spain', '456 Paint St, Barcelona', 'picasso456', 'password456', 'https://upload.wikimedia.org/wikipedia/commons/a/a7/Portrait_de_Picasso%2C_1908_%28background_retouched%29.jpg', 1),
(3, 'Vincent', 'van Gogh', 'Vincent van Gogh', 'Zundert, Netherlands', '789 Starry Way, Paris', 'vangogh789', 'password789', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcS8JnvY8KxFM8qPEb8hm5uNGxueValE8Qe9RQ&s', 1),
(4, 'Claude', 'Monet', 'Monet', 'Paris, France', '123 Water Lily Rd, Paris', 'monet123', 'password123', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcR9_SxaEBNdgKVmRROei8OmNIRG7aqZ2meeAA&shttps://cdn.britannica.com/57/250457-050-342611AD/Claude-Monet-French-Impressionist-painter.jpg?w=400&h=300&c=crop', 1),
(5, 'Salvador', 'Dalí', 'Dalí', 'Figueres, Spain', '456 Surreal St, Figueres', 'dali456', 'password456', 'https://upload.wikimedia.org/wikipedia/commons/2/24/Salvador_Dal%C3%AD_1939.jpg', 1),
(6, 'Georgia', 'O\'Keeffe', 'O\'Keeffe', 'Sun Prairie, USA', '789 Desert Dr, Santa Fe', 'okeeffe789', 'password789', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQ1ImZoCaEQP-I4eNqOLkxvNXmAQRxH0jK2fw&s', 1),
(7, 'Frida', 'Kahlo', 'Frida', 'Coyoacán, Mexico', '101 Casa Azul, Mexico City', 'kahlo101', 'password101', 'https://upload.wikimedia.org/wikipedia/commons/thumb/0/06/Frida_Kahlo%2C_by_Guillermo_Kahlo.jpg/440px-Frida_Kahlo%2C_by_Guillermo_Kahlo.jpg', 1),
(8, 'Henri', 'Matisse', 'Matisse', 'Le Cateau-Cambrésis, France', '202 Collage Ln, Nice', 'matisse202', 'password202', 'https://cdn.britannica.com/30/68730-050-4922D09B/Henri-Matisse.jpg', 1),
(9, 'Rembrandt', 'van Rijn', 'Rembrandt', 'Leiden, Netherlands', '303 Portrait Blvd, Amsterdam', 'rembrandt303', 'password303', 'https://cdn.britannica.com/82/190482-050-33D2C4C5/Self-Portrait-canvas-Rembrandt-van-Rijn-Washington-DC.jpg?w=400&h=300&c=crop', 1),
(10, 'Edvard', 'Munch', 'Munch', 'Loten, Norway', '404 Scream St, Oslo', 'munch404', 'password404', 'https://www.munchmuseet.no/globalassets/kunstverk/edvardmunchselvportrettgrafikk_crop.jpg?w=500&h=333&mode=Crop&quality=50&crop=0,420,1688,1545', 1),
(17, 'Rene', 'Magritte', 'Magritte', 'Lessines, Belgium', '1111 Mystery Ln, Brussels', 'magritte1111', 'password1111', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSqTCYIo4L8OKyRCBq_zrGpP5WXUx6YZVC3oA&s', 1),
(20, 'Sandro', 'Botticelli', 'Botticelli', 'Florence, Italy', '789 Renaissance Ave, Florence', 'botticelli789', 'password789', 'https://upload.wikimedia.org/wikipedia/commons/thumb/d/d4/Sandro_Botticelli_083.jpg/220px-Sandro_Botticelli_083.jpg', 1),
(21, 'Johannes', 'Vermeer', 'Vermeer', 'Delft, Netherlands', '101 Painter St, Delft', 'vermeer101', 'password101', 'https://i0.wp.com/blog.creativeflair.org/wp-content/uploads/2023/03/download-5-1.webp?fit=409%2C330&ssl=1', 1),
(22, 'Ravipas', 'Panutatpinyo', 'Blather2', 'Nonthaburi', 'Nonthaburi', 'ittodesu', '$2y$10$TicvVVZ4Ss9gnJBhRTTWjOXcZ4McQzyAGcPvokBzNsOFb7KtfJLKa', 'https://pbs.twimg.com/profile_images/1828114892840173569/GuWNer6C_400x400.jpg', 1),
(23, 'Grant', 'Wood', 'Grant Wood', 'Anamosa, USA', '505 Gothic Ln, Cedar Rapids', 'wood505', 'password505', 'https://upload.wikimedia.org/wikipedia/commons/0/02/Grant_Wood.jpg', 1);

-- --------------------------------------------------------

--
-- Table structure for table `Artwork`
--

CREATE TABLE `Artwork` (
  `art_id` int NOT NULL,
  `artist_id` int DEFAULT NULL,
  `art_name` varchar(100) DEFAULT NULL,
  `art_description` text,
  `art_type` varchar(50) DEFAULT NULL,
  `art_date` int DEFAULT NULL,
  `admin_id` int NOT NULL,
  `art_image` varchar(900) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `like_count` int DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `Artwork`
--

INSERT INTO `Artwork` (`art_id`, `artist_id`, `art_name`, `art_description`, `art_type`, `art_date`, `admin_id`, `art_image`, `like_count`) VALUES
(2, 2, 'Guernica', 'A powerful anti-war painting', 'Abstract', 1937, 1, 'https://upload.wikimedia.org/wikipedia/en/7/74/PicassoGuernica.jpg', 2),
(3, 3, 'Starry Night', 'A swirling night sky over a quiet town', 'Landscape', 1889, 1, 'https://upload.wikimedia.org/wikipedia/commons/thumb/e/ea/Van_Gogh_-_Starry_Night_-_Google_Art_Project.jpg/700px-Van_Gogh_-_Starry_Night_-_Google_Art_Project.jpg', 0),
(5, 2, 'The Weeping Woman', 'Portrayal of pain and suffering', 'Cubism', 1937, 1, 'https://upload.wikimedia.org/wikipedia/en/1/14/Picasso_The_Weeping_Woman_Tate_identifier_T05010_10.jpg', 0),
(6, 9, 'The Night Watch', 'A Dutch Golden Age military painting by Rembrandt', 'Baroque', 1642, 1, 'https://upload.wikimedia.org/wikipedia/commons/thumb/5/5a/The_Night_Watch_-_HD.jpg/760px-The_Night_Watch_-_HD.jpg', 0),
(7, 10, 'The Scream', 'Iconic expressionist painting depicting existential dread', 'Expressionism', 1893, 1, 'https://www.edvardmunch.org/assets/img/paintings/the-scream.jpg', 3),
(8, 5, 'The Persistence of Memory', 'A surreal landscape with melting clocks', 'Surrealism', 1931, 1, 'https://upload.wikimedia.org/wikipedia/en/d/dd/The_Persistence_of_Memory.jpg', 1),
(9, 20, 'The Birth of Venus', 'Depiction of the goddess Venus emerging from the sea', 'Renaissance', 1486, 1, 'https://upload.wikimedia.org/wikipedia/commons/thumb/0/0b/Sandro_Botticelli_-_La_nascita_di_Venere_-_Google_Art_Project_-_edited.jpg/1200px-Sandro_Botticelli_-_La_nascita_di_Venere_-_Google_Art_Project_-_edited.jpg', 1),
(10, 23, 'American Gothic', 'A depiction of a farmer and his daughter', 'American Regionalism', 1930, 1, 'https://upload.wikimedia.org/wikipedia/commons/thumb/c/cc/Grant_Wood_-_American_Gothic_-_Google_Art_Project.jpg/480px-Grant_Wood_-_American_Gothic_-_Google_Art_Project.jpg', 1),
(12, 7, 'Self-Portrait with Thorn Necklace and Hummingbird', 'A self-portrait by Frida Kahlo', 'Surrealism', 1940, 1, 'https://upload.wikimedia.org/wikipedia/en/1/1e/Frida_Kahlo_%28self_portrait%29.jpg', 0),
(13, 21, 'Girl with a Pearl Earring', 'A tronie of a girl wearing a pearl earring', 'Baroque', 1665, 1, 'https://upload.wikimedia.org/wikipedia/commons/thumb/0/0f/1665_Girl_with_a_Pearl_Earring.jpg/540px-1665_Girl_with_a_Pearl_Earring.jpg', 0),
(14, 4, 'Water Lilies', 'A series of approximately 250 paintings depicting Monet’s flower garden at Giverny', 'Impressionism', 1919, 1, 'https://upload.wikimedia.org/wikipedia/commons/thumb/9/9e/WLA_metmuseum_Water_Lilies_by_Claude_Monet.jpg/1198px-WLA_metmuseum_Water_Lilies_by_Claude_Monet.jpg', 2),
(22, 22, 'Lilibelle Halloween 2024', 'ขนมของคุณลูกกวาดน่ะ ลิลี่ขอนะ!!', 'Digital Painting', 2024, 1, 'https://pbs.twimg.com/media/GbJ54SzbwAA9Zi_?format=jpg&name=large', 3),
(23, 22, 'Farewell Minato Aqua', 'Thank you for six years!!', 'Digital Painting', 2024, 1, 'https://pbs.twimg.com/media/GV7DRvpaoAA2nmn?format=jpg&name=medium', 1),
(24, 6, 'Red Canna', 'A close-up of the red canna flower, showcasing the details of its petals', 'Floral', 1924, 1, 'https://upload.wikimedia.org/wikipedia/en/c/ca/Red_Canna_%281924%29_by_Georgia_O%27Keeffe.jpg', 0),
(25, 6, 'Blue and Green Music', 'An abstract interpretation of music in blue and green hues', 'Abstract', 1921, 1, 'https://upload.wikimedia.org/wikipedia/commons/8/84/Blue_and_Green_Music_by_Georgia_O%27Keeffe%2C_1921.jpg', 0),
(26, 8, 'The Dance', 'Depicts a group of people dancing in a ring in a joyous and dynamic manner', 'Modernism', 1910, 1, 'https://upload.wikimedia.org/wikipedia/en/thumb/a/a7/Matissedance.jpg/800px-Matissedance.jpg', 1),
(27, 8, 'Woman with a Hat', 'A colorful portrait of Matisse’s wife', 'Fauvism', 1905, 1, 'https://upload.wikimedia.org/wikipedia/en/thumb/f/fb/Matisse-Woman-with-a-Hat.jpg/1200px-Matisse-Woman-with-a-Hat.jpg', 1),
(28, 9, 'The Anatomy Lesson of Dr. Nicolaes Tulp', 'Depicts Dr. Nicolaes Tulp demonstrating a dissection to other surgeons', 'Baroque', 1632, 1, 'https://upload.wikimedia.org/wikipedia/commons/thumb/4/4d/Rembrandt_-_The_Anatomy_Lesson_of_Dr_Nicolaes_Tulp.jpg/540px-Rembrandt_-_The_Anatomy_Lesson_of_Dr_Nicolaes_Tulp.jpg', 0),
(29, 9, 'Self-Portrait as the Apostle Paul', 'A self-portrait of Rembrandt portraying himself as the Apostle Paul', 'Baroque', 1661, 1, 'https://upload.wikimedia.org/wikipedia/commons/thumb/3/3c/Rembrandt_Harmensz._van_Rijn_-_Zelfportret_als_de_apostel_Paulus_-_Google_Art_Project.jpg/540px-Rembrandt_Harmensz._van_Rijn_-_Zelfportret_als_de_apostel_Paulus_-_Google_Art_Project.jpg', 0),
(36, 17, 'The Lovers', 'Depicts two individuals with shrouded faces kissing', 'Surrealism', 1928, 1, 'https://www.renemagritte.org/assets/img/paintings/the-lovers-2.jpg', 0),
(37, 17, 'The False Mirror', 'A painting of an eye in surrealist style', 'Surrealism', 1929, 1, 'https://img.wikioo.org/ADC/art.nsf/get_large_image?Open&ra=8XYU7V', 0);

-- --------------------------------------------------------

--
-- Table structure for table `Audience`
--

CREATE TABLE `Audience` (
  `audience_id` int NOT NULL,
  `audience_fname` varchar(50) DEFAULT NULL,
  `audience_lname` varchar(50) DEFAULT NULL,
  `audience_email` varchar(100) DEFAULT NULL,
  `audience_phone_no` varchar(15) DEFAULT NULL,
  `audience_username` varchar(50) NOT NULL,
  `audience_password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `Audience`
--

INSERT INTO `Audience` (`audience_id`, `audience_fname`, `audience_lname`, `audience_email`, `audience_phone_no`, `audience_username`, `audience_password`) VALUES
(1, 'John', 'Doe', 'johndoe@example.com', '5551112222', 'johndoe', 'password1'),
(2, 'Jane', 'Smith', 'janesmith@example.com', '5553334444', 'janesmith', 'password2'),
(3, 'Alice', 'Johnson', 'alicej@example.com', '5555556666', 'alicej', 'password3'),
(4, 'Bob', 'Brown', 'bobbrown@example.com', '5557778888', 'bobbrown', 'password4'),
(7, 'Jay', 'Park', '6522771003@g.siit.tu.ac.th', '0981234512', 'js', '$2y$10$OVxnLyZwXD6/MPP4E8hBB.NNimAnVVyk6qGcQvrPDopiZvR8uwpXO'),
(8, 'vb', 'vbn', '6522771003@g.siit.tu.ac.th', '09812345', 'p', '$2y$10$sMhS0i2pknCK2l.5JuQ.F.my57hHKFu7wD5m1OGU4XAmcPkQVMRgK'),
(9, 'pim', 'on', NULL, NULL, 'q', '$2y$10$avE50tK987ebNnGSHKm/9ur//ZGPw0Igt3cHD6/UBXh3DqC1Ejk6K'),
(10, 'e', 'e', NULL, NULL, 'e', '$2y$10$NSWB0ptTEpM896pB1w4a9ukA9coSUAm8uchbqKnmbNOMRlA4bUqPe'),
(11, 'test edit', '1', 't1@gmail.com', '098272321', 't1', '$2y$10$Zln3OEZBq37.MBXYLNevn.82OWbsxJgbmDTCZV9YH5YeRfIWiPIwe');

-- --------------------------------------------------------

--
-- Table structure for table `Exhibition`
--

CREATE TABLE `Exhibition` (
  `exhibition_id` int NOT NULL,
  `gallery_name` varchar(100) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `state` varchar(50) DEFAULT NULL,
  `postal_code` varchar(20) DEFAULT NULL,
  `country` varchar(50) DEFAULT NULL,
  `e_start_date` date DEFAULT NULL,
  `e_end_date` date DEFAULT NULL,
  `admin_id` int NOT NULL,
  `exhibition_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `Exhibition`
--

INSERT INTO `Exhibition` (`exhibition_id`, `gallery_name`, `address`, `city`, `state`, `postal_code`, `country`, `e_start_date`, `e_end_date`, `admin_id`, `exhibition_image`) VALUES
(1, 'Art BKK 2025', '123 Art Street', 'Bangkok', 'Bangkok', '10100', 'Thailand', '2025-01-09', '2025-02-19', 1, 'https://pbs.twimg.com/media/GcmZBolbQAA58o7?format=jpg&name=small'),
(2, 'Sculpture World', '456 Creative Avenue', 'Bangkok', 'Bangkok', '10200', 'Thailand', '2024-12-04', '2025-02-10', 1, 'https://pbs.twimg.com/media/GcmZBomacAATUhX?format=jpg&name=small'),
(3, 'Ghibli Studio Exhibition', '789 Art Boulevard', 'Chiang Mai', 'Chiang Mai', '50000', 'Thailand', '2024-09-30', '2024-11-30', 1, 'https://pbs.twimg.com/media/GcmZBojagAIncQF?format=jpg&name=small'),
(4, 'SIIT Studio Art Show', '101 Innovation Drive', 'Pathum Thani', 'Pathum Thani', '12000', 'Thailand', '2024-09-30', '2024-11-01', 1, 'https://pbs.twimg.com/media/GcmcnIkbUAAFZup?format=jpg&name=medium'),
(5, 'Picasso Paradise', '202 Colorful Way', 'Bangkok', 'Bangkok', '10330', 'Thailand', '2024-09-30', '2024-12-15', 1, 'https://pbs.twimg.com/media/GcmcnIsaIAA86Xn?format=jpg&name=medium'),
(6, 'Blather1015 Days', '303 Vision Lane', 'Nonthaburi', 'Nonthaburi', '11000', 'Thailand', '2024-09-30', '2024-12-01', 1, 'https://pbs.twimg.com/media/GcmcnIhbYAAuRcw?format=jpg&name=large'),
(7, 'Modern Sculpture Wonders', '404 Exhibition Road', 'Bangkok', 'Bangkok', '10110', 'Thailand', '2024-08-15', '2024-10-05', 1, 'https://pbs.twimg.com/media/GcmcnIia4AAWfZn?format=jpg&name=large'),
(32, 'Art siit', 'bkk 24', 'bkk', 'bkk', '12345', 'thailand', '2024-11-10', '2024-11-30', 1, 'https://i.natgeofe.com/n/548467d8-c5f1-4551-9f58-6817a8d2c45e/NationalGeographic_2572187_2x1.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `Featured_In`
--

CREATE TABLE `Featured_In` (
  `art_id` int NOT NULL,
  `monthly_spotlight_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Likes`
--

CREATE TABLE `Likes` (
  `audience_id` int NOT NULL,
  `art_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `Likes`
--

INSERT INTO `Likes` (`audience_id`, `art_id`) VALUES
(1, 2),
(9, 2),
(7, 7),
(8, 7),
(11, 7),
(9, 8),
(7, 9),
(8, 10),
(7, 14),
(8, 14),
(7, 22),
(8, 22),
(10, 22),
(7, 23),
(7, 26),
(7, 27);

--
-- Triggers `Likes`
--
DELIMITER $$
CREATE TRIGGER `decrement_like_count` AFTER DELETE ON `Likes` FOR EACH ROW BEGIN
  -- Update the like_count in the Artwork table for the related artwork
  UPDATE Artwork a
  SET a.like_count = a.like_count - 1
  WHERE a.art_id = OLD.art_id;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `decrement_popularity_score` AFTER DELETE ON `Likes` FOR EACH ROW BEGIN
    -- Update the popularity_score in Monthly_Spotlight after a like is removed
    IF EXISTS (SELECT 1 FROM Monthly_Spotlight WHERE art_id = OLD.art_id) THEN
        UPDATE Monthly_Spotlight
        SET popularity_score = popularity_score - 1
        WHERE art_id = OLD.art_id;
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `update_like_count` AFTER INSERT ON `Likes` FOR EACH ROW BEGIN
  -- Update the like_count in the Artwork table for the related artwork
  UPDATE Artwork a
  SET a.like_count = a.like_count + 1
  WHERE a.art_id = NEW.art_id;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `update_popularity_score` AFTER INSERT ON `Likes` FOR EACH ROW BEGIN
    -- Update the popularity_score in Monthly_Spotlight after a new like is added
    IF EXISTS (SELECT 1 FROM Monthly_Spotlight WHERE art_id = NEW.art_id) THEN
        UPDATE Monthly_Spotlight
        SET popularity_score = popularity_score + 1
        WHERE art_id = NEW.art_id;
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `Monthly_Spotlight`
--

CREATE TABLE `Monthly_Spotlight` (
  `art_id` int NOT NULL,
  `artist_id` int NOT NULL,
  `m_date_start` date DEFAULT NULL,
  `m_date_end` date DEFAULT NULL,
  `monthly_spotlight_id` int NOT NULL,
  `popularity_score` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `Monthly_Spotlight`
--

INSERT INTO `Monthly_Spotlight` (`art_id`, `artist_id`, `m_date_start`, `m_date_end`, `monthly_spotlight_id`, `popularity_score`) VALUES
(2, 2, '2024-11-27', '2024-12-27', 29, 2),
(22, 22, '2024-11-27', '2024-12-27', 30, 3),
(7, 10, '2024-11-27', '2024-12-27', 32, 3),
(8, 5, '2024-11-27', '2024-12-27', 33, 1),
(9, 20, '2024-11-27', '2024-12-27', 34, 1),
(14, 4, '2024-11-27', '2024-12-27', 35, 2);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Administrator`
--
ALTER TABLE `Administrator`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `Artist`
--
ALTER TABLE `Artist`
  ADD PRIMARY KEY (`artist_id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Indexes for table `Artwork`
--
ALTER TABLE `Artwork`
  ADD PRIMARY KEY (`art_id`),
  ADD KEY `artist_id` (`artist_id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Indexes for table `Audience`
--
ALTER TABLE `Audience`
  ADD PRIMARY KEY (`audience_id`);

--
-- Indexes for table `Exhibition`
--
ALTER TABLE `Exhibition`
  ADD PRIMARY KEY (`exhibition_id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Indexes for table `Featured_In`
--
ALTER TABLE `Featured_In`
  ADD PRIMARY KEY (`art_id`,`monthly_spotlight_id`),
  ADD KEY `featured_in_ibfk_2` (`monthly_spotlight_id`);

--
-- Indexes for table `Likes`
--
ALTER TABLE `Likes`
  ADD PRIMARY KEY (`audience_id`,`art_id`),
  ADD KEY `likes_ibfk_2` (`art_id`);

--
-- Indexes for table `Monthly_Spotlight`
--
ALTER TABLE `Monthly_Spotlight`
  ADD PRIMARY KEY (`monthly_spotlight_id`),
  ADD KEY `art_id` (`art_id`),
  ADD KEY `popularity_score` (`popularity_score`),
  ADD KEY `monthly_spotlight_ibfk_2` (`artist_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Administrator`
--
ALTER TABLE `Administrator`
  MODIFY `admin_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `Artist`
--
ALTER TABLE `Artist`
  MODIFY `artist_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `Artwork`
--
ALTER TABLE `Artwork`
  MODIFY `art_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `Audience`
--
ALTER TABLE `Audience`
  MODIFY `audience_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `Exhibition`
--
ALTER TABLE `Exhibition`
  MODIFY `exhibition_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `Monthly_Spotlight`
--
ALTER TABLE `Monthly_Spotlight`
  MODIFY `monthly_spotlight_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `Artist`
--
ALTER TABLE `Artist`
  ADD CONSTRAINT `artist_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `Administrator` (`admin_id`);

--
-- Constraints for table `Artwork`
--
ALTER TABLE `Artwork`
  ADD CONSTRAINT `artwork_ibfk_1` FOREIGN KEY (`artist_id`) REFERENCES `Artist` (`artist_id`),
  ADD CONSTRAINT `artwork_ibfk_2` FOREIGN KEY (`admin_id`) REFERENCES `Administrator` (`admin_id`);

--
-- Constraints for table `Exhibition`
--
ALTER TABLE `Exhibition`
  ADD CONSTRAINT `exhibition_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `Administrator` (`admin_id`);

--
-- Constraints for table `Featured_In`
--
ALTER TABLE `Featured_In`
  ADD CONSTRAINT `featured_in_ibfk_1` FOREIGN KEY (`art_id`) REFERENCES `Artwork` (`art_id`),
  ADD CONSTRAINT `featured_in_ibfk_2` FOREIGN KEY (`monthly_spotlight_id`) REFERENCES `Monthly_Spotlight` (`monthly_spotlight_id`);

--
-- Constraints for table `Likes`
--
ALTER TABLE `Likes`
  ADD CONSTRAINT `likes_ibfk_1` FOREIGN KEY (`audience_id`) REFERENCES `Audience` (`audience_id`),
  ADD CONSTRAINT `likes_ibfk_2` FOREIGN KEY (`art_id`) REFERENCES `Artwork` (`art_id`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Constraints for table `Monthly_Spotlight`
--
ALTER TABLE `Monthly_Spotlight`
  ADD CONSTRAINT `monthly_spotlight_ibfk_2` FOREIGN KEY (`artist_id`) REFERENCES `Artist` (`artist_id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `monthly_spotlight_ibfk_3` FOREIGN KEY (`art_id`) REFERENCES `Artwork` (`art_id`) ON DELETE CASCADE ON UPDATE RESTRICT;

DELIMITER $$
--
-- Events
--
CREATE DEFINER=`root`@`localhost` EVENT `update_monthly_spotlight_event` ON SCHEDULE EVERY 1 MONTH STARTS '2024-12-01 00:00:00' ON COMPLETION NOT PRESERVE ENABLE DO CALL update_monthly_spotlight()$$

DELIMITER ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
