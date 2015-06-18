
--
-- Table structure for table `project`
--

CREATE TABLE IF NOT EXISTS `project` (
    `id_project` int(11) NOT NULL,
    `name` varchar(30) NOT NULL,
    `languages` varchar(30) NOT NULL,
    `path` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `translation`
--

CREATE TABLE IF NOT EXISTS `translation` (
    `id_project` int(11) NOT NULL,
    `code` varchar(50) NOT NULL,
    `language` varchar(2) DEFAULT NULL,
    `translation` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `project`
--
ALTER TABLE `project`
ADD PRIMARY KEY (`id_project`),
ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `translation`
--
ALTER TABLE `translation`
ADD UNIQUE KEY `unique_index` (`id_project`,`code`,`language`),
ADD KEY `id_project` (`id_project`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `project`
--
ALTER TABLE `project`
MODIFY `id_project` int(11) NOT NULL AUTO_INCREMENT;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `translation`
--
ALTER TABLE `translation`
ADD CONSTRAINT `translation_ibfk_1` FOREIGN KEY (`id_project`) REFERENCES `project` (`id_project`) ON DELETE CASCADE;
