CREATE DATABASE IF NOT EXISTS bigdb;
USE bigdb;

CREATE TABLE IF NOT EXISTS big_table (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    gene_id VARCHAR(32) NOT NULL,
    sample_id INT UNSIGNED NOT NULL,
    value DOUBLE NOT NULL,
    created_at DATETIME NOT NULL,
    PRIMARY KEY (id),
    KEY idx_sample (sample_id)
) ENGINE=InnoDB;

