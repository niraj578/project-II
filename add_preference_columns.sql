-- Add car_preference column to users table only
-- We'll use the existing 'model' column from cars table to determine types

ALTER TABLE users ADD COLUMN car_preference VARCHAR(50) DEFAULT NULL;
