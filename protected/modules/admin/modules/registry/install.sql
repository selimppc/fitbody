CREATE TABLE `yiiapp`.`registry` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(255) NOT NULL,
  `key` VARCHAR(255) NOT NULL,
  `type` ENUM('text','textarea','redactor','folder','date') NOT NULL,
  `parse` TINYINT(1) NOT NULL DEFAULT 0,
  `create_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `parent_category_id` INT(11) NULL,
  `old` TINYINT(1) NOT NULL DEFAULT 0,
  `position` INT(5) NOT NULL
  PRIMARY KEY (`id`))
ENGINE = InnoDB;

ALTER TABLE `yiiapp`.`registry`
ADD INDEX `fk_registry_1_idx` (`parent_category_id` ASC);
ALTER TABLE `yiiapp`.`registry`
ADD CONSTRAINT `fk_registry_1`
  FOREIGN KEY (`parent_category_id`)
  REFERENCES `yiiapp`.`registry` (`id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE;

CREATE TABLE `yiiapp`.`registry_value` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `registry_id` INT(11) NOT NULL,
  `language` VARCHAR(100) NOT NULL,
  `value` TEXT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_registry_value_1_idx` (`registry_id` ASC),
  CONSTRAINT `fk_registry_value_1`
    FOREIGN KEY (`registry_id`)
    REFERENCES `yiiapp`.`registry` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;

ALTER TABLE `yiiapp`.`registry_value`
ADD COLUMN `create_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `value`,
ADD COLUMN `old` TINYINT(1) NOT NULL DEFAULT 0 AFTER `create_date`;

ALTER TABLE `yiiapp`.`registry`
DROP COLUMN `old`;

ALTER TABLE `yiiapp`.`registry`
DROP COLUMN `position`;

ALTER TABLE `yiiapp`.`registry`
CHANGE COLUMN `type` `type` ENUM('text','textarea','redactor','folder','date','email') CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL ;

ALTER TABLE `yiiapp`.`registry`
DROP COLUMN `parse`;
