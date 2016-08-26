CREATE TABLE `score` (
  `UserId` int(11) NOT NULL,
  `LeaderboardId` int(11) NOT NULL,
  `Score` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

CREATE TABLE `transaction` (
  `TransactionId` int(11) NOT NULL,
  `UserId` int(11) NOT NULL,
  `CurrencyAmount` int(11) NOT NULL,
  `Verifier` varchar(200) CHARACTER SET latin1 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

CREATE TABLE `user` (
  `UserId` int(11) NOT NULL,
  `Data` text CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

ALTER TABLE `score`
  ADD INDEX (`UserId`);
  
ALTER TABLE `score`
  ADD INDEX (`LeaderboardId`);

ALTER TABLE `transaction`
  ADD INDEX (`TransactionId`);

ALTER TABLE `user`
  ADD INDEX (`UserId`);
